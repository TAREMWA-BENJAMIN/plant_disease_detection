<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $table = 'diseases';

    protected $fillable = [
        'disease_name',
        'description',
        'suggested_solution',
    ];

    // If your 'diseases' table also has 'created_at' and 'updated_at' columns,
    // you don't need to define $timestamps = true; as it's the default.
    // If not, you would set public $timestamps = false;

    // If a disease belongs to a scan, you would define a relationship like this:
    // public function scan(): BelongsTo
    // {
    //     return $this->belongsTo(Scan::class);
    // }
} 