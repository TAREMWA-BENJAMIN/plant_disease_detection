<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatReplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'created_at' => $this->created_at->toIso8601String(),
            'attachment_url' => $this->attachment_url ? asset('storage/' . $this->attachment_url) : null,
            'user' => new UserResource($this->user),
        ];
    }
}
