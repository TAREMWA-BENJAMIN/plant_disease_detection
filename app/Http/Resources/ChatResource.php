<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'title' => $this->chat_topic,
            'content' => $this->content,
            'created_at' => $this->chat_created_at->toIso8601String(),
            'creator' => new UserResource($this->whenLoaded('creator')),
            'replies' => ChatReplyResource::collection($this->when($this->relationLoaded('replies'), $this->replies)),
        ];
    }
}
