<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'user_id'  => $this->user_id,
            'social_account_id' => $this->social_account_id,
            'type' => $this->type,
            'content' => $this->content,
            'external_post_id' => $this->external_post_id,
            'status' => $this->status,
            'published_at' => $this->published_at,
        ];
    }
}
