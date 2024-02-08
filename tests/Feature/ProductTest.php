<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Http\Controllers\ProductController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Test that stock is correct for an item with only active labels.
     */
    public function test_product_stock_only_active_labels(): void {
        // "Fish Box" has three active labels.
        $fish_box = Product::findOrFail('6');
        $this->assertEquals("3", $fish_box->stock());
    }

    /**
     * Test that stock is zero for an inactive item.
     */
    public function test_product_stock_no_active_labels(): void {
        // "Inactive" only has a single processed, non-active label.
        $inactive_product = Product::findOrFail('8');
        $this->assertEquals("0", $inactive_product->stock());
    }

    /**
     * Test that stock is correct for an item with a mix of
     * active/inactive labels.
     */
    public function test_product_stock_active_and_inactive_labels(): void {
        // "Taco Box" has two active labels, and one printed/non-active label.
        $taco_box = Product::findOrFail('1');
        $this->assertEquals("2", $taco_box->stock());
    }

    /**
     * Test that products pulled are properly formatted.
     */
    public function test_index_proper_format(): void {
        $products = app(ProductController::class)->index();
        $this->assertNotEmpty($products);

        // Tests that all items are pulled with proper format.
        foreach ($products as $product) {
            $this->assertArrayHasKey('name', $product, "name has not been set.");
            $this->assertNotEmpty($product['name'], "name is empty.");

            $this->assertArrayHasKey('plu_cd', $product, "plu_cd has not been set.");
            $this->assertNotEmpty($product['plu_cd'], "plu_cd is empty.");

            $this->assertArrayHasKey('price', $product, "price has not been set.");
            $this->assertGreaterThanOrEqual(0, $product['price'], "price is empty.");

            $this->assertArrayHasKey('desc', $product, "desc has not been set.");
            // $this->assertNotEmpty($product['desc'], "desc is empty.");  // Desc. can be empty.

            $this->assertArrayHasKey('img', $product, "img has not been set.");
            $this->assertNotEmpty($product['img'], "img is empty.");

            $this->assertArrayHasKey('ins_datetime', $product, "ins_datetime has not been set.");
            $this->assertNotEmpty($product['ins_datetime'], "ins_datetime has not been set.");

            $this->assertArrayHasKey('upd_datetime', $product, "upd_datetime has not been set.");
            $this->assertNotEmpty($product['upd_datetime'], "upd_datetime is empty.");

            $this->assertArrayHasKey('stock', $product, "stock has not been set.");
            $this->assertGreaterThanOrEqual(0, $product['stock'], "stock is empty.");

            $this->assertArrayHasKey('category', $product, "category has not been set.");
            $this->assertNotEmpty($product['category'], "category is empty.");
        }
    }

    /**
     * Test that only products with active labels are returned.
     */
    public function test_index_only_finds_active_products(): void {
        // Pull all active products.
        $products = app(ProductController::class)->index();
        $this->assertNotEmpty($products);

        // Stock > 0 means an active label exists.
        // Stock = 0 is an inactive product, and should not be pulled.
        foreach ($products as $product) {
            $this->assertGreaterThan(
                0,
                $product['stock'], 
                $product['name'] . " is not active.");
        }
    }

    /**
     * Searches for "Inactive", a seeded product that deliberately
     * has no active labels, and thus would not show up when using index().
     */
    public function test_index_admin_pulls_inactive_products(): void {
        $products = app(ProductController::class)->indexAdmin();
        $this->assertNotEmpty($products);

        // Inactive product should exist, even with no active labels.
        $inactive_product = NULL;
        foreach ($products as $product) {
            if ($product['stock'] == 0) {
                $inactive_product = $product;
            }
        }
        $this->assertNotNull($inactive_product);
    }

    /**
     * Test that products pulled via indexAdmin are properly formatted.
     */
    public function test_index_admin_proper_format(): void {
        $products = app(ProductController::class)->indexAdmin();
        $this->assertNotEmpty($products);

        // Tests that all items are pulled with proper format.
        foreach ($products as $product) {
            $this->assertArrayHasKey('name', $product, "name has not been set.");
            $this->assertNotEmpty($product['name'], "name is empty.");

            $this->assertArrayHasKey('plu_cd', $product, "plu_cd has not been set.");
            $this->assertNotEmpty($product['plu_cd'], "plu_cd is empty.");

            $this->assertArrayHasKey('price', $product, "price has not been set.");
            $this->assertGreaterThanOrEqual(0, $product['price'], "price is empty.");

            $this->assertArrayHasKey('desc', $product, "desc has not been set.");
            // $this->assertNotEmpty($product['desc'], "desc is empty.");  // Desc. can be empty.

            $this->assertArrayHasKey('img', $product, "img has not been set.");
            $this->assertNotEmpty($product['img'], "img is empty.");

            $this->assertArrayHasKey('ins_datetime', $product, "ins_datetime has not been set.");
            $this->assertNotEmpty($product['ins_datetime'], "ins_datetime has not been set.");

            $this->assertArrayHasKey('upd_datetime', $product, "upd_datetime has not been set.");
            $this->assertNotEmpty($product['upd_datetime'], "upd_datetime is empty.");

            $this->assertArrayHasKey('stock', $product, "stock has not been set.");
            $this->assertGreaterThanOrEqual(0, $product['stock'], "stock is empty.");

            $this->assertArrayHasKey('category', $product, "category has not been set.");
            $this->assertNotEmpty($product['category'], "category is empty.");
        }
    }

    /**
     * Test that a given active product_id is correctly pulled.
     */
    public function test_retrieve_product(): void {
        // Product to find.
        DB::table('products')->insert([
            'id'          => '10',
            'item_name'   => 'Waldo',
            'plu_cd'      => '594539430',
            'item_price'  => '200',
            'item_desc'   => 'Found you!',
            'item_img'    => 'placeholder.png',
            'created_at'  => '2023-12-01 12:00:00',
            'updated_at'  => '2023-12-01 12:00:00',
            'category_id' => '5', // Corresponds to "Test" category.
        ]);

        $product_id = '10';
        $pulled_product = app(ProductController::class)->retrieve($product_id);

        // Checking parameters.
        $this->assertNotEmpty($pulled_product);
        $this->assertEquals("10",                  $pulled_product['product_id'],   "product_id not correct.");
        $this->assertEquals("Waldo",               $pulled_product['name'],         "item_name not correct.");
        $this->assertEquals("594539430",           $pulled_product['plu_cd'],       "plu_cd not correct.");
        $this->assertEquals("200",                 $pulled_product['price'],        "item_price not correct.");
        $this->assertEquals("Found you!",          $pulled_product['desc'],         "item_desc not correct.");
        $this->assertEquals("placeholder.png",     $pulled_product['img'],          "item_img not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_product['ins_datetime'], "ins_datetime not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_product['upd_datetime'], "upd_datetime not correct.");
        $this->assertEquals("0",                   $pulled_product['stock'],        "stock not correct."); // No active labels, so stock should be 0.
        $this->assertEquals("Test",                $pulled_product['category'],     "category not correct.");
    }

    /**
     * Test that all products associated with a category are pulled.
     */
    public function test_retrieve_products_by_category(): void {
        // Taco Box and Steak Box
        $search_category = "Beef";
        $beef_products = app(ProductController::class)->filter($search_category);

        $this->assertNotEmpty($beef_products);
        $this->assertEquals(2, count($beef_products));

        // Pulling out just names.
        $product_names = array();
        foreach ($beef_products as $product) {
            $product_names[] = $product['name'];
        }

        $this->assertTrue(in_array("Taco Box", $product_names));
        $this->assertTrue(in_array("Steak Box", $product_names));
    }

    /**
     * Test that filter returns no products when given a non-existant category.
     */
    public function test_retrieve_no_products_when_non_existant_category(): void {
        $imaginary_category = "Ghost";
        $ghost_products = app(ProductController::class)->filter($imaginary_category);
        $this->assertEmpty($ghost_products);
    }

    /**
     * Test that info is being passed along to view from search().
     */
    public function test_search_info_sent_to_view(): void {
        $response = $this->get('/search', ['q' => "Taco"]);
        $response->assertStatus(200);
        $response->assertViewHas('products');
        $response->assertViewHas('categories');
    }

    /**
     * Test that the correct product is found when searching.
     */
    public function test_search_find_correct_product(): void {
        // Taco Box
        $response = $this->get('/search?q=Taco');
        $response->assertStatus(200);
        
        $search_results = $response->getOriginalContent()->getData()['products'];
        $this->assertNotEmpty($search_results);
        $this->assertEquals(1, count($search_results));

        $found_product = $search_results[0];
        $this->assertEquals("Taco Box", $found_product['name']);
    }

    /**
     * Test that no product is returned when searching for an item the doesn't exist.
     */
    public function test_search_empty_for_non_existant_product(): void {
        // Non-existant product.
        $response = $this->get('/search?q=Ghost');
        $response->assertStatus(200);
        
        $search_results = $response->getOriginalContent()->getData()['products'];
        $this->assertEmpty($search_results);
    }

    /**
     * Test that no product is returned when searching for an inactive item.
     */
    public function test_search_empty_for_inactive_product(): void {
        // "Inactive" the product exists, but has no active labels.
        $response = $this->get('/search?q=Inactive');
        $response->assertStatus(200);
        
        $search_results = $response->getOriginalContent()->getData()['products'];
        $this->assertEmpty($search_results);
    }

    /**
     * Test that a given product is properly stored.
     */
    public function test_store_product_with_no_image(): void {
        $request = ['item_name'  => 'Apple',
                    'plu_cd'     => '49504319234302',
                    'item_price' => '444',
                    'item_desc'  => 'An apple.',
                    'category'   => 'Test'];

        // Send the request via the add route.
        $this->followingRedirects()->post('/admin/products/add', $request)->assertStatus(200);

        $saved_product = DB::table('products')->where('item_name', 'Apple')->get();
        $this->assertNotEmpty($saved_product);

        // Checking parameters.
        $saved_product = $saved_product[0];
        $this->assertNotEmpty($saved_product);
        $this->assertEquals("Apple",           $saved_product->item_name,   "item_name not correct.");
        $this->assertEquals("49504319234302",  $saved_product->plu_cd,      "plu_cd not correct.");
        $this->assertEquals("444",             $saved_product->item_price,  "item_price not correct.");
        $this->assertEquals("An apple.",       $saved_product->item_desc,   "item_desc not correct.");
        $this->assertEquals("placeholder.png", $saved_product->item_img,    "item_img not correct.");
        $this->assertEquals("5",               $saved_product->category_id, "category_id not correct.");  // Corresponds to "Test" category.

        // These parameters are set using execution time, so just check they are not empty.
        $this->assertNotEmpty($saved_product->created_at);
        $this->assertNotEmpty($saved_product->updated_at);
    }

    /**
     * Test that a given image is proprely stored when a product is added.
     */
    public function test_store_product_with_image(): void {
        // Creating a fake testing image.
        Storage::fake('public');
        $file = UploadedFile::fake()->image('apple.jpg');
        
        $request = ['item_name'  => 'Apple',
                    'plu_cd'     => '49504319234302',
                    'item_price' => '444',
                    'item_desc'  => 'An apple.',
                    'item_img'   => $file,
                    'category'   => 'Test'];

        // Send the request via the add route.
        $this->followingRedirects()->post('/admin/products/add', $request)->assertStatus(200);

        // Check that file was saved.
        Storage::disk('public')->assertExists($file->hashName());
    }

    /**
     * Test that an invalid image isn't stored.
     */
    public function test_store_product_with_invalid_image(): void {
        // Creating a fake, inavlid testing image.
        Storage::fake('public');
        $file = UploadedFile::fake()->image('apple.csv');

        $request = ['item_name'  => 'Apple',
                    'plu_cd'     => '49504319234302',
                    'item_price' => '444',
                    'item_desc'  => 'An apple.',
                    'item_img'   => $file,
                    'category'   => 'Test'];

        // Send the request via the add route.
        $this->followingRedirects()->post('/admin/products/add', $request)->assertStatus(200);

        // Check that file was not saved saved.
        Storage::disk('public')->assertMissing($file->hashName());
    }

    /**
     * Test empty item_name during storage.
     */
    public function test_store_product_with_empty_item_name(): void {
        $request = ['item_name'  => '',
                    'plu_cd'     => '49504319234302',
                    'item_price' => '444',
                    'item_desc'  => 'An apple.',
                    'category'   => 'Test'];

        // Send the request via the add route.
        $response = $this->followingRedirects()->post('/admin/products/add', $request);

        $response->assertViewHas('errors');
        $error = $response->viewData('errors')->all();
        $this->assertEquals("Please enter a name for the product.", $error[0]);
    }

    /**
     * Test empty plu_cd during storage.
     */
    public function test_store_product_with_empty_plu_cd(): void {
        $request = ['item_name'  => 'Apple',
                    'plu_cd'     => '',
                    'item_price' => '444',
                    'item_desc'  => 'An apple.',
                    'category'   => 'Test'];

        // Send the request via the add route.
        $response = $this->followingRedirects()->post('/admin/products/add', $request);

        $response->assertViewHas('errors');
        $error = $response->viewData('errors')->all();
        $this->assertEquals("Please enter a PLU code.", $error[0]);
    }

    /**
     * Test invalid PLU code during storage.
     */
    public function test_store_product_with_invalid_plu(): void {
        $request = ['item_name'  => 'Apple', 
                    'plu_cd'     => 'abc',
                    'item_price' => '444',
                    'item_desc'  => 'An apple.',
                    'category'   => 'Test'];

        // Send the request via the add route.
        $response = $this->followingRedirects()->post('/admin/products/add', $request);

        $response->assertViewHas('errors');
        $error = $response->viewData('errors')->all();
        $this->assertEquals("Please enter a PLU code with numeric values only.", $error[0]);
    }

    /**
     * Test empty price during storage.
     */
    public function test_store_product_with_empty_price(): void {
        $request = ['item_name'  => 'Apple',
                    'plu_cd'     => '49504319234302',
                    'item_price' => '',
                    'item_desc'  => 'An apple.',
                    'category'   => 'Test'];

        // Send the request via the add route.
        $response = $this->followingRedirects()->post('/admin/products/add', $request);

        $response->assertViewHas('errors');
        $error = $response->viewData('errors')->all();
        $this->assertEquals("Please enter a price for the product.", $error[0]);
    }

    /**
     * Test invalid price during storage.
     */
    public function test_store_product_with_invalid_price(): void {
        $request = ['item_name'  => 'Apple',
                    'plu_cd'     => '49504319234302',
                    'item_price' => 'abc',
                    'item_desc'  => 'An apple.',
                    'category'   => 'Test'];

        // Send the request via the add route.
        $response = $this->followingRedirects()->post('/admin/products/add', $request);

        $response->assertViewHas('errors');
        $error = $response->viewData('errors')->all();
        $this->assertEquals("Please enter a valid price.", $error[0]);
    }

    /**
     * Test that a given product is properly deleted.
     */
    public function test_delete_product(): void {
        // Product to be deleted.
        DB::table('products')->insert([
            'id'          => '10',
            'item_name'   => 'Target',
            'plu_cd'      => '594539430',
            'item_price'  => '200',
            'item_desc'   => 'Delete me!',
            'item_img'    => 'placeholder.png',
            'created_at'  => '2023-12-01 12:00:00',
            'updated_at'  => '2023-12-01 12:00:00',
            'category_id' => '5',
        ]);

        // Ensure it was saved.
        $saved_product = DB::table('products')->where('id', '10')->get();
        $this->assertNotEmpty($saved_product);

        // Send the request via the delete route.
        $request = ['selected_products' => '10'];
        $response = $this->followingRedirects()->delete('/admin/products/delete', $request);
        $response->assertStatus(200);

        // Ensure it was deleted.
        $deleted_product = DB::table('products')->where('id', '10')->get();
        $this->assertEmpty($deleted_product);
    }

    /**
     * Test that multiple products can be deleted at once.
     */
    public function test_delete_multiple_products(): void {
        // Products to be deleted.
        DB::table('products')->insert([
            'id'          => '10',
            'item_name'   => 'TargetOne',
            'plu_cd'      => '594539430',
            'item_price'  => '200',
            'item_desc'   => 'Delete me!',
            'item_img'    => 'placeholder.png',
            'created_at'  => '2023-12-01 12:00:00',
            'updated_at'  => '2023-12-01 12:00:00',
            'category_id' => '5',
        ]);

        DB::table('products')->insert([
            'id'          => '11',
            'item_name'   => 'TargetTwo',
            'plu_cd'      => '32040594',
            'item_price'  => '200',
            'item_desc'   => 'No, delete me!',
            'item_img'    => 'placeholder.png',
            'created_at'  => '2023-12-01 12:00:00',
            'updated_at'  => '2023-12-01 12:00:00',
            'category_id' => '5',
        ]);

        // Ensure they were saved.
        $saved_product = DB::table('products')->where('id', '10')->get();
        $this->assertNotEmpty($saved_product);

        $saved_product = DB::table('products')->where('id', '11')->get();
        $this->assertNotEmpty($saved_product);

        // Send the request via the delete route.
        $request = ['selected_products' => '10,11'];
        $response = $this->followingRedirects()->delete('/admin/products/delete', $request);
        $response->assertStatus(200);

        // Ensure they were deleted.
        $deleted_product = DB::table('products')->where('id', '10')->get();
        $this->assertEmpty($deleted_product);

        $deleted_product = DB::table('products')->where('id', '11')->get();
        $this->assertEmpty($deleted_product);
    }

    /**
     * Test that a given product's category is flushed when orphaned.
     */
    public function test_flush_category(): void {
        $request = ['item_name'  => 'Target',
                    'plu_cd'     => '49504319234302',
                    'item_price' => '444',
                    'item_desc'  => 'Delete me!',
                    'category'   => 'DeleteMe'];

        // Add request via add route to ensure new category is made.
        $this->followingRedirects()->post('/admin/products/add', $request)->assertStatus(200);

        // Ensure the category exists.
        $new_category = DB::table('categories')->where('category_name', 'DeleteMe')->get();
        $this->assertNotEmpty($new_category);

        // Find and delete the target product, making DeleteMe and orphan category.
        $delete_target_id = DB::table('products')->select('id')->where('item_name', 'Target')->get()->value('id');
        $this->assertNotEmpty($delete_target_id);

        $request = ['selected_products' => $delete_target_id];
        $response = $this->followingRedirects()->delete('/admin/products/delete', $request);
        $response->assertStatus(200);

        $deleted_target= DB::table('products')->where('item_name', 'Target')->get();
        $this->assertEmpty($deleted_target);

        // Ensure the category was flushed.
        $flushed_category = DB::table('categories')->where('category_name', 'DeleteMe')->get();
        $this->assertEmpty($flushed_category);
    }
}
