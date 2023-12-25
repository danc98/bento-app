<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Label extends Model
{
    public $timestamps = false;
    /**
     * Return product associated with label.
     */
    public function product(): BelongsTo {
        return $this->belongsTo(Product::class);
    }
}
