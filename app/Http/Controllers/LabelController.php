<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Redirect;

class LabelController extends Controller
{      
    /**
     * Return distinct product_ids associated with active labels.
     */
    public function active()
    {
        // Get all active product_ids associated with active labels.
        $active_labels = Label::distinct('product_id')->where('pack_status', '1')->get('product_id');

        $active_ids = [];

        // Pull out just the product_ids.
        foreach ($active_labels as $active_label) {
            $active_ids[] = $active_label["product_id"];
        }

        return $active_ids;
    }

    /**
     * Return all labels, even unactive ones.
     * Note: For admin panel use only.
     */
    public function index() {
        $labels = array();

        foreach (Label::all() as $label) {
            $labels[] = $this->formatLabel($label);
        }

        return $labels;
    }

    /**
     * Retrieve a label using a given label_id.
     */
    public function retrieve(string $label_id) {
        $label = Label::findOrFail($label_id);
        return $this->formatLabel($label);
    }

    /**
     * Adds a new label to the database.
     */
    public function store(Request $request): RedirectResponse {
        // As everything is either a dropdown or a set input field, input validation
        // shouldn't be necessary, but if a column is added to the labels that would
        // necessitate it, the validateLabel function can be filled out.

        // $result = $this->validateLabel($request);

        // if (count($result['errors']) === 0) {
        //     $label_info = $result['label_info'];

        //     $label = new Label;

        //     $label->product_id      = $label_info['product_id'];
        //     $label->valid_datetime  = $label_info['valid_datetime'];
        //     $label->pack_status     = $label_info['pack_status'];

        //     $label->save();
        // } else {
        //     return Redirect::route('admin.add-label')->withErrors($result['errors']);
        // }
        $label = new Label;

        $valid_datetime = new \DateTime($request['valid_datetime']);

        $label->product_id      = $request['product_id'];
        $label->prod_datetime   = date("Y-m-d H:i:s");
        $label->valid_datetime  = $valid_datetime->format("Y-m-d H:i:s");
        $label->update_datetime = date("Y-m-d H:i:s");
        $label->pack_status     = $request['pack_status'];

        $label->save();

        return Redirect::route('admin.labels');
    }

    /**
     * Deletes all labels given in the request.
     */
    public function delete(Request $request): RedirectResponse {
        $labels = array_filter(explode(",", $request["selected_labels"]));
        if (count($labels) != 0) {
            // Deletes all labels in array.
            Label::destroy($labels);
        }

        return Redirect::route('admin.labels');
    }

    /**
     * Converts pack_status to a more informative format.
     */
    private function convertPackStatus($pack_status) {
        switch ($pack_status) {
            case "0":
                $pack_status = "0: printed";
                break;
            case "1":
                $pack_status = "1: onSale";
                break;
            case "2":
                $pack_status = "2: processed";
                break;
            default:
                $pack_status = "unknown";
        }
        return $pack_status;
    }

    /**
     * Convert a label into an easier to use format.
     */
    private function formatLabel($label) {
        $formatted_label = array();

        // Convert product_id and pack_status into readable formats.
        $attached_product_name = $label->product->item_name;
        $pack_status = $this->convertPackStatus($label->pack_status);

        $formatted_label = [
            'label_id'        => $label->id,
            'product'         => $attached_product_name,
            'prod_datetime'   => $label->prod_datetime,
            'valid_datetime'  => $label->valid_datetime,
            'update_datetime' => $label->update_datetime,
            'pack_status'     => $pack_status,
        ];
        return $formatted_label;
    }

    /**
     * Private input validation function for adding a label to the database.
     */
    private function validateLabel(Request $request) {
        $label_info = array();
        $errors = array();

        // Valid Datetime
        $valid_datetime = $request['valid_datetime'];
        $test_arr = explode('-', $valid_datetime);

        if ($valid_datetime == "") {
            $errors['valid_datetime'] = "Please enter when the label will be valid.";
        } else if (!checkdate($test_arr[1], $test_arr[2], $test_arr[0])) {
            $errors['valid_datetime'] = "Please enter a valid date.";
        }

        $return_array = array();
        $return_array['errors'] = $errors;
        $return_array['label_info'] = $label_info;

        return $return_array;
    }
}
