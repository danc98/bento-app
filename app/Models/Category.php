<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $timestamps = false;
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'category_name',
    ];

    /**
     * Get the products for the category.
     */
    public function products(): HasMany {
        return $this->hasMany(Product::class);
    }
}
