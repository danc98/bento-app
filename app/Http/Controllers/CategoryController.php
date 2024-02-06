<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Get the specified category.
     */
    public function retrieve(string $category_id) {
        $category = Category::findOrFail($category_id);

        $category_details = [
            'category_id'     => $category->id,
            'name'            => $category->category_name,
            'valid_datetime'  => $category->valid_datetime,
            'update_datetime' => $category->update_datetime,
        ];

        return $category_details;
    }

    /**
     * Attempt to get the specified category.
     * If category does not exist, create it.
     */
    public function retrieveOrMake(string $category_name) {
        if (empty($category_name)) { return ""; }
        $category = Category::firstOrNew(['category_name' => $category_name]);

        if (!$category->exists) {
            $category->valid_datetime = date("Y-m-d H:i:s");
            $category->update_datetime = date("Y-m-d H:i:s");

            $category->save();
        }

        return $category->id;
    }

    /**
     * Recieves list of active categories and uses it find
     * orphaned categories (categories without an existing product)
     * and deletes them.
     */
    public function flushCategories($active_categories) {
        // Pull all saved categories.
        $categories = array();
        foreach (Category::all() as $label) {
            $categories[] = $label->id;
        }

        // Determine inactive categories, and delete.
        $orphans = array_diff($categories, $active_categories);
        foreach ($orphans as $orphan) {
            $this->delete($orphan);
        }
    }

    /**
     * Deletes the specified category.
     */
    public function delete($category_id) {
        Category::where('id', $category_id)->delete();
    }
}
