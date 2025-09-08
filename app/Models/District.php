<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'region_id',
        'flag',
    ];

    /**
     * Get the users that belong to this district.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the country that this district belongs to.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
} 