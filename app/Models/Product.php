<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{ 
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'item_name',
        'plu_cd',
        'item_price',
        'item_desc',
        'item_img',
    ];

    /**
     * Get all labels associated with a product.
     */
    public function labels(): HasMany {
        return $this->hasMany(Label::class);
    }

    /**
     * Get the current stock.
     */
    public function stock() {
        return $this->labels()->where('pack_status', '1')->count();
    }

    /**
     * Get the product's category.
     */
    public function category() {
        $category = Category::findOr($this->category_id, function() { return ""; });
        if ($category != "") { $category = $category->category_name; }
        return $category;
    }
}
