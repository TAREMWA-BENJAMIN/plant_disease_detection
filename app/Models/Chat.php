<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    use HasFactory;

    protected $table = 'chat';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'chat_topic',
        'content',
        'chat_creator_id',
        'chat_created_at',
        'attachment_url',
        'file_type',
        'file_size',
    ];

    protected $casts = [
        'chat_created_at' => 'datetime',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'chat_creator_id', 'id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(ChatReply::class, 'chat_id');
    }
} 