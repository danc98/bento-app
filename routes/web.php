<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\LabelController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Category;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('homepage');
});

// Single product listing.
Route::get('/product/{id}', function (Request $request, string $id) {
    // If request came from the full index, flush the cache.
    if ($request->session()->get('_previous')['url'] == route('product.index')) {
        $request->session()->put('p_search', '');   // Search Bar
        $request->session()->put('f_category', ''); // Category Filter
    }
    // If request came from the search or category bar, preserve results.
    $prev_product = $request->session()->get('p_search') ?? '';
    $filter_category = $request->session()->get('f_category') ?? '';

    // Set return URL based on session data.
    if (!empty($prev_product)) {
        $return_url = route('product.search') . "?q=" . $prev_product;
    } else if (!empty($filter_category)) {
        $return_url = route('product.filter', $filter_category);
    } else {
        $return_url = route('product.index');
    }

    $product = app(ProductController::class)->retrieve($id);
    return view('product-listing')
    ->with('product', $product)
    ->with('return_url', $return_url);
})->name('product.show');

// Full product listing.
Route::get('/product', function () {
    return View::make('homepage')
        ->with('products', app(ProductController::class)->index())
        ->with('categories', Category::all());
})->name('product.index');

// Filtering products by category.
Route::get('/product/filter/{id}', function (Request $request, string $id) {
    // Save the filtered category for back button purposes.
    $request->session()->put('f_category', $id);
    // Flush search cache to avoid crossover.
    $request->session()->put('p_search', '');

    return View::make('homepage')
        ->with('products', app(ProductController::class)->filter($id))
        ->with('categories', Category::all());
})->name('product.filter');

// Searching for a product by name.
Route::get('/search', [ProductController::class, 'search'])->name('product.search');

// Route to product admin page.
Route::get('/admin/products', function () {
    return View::make('admin-products')
        ->with('products', app(ProductController::class)->indexAdmin());
})->name('admin.products');;

// Route to product add page.
Route::get('/admin/products/add', function () {
    return View::make('admin-add-product');
})->name('admin.add-product');

// Route to store a product.
Route::post('/admin/products/add', [ProductController::class, 'store'])->name('admin.store-product');

// Route to patch a product.
Route::patch('/admin/products/patch', [ProductController::class, 'patch'])->name('admin.patch-product');

// Route to delete a product.
Route::delete('/admin/products/delete', [ProductController::class, 'delete'])->name('admin.delete-product');

// Route to admin labels page.
Route::get('/admin/labels', function () {
    return View::make('admin-labels')
        ->with('labels', app(LabelController::class)->index());
})->name('admin.labels');

// Route to label add page.
Route::get('/admin/labels/add', function () {
    return View::make('admin-add-label')
        ->with('products', app(ProductController::class)->indexAdmin());
})->name('admin.add-label');

// Route to store a label.
Route::post('/admin/labels/add', [LabelController::class, 'store'])->name('admin.store-label');

// Route to delete a label.
Route::delete('/admin/labels/delete', [LabelController::class, 'delete'])->name('admin.delete-label');