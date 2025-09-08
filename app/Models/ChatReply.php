<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatReply extends Model
{
    use HasFactory;

    protected $table = 'chat_replies';
    
    protected $fillable = [
        'chat_id',
        'user_id',
        'content',
        'created_at',
        'attachment_url',
        'file_type',
        'file_size',
    ];

    protected $casts = [
        'created_at' => 'datetime'
    ];

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class, 'chat_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
} 