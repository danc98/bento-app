<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class ProductController extends Controller
{
    /**
     * Return a listing of all products with active labels.
     */
    public function index() {
        $active_ids = app(LabelController::class)->active();
        $products = array();

        foreach ($active_ids as $active_id) {
            $products[] = $this->retrieve($active_id);
        }

        return $products;
    }

    /**
     * Return all products, even those without active labels.
     * Note: For admin panel use only.
     */
    public function indexAdmin() {
        $products = array();

        foreach (Product::all() as $product) {
            $products[] = [
                'product_id'   => $product->id,
                'name'         => $product->item_name,
                'plu_cd'       => $product->plu_cd,
                'price'        => $product->item_price,
                'desc'         => $product->item_desc,
                'img'          => $product->item_img,
                'ins_datetime' => $product->created_at,
                'upd_datetime' => $product->updated_at,
                'stock'        => $product->stock(),
                'category'     => $product->category(),
            ];
        }

        return $products;
    }

    /**
     * Get the specified product.
     */
    public function retrieve(string $product_id) {
        $product = Product::findOrFail($product_id);

        $listing = [
            'product_id'   => $product->id,
            'name'         => $product->item_name,
            'plu_cd'       => $product->plu_cd,
            'price'        => $product->item_price,
            'desc'         => $product->item_desc,
            'img'          => $product->item_img,
            'ins_datetime' => $product->created_at,
            'upd_datetime' => $product->updated_at,
            'stock'        => $product->stock(),
            'category'     => $product->category(),
        ];

        return $listing;
    }

    /**
     * Get a listing of all products filtered by category.
     */
    public function filter(string $category) {
        $products = $this->index();
        $filtered = [];
        
        foreach ($products as $product) {
            if ($product['category'] == $category) {
                $filtered[] = $product;
            }
        }

        return $filtered;
    }

    /**
     * Get a listing of all products whose name contains the search condition.
     */
    public function search(Request $request) {
        $products = $this->index();
        $name = $this->cleanInput($request->input('q'));
        $filtered = [];
        
        foreach ($products as $product) {
            if (str_contains(strtolower($product['name']), strtolower($name))) {
                $filtered[] = $product;
            }
        }

        // Save the searched product for back button purposes.
        $request->session()->put('p_search', $name);

        // Flush category cache to avoid crossover.
        $request->session()->put('f_category', '');

        return View::make('homepage')
        ->with('products', $filtered)
        ->with('categories', Category::all());
    }

    /**
     * Stores an image.
     */
    public function storeImage($image) {
        $path = $image->store('', 'public');
        return $path;
    }

    /**
     * Adds a new product to the database.
     */
    public function store(Request $request): RedirectResponse {
        $result = $this->validateProduct($request);
        // Note: Laravel has native validation functionality, so this is in essence
        // re-inventing the wheel. You do get more fine-tuned validation with this
        // method, but I imagine you can accomplish the same with Larvel's native
        // tools, I just simply lack the knowledge to do so at the moment.

        if (count($result['errors']) === 0) {
            $product_info = $result['product_info'];

            $product = new Product;
            $category_id = app(CategoryController::class)->retrieveOrMake($product_info['category']);
            $item_img = $product_info['item_img'];

            // Only store non-placeholder images.
            if ($item_img != "placeholder.png") {
                $item_img = $this->storeImage($request->item_img);
            }

            $product->item_name   = $product_info['item_name'];
            $product->plu_cd      = $product_info['plu_cd'];
            $product->item_price  = $product_info['item_price'];
            $product->item_desc   = $product_info['item_desc'];
            $product->item_img    = $item_img;
            if (!empty($category_id)) {
                $product->category_id = $category_id;
            }

            $product->save();
        } else {
            return Redirect::route('admin.add-product')->withErrors($result['errors']);
        }

        return Redirect::route('admin.products');
    }

    /**
     * Deletes all products given in the request.
     */
    public function delete(Request $request): RedirectResponse {
        // Given the hypothetical system this program would be attached to,
        // soft deletion would be ideal, but so as not to clog up the database
        // with test data, hard deletion is implemented.
        $products = array_filter(explode(",", $request["selected_products"]));
        if (count($products) != 0) {
            Product::destroy($products);
        }

        // Checks if any categories are newly orphaned, and deletes them.
        // Remove if you wish to preserve categories without products.
        $this->flushCategories();

        return Redirect::route('admin.products');
    }

    /**
     * Deletes orphaned products as a result of deleting a product.
     */
    private function flushCategories() {
        // Pulls all category_ids attached to products.
        $items = Product::distinct()->select('category_id')->get()->toArray();

        // Converts into usable format for CategoryController.
        $active_categories = array();
        foreach ($items as $item) {
            $active_categories[] = $item["category_id"];
        }
        
        app(CategoryController::class)->flushCategories($active_categories);
    }

    /**
     * Private input validation function for working with products.
     */
    private function cleanInput($input) {
        if (empty($input)) { return $input; }
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);
        return $input;
    }

    
    /**
     * Private input validation function for adding a product to the database.
     */
    private function validateProduct(Request $request) {
        $product_info = array();
        $errors = array();

        // Name
        $item_name = $this->cleanInput($request['item_name']);

        // Could set a default name, but for now making name a requirement.
        if ($item_name == "") {
            $errors['item_name'] = "Please enter a name for the product.";
        }

        // Shorten long item names rather than error them.
        if (mb_strlen($item_name) > 25) {
            $item_name = mb_substr($item_name, 0, 25);
        }

        $product_info['item_name'] = $item_name;

        // PLU Code
        $plu_cd = $request['plu_cd'];

        if ($plu_cd == "") {
            $errors['plu_cd'] = "Please enter a PLU code.";
        } else if (!preg_match('/^[0-9]+$/', $plu_cd)) {
            $errors['plu_cd'] = "Please enter a PLU code with numeric values only.";
        }

        // Here you would call a function to validate whether the number inputted
        // qualifies as valid PLU, but since this is a test site inputting such logic
        // is outside the scope of this project.
        // if (!is_valid_plu($plu_cd)) { $errors['plu_cd']  = "Please enter a valid PLU."; }

        $product_info['plu_cd'] = $plu_cd;

        // Price
        $item_price = $request['item_price'];

        // Remove all commas to prevent non-numeric errors.
        // (Yes, this could be handled by the regex, but it's complex enough already.)
        $item_price = str_replace(",", "", $item_price);

        // You must have a price, even if it's 0.
        if ($item_price == "") {
            $errors['item_price'] = "Please enter a price for the product.";
        }

        // Both whole prices (10) and float values (10.99) are accepted.
        else if (!preg_match('/^\d+(\.\d{2})?$/', $item_price)) {
            $errors['item_price'] = "Please enter a valid price.";
        }

        $product_info['item_price'] = $item_price;

        // Description
        $item_desc = $this->cleanInput($request['item_desc']);

        // Shorten long descriptions rather than error them.
        if (mb_strlen($item_desc) > 125) {
            $item_desc = mb_substr($item_desc, 0, 125);
        }

        $product_info['item_desc'] = $item_desc;

        // Image URL
        $item_img = "";

        if (empty($request->item_img)) {
            // Default placeholder image.
            $item_img = "placeholder.png";
        } else {
            $approved_extensions = ["png", "jpeg", "jpg"];
            if (!in_array($request->item_img->extension(), $approved_extensions)) {
                $errors['item_img'] = "Please upload a valid image. (.png, .jpeg, .jpg)";
            } else {
                $item_img = $request->item_img->getClientOriginalName();
            }
        }

        $product_info['item_img'] = $item_img;

        // Category
        // Insert/Update logic will be handled elsewhere, so for now just sanitize input.
        $category = $this->cleanInput($request['category']);

        $product_info['category'] = $category;

        $return_array = array();
        $return_array['errors'] = $errors;
        $return_array['product_info'] = $product_info;

        return $return_array;
    }

}
