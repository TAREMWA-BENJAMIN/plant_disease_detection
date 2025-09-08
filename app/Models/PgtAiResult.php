<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PgtAiResult extends Model
{
    use HasFactory;

    protected $table = 'pgt_ai_results';

    protected $fillable = [
        'user_id',
        'plant_image',
        'plant_name',
        'disease_name',
        'disease_details',
        'suggested_solution',
        'prevention_tips'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that owns the result
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
} 