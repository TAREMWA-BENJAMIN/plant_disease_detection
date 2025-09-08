<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $fillable = [
        'title',
        'description',
        'content',
        'url',
        'image_url',
        'published_date',
        'source',
        'country',
        'language',
        'category',
    ];

    protected $casts = [
        'published_date' => 'datetime',
    ];
}
