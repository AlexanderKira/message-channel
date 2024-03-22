<?php

namespace App\Http\Resources\Message;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'content' => $this->content,
            'type' => $this->type,
            'sender' => $this->sender,
            'recipient' => $this->recipient,
            'replies' => $this->replies,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
