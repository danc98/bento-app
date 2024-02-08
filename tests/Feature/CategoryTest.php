<?php

namespace Tests\Feature;

use App\Http\Controllers\CategoryController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Test that index() returns categories in the proper format.
     */
    public function test_index_proper_format(): void
    {
        $categories = app(CategoryController::class)->index();
        $this->assertNotEmpty($categories);

        // Tests that all items are pulled with proper format.
        foreach ($categories as $category) {
            $this->assertArrayHasKey('category_id', $category, "category_id has not been set.");
            $this->assertNotEmpty($category['category_id'], "category_id is empty.");
        
            $this->assertArrayHasKey('name', $category, "name has not been set.");
            $this->assertNotEmpty($category['name'], "name is empty.");

            $this->assertArrayHasKey('created_at', $category, "created_at has not been set.");
            $this->assertNotEmpty($category['created_at'], "created_at is empty.");

            $this->assertArrayHasKey('updated_at', $category, "updated_at has not been set.");
            $this->assertNotEmpty($category['updated_at'], "updated_at is empty.");
        }
    }

    /**
     * Test that a given category is correctly pulled.
     */
    public function test_retrieve_category(): void {
        // Category to find.
        DB::table('categories')->insert([
            'id'              => '20',
            'category_name'   => 'Waldo',
            'created_at'  => '2023-12-01 12:00:00',
            'updated_at' => '2023-12-01 12:00:00',
        ]);

        $category_id = '20';
        $pulled_category = app(CategoryController::class)->retrieve($category_id);

        // Checking parameters.
        $this->assertNotEmpty($pulled_category);
        $this->assertEquals("20",                  $pulled_category['category_id'],     "label_id not correct.");
        $this->assertEquals("Waldo",               $pulled_category['name'],            "name not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_category['created_at'],  "created_at not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_category['updated_at'], "prod_datetime not correct.");
    }

    /**
     * Test that a non-existant category is created with retrieveOrMake.
     */
    public function test_retrieve_or_make_create_new_category(): void
    {
        // Make new category called "Newbie".
        $new_category_name = "Newbie";
        $new_category_id = app(CategoryController::class)->retrieveOrMake($new_category_name);

        // Checking parameters.
        $saved_category = app(CategoryController::class)->retrieve($new_category_id);
        $this->assertNotEmpty($saved_category);
        $this->assertEquals("Newbie", $saved_category["name"], "name is not correct.");

        // All other parameters are auto-set, so just check they are not empty.
        $this->assertNotEmpty($saved_category["category_id"]);
        $this->assertNotEmpty($saved_category["created_at"]);
        $this->assertNotEmpty($saved_category["updated_at"]);
    }

    /**
     * Test that a existing category is retrieved and not made with retrieveOrMake.
     */
    public function test_retrieve_or_make_find_existing_category(): void
    {
        // Pre-Existing test category.
        DB::table('categories')->insert([
            'id'              => '20',
            'category_name'   => 'Waldo',
            'created_at'      => '1995-12-01 12:00:00',
            'updated_at'      => '1995-12-01 12:00:00',
        ]);

        $search_category_name = "Waldo";
        $found_category_id = app(CategoryController::class)->retrieveOrMake($search_category_name);
        $this->assertEquals("20", $found_category_id);
        
        // Checking parameters.
        $found_category = app(CategoryController::class)->retrieve($found_category_id);
        $this->assertNotEmpty($found_category);
        $this->assertEquals("Waldo", $found_category["name"], "name is not correct.");

        // Since the two datetime items are newly set when a category is made, if they are
        // unchanged from above that means retrieveOrMake() didn't make a new category.
        $this->assertEquals("1995-12-01 12:00:00", $found_category["created_at"], "created_at not correct.");
        $this->assertEquals("1995-12-01 12:00:00", $found_category["updated_at"], "updated_at not correct.");
    }

    /**
     * Test that a category is properly deleted.
     */
    public function test_delete_category(): void
    {
        // Category to be deleted.
        DB::table('categories')->insert([
            'id'              => '20',
            'category_name'   => 'DeleteMe!',
            'created_at'      => '1985-12-01 12:00:00',
            'updated_at'      => '1985-12-01 12:00:00',
        ]);

        // Ensure category exists in DB.
        $target_category = DB::table('categories')->where('category_name', 'DeleteMe!')->get()[0];
        $this->assertNotEmpty($target_category);

        // Delete the category and ensure it is gone.
        app(CategoryController::class)->delete($target_category->id);
        $deleted_category = DB::table('categories')->where('category_name', 'DeleteMe!')->get();
        $this->assertEmpty($deleted_category);
    }
}
