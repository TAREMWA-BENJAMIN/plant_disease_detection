<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $fillable = [
        'commodity',
        'unit',
        'retail_price',
        'wholesale_price',
        'difference',
        'change_percentage',
    ];
}
