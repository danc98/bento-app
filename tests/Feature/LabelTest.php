<?php

namespace Tests\Feature;

use App\Http\Controllers\LabelController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class LabelTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    /**
     * Test that active() doesn't return in inactive products.
     */
    public function test_active_returns_only_active_ids(): void
    {
        $product_ids = app(LabelController::class)->active();

        // Get the status of all labels associated with product_id,
        // and ensures there is at least one active label.
        foreach ($product_ids as $product_id) {
            $all_statuses = DB::table('labels')->where('product_id', $product_id)->get('pack_status');
            $this->assertTrue($all_statuses->contains('pack_status', '1'));
        }
    }

    /**
     * Test that index() returns labels in the proper format.
     */
    public function test_index_proper_format(): void
    {
        $labels = app(LabelController::class)->index();
        $this->assertNotEmpty($labels);

        // Tests that all items are pulled with proper format.
        foreach ($labels as $label) {
            $this->assertArrayHasKey('label_id', $label, "label_id has not been set.");
            $this->assertNotEmpty($label['label_id'], "label_id is empty.");

            $this->assertArrayHasKey('product_id', $label, "product_id has not been set.");
            $this->assertNotEmpty($label['product_id'], "product_id is empty.");

            $this->assertArrayHasKey('prod_datetime', $label, "prod_datetime has not been set.");
            $this->assertNotEmpty($label['prod_datetime'], "prod_datetime is empty.");

            $this->assertArrayHasKey('valid_datetime', $label, "valid_datetime has not been set.");
            $this->assertNotEmpty($label['valid_datetime'], "valid_datetime is empty.");

            $this->assertArrayHasKey('update_datetime', $label, "update_datetime has not been set.");
            $this->assertNotEmpty($label['update_datetime'], "update_datetime is empty.");

            $this->assertArrayHasKey('pack_status', $label, "pack_status has not been set.");
            $this->assertNotEmpty($label['pack_status'], "pack_status is empty.");
        }
    }

    /**
     * Test that index() returns inactive labels.
     */
    public function test_index_returns_inactive_labels(): void
    {
        $labels = app(LabelController::class)->index();
        $this->assertNotEmpty($labels);

        // Only labels of pack_status = 1: onSale are active.
        $inactive_label = NULL;
        foreach ($labels as $label) {
            if ($label['pack_status'] != "1: onSale") {
                $inactive_label = $label;
            }
        }
        $this->assertNotNull($inactive_label);
    }

    /**
     * Test that a given active label_id is correctly pulled.
     */
    public function test_retrieve_label(): void {
        // Label to find.
        DB::table('labels')->insert([
            'id'              => '20',
            'product_id'      => '7',
            'prod_datetime'   => '2023-12-01 12:00:00',
            'valid_datetime'  => '2023-12-01 12:00:00',
            'update_datetime' => '2023-12-01 12:00:00',
            'pack_status'     => '0',
        ]);

        $label_id = '20';
        $pulled_label = app(LabelController::class)->retrieve($label_id);

        // Checking parameters.
        $this->assertNotEmpty($pulled_label);
        $this->assertEquals("20",                  $pulled_label['label_id'],        "label_id not correct.");
        $this->assertEquals("7",                   $pulled_label['product_id'],      "product_id not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_label['prod_datetime'],   "prod_datetime not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_label['valid_datetime'],  "valid_datetime not correct.");
        $this->assertEquals("2023-12-01 12:00:00", $pulled_label['update_datetime'], "update_datetime not correct.");
        $this->assertEquals("0: printed",          $pulled_label['pack_status'],     "pack_status not correct.");
    }

    /**
     * Test that store() properly stores a new label.
     */
    public function test_store_label(): void
    {
        $request = ['product_id'      => '7',
                    'valid_datetime'  => '1944-01-01 12:00:00',
                    'pack_status'     => '0'];

        // Send the request via the add route.
        $this->followingRedirects()->post('/admin/labels/add', $request)->assertStatus(200);

        // Label deliberately set to odd date to ensure record is pulled.
        $saved_label = DB::table('labels')->where('valid_datetime', '1944-01-01 12:00:00')->get();
        $this->assertNotEmpty($saved_label);

        // Checking parameters.
        $saved_label = $saved_label[0];
        $this->assertEquals("7",                   $saved_label->product_id,      "product_id not correct.");
        $this->assertEquals("1944-01-01 12:00:00", $saved_label->valid_datetime,  "valid_datetime not correct.");
        $this->assertEquals("0",                   $saved_label->pack_status,     "pack_status not correct.");

        // These parameters are set using execution time, so just check they are not empty.
        $this->assertNotEmpty($saved_label->prod_datetime);
        $this->assertNotEmpty($saved_label->update_datetime);
    }

    /**
     * Test that delete() properly deletes a label.
     */
    public function test_delete_label(): void
    {
        // Label to be deleted.
        DB::table('labels')->insert([
            'id'              => '20',
            'product_id'      => '7',
            'prod_datetime'   => '2024-01-01 12:00:00',
            'valid_datetime'  => '1945-01-01 12:00:00',
            'update_datetime' => '2024-01-01 12:00:00',
            'pack_status'     => '2',
        ]);

        // Label deliberately set to odd date to ensure record is pulled.
        $saved_label = DB::table('labels')->where('valid_datetime', '1945-01-01 12:00:00')->get();
        $this->assertNotEmpty($saved_label);

        // Send the request via the delete route.
        $request = ['selected_labels' => '20'];
        $response = $this->followingRedirects()->delete('/admin/labels/delete', $request);
        $response->assertStatus(200);

        // Ensure it was deleted.
        $deleted_label = DB::table('labels')->where('valid_datetime', '1945-01-01 12:00:00')->get();
        $this->assertEmpty($deleted_label);
    }

    /**
     * Test that delete() properly deletes multiple labels at once.
     */
    public function test_delete_multiple_labels(): void
    {
        // Labels to be deleted.
        DB::table('labels')->insert([
            'id'              => '20',
            'product_id'      => '7',
            'prod_datetime'   => '2024-01-01 12:00:00',
            'valid_datetime'  => '1935-01-01 12:00:00',
            'update_datetime' => '2024-01-01 12:00:00',
            'pack_status'     => '2',
        ]);

        DB::table('labels')->insert([
            'id'              => '21',
            'product_id'      => '7',
            'prod_datetime'   => '2024-01-01 12:00:00',
            'valid_datetime'  => '1936-01-01 12:00:00',
            'update_datetime' => '2024-01-01 12:00:00',
            'pack_status'     => '2',
        ]);

        // Labels deliberately set to odd date to ensure record is pulled.
        $saved_label = DB::table('labels')->where('valid_datetime', '1935-01-01 12:00:00')->get();
        $this->assertNotEmpty($saved_label);

        $saved_label = DB::table('labels')->where('valid_datetime', '1936-01-01 12:00:00')->get();
        $this->assertNotEmpty($saved_label);

        // Send the request via the delete route.
        $request = ['selected_labels' => '20,21'];
        $response = $this->followingRedirects()->delete('/admin/labels/delete', $request);
        $response->assertStatus(200);

        // Ensure they were deleted.
        $deleted_label = DB::table('labels')->where('valid_datetime', '1935-01-01 12:00:00')->get();
        $this->assertEmpty($deleted_label);

        $deleted_label = DB::table('labels')->where('valid_datetime', '1936-01-01 12:00:00')->get();
        $this->assertEmpty($deleted_label);
    }
}
