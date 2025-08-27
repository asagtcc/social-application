<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'slug'  => $this->slug,
            'name'  => $this->name,
            'posts_per_month' => $this->posts_per_month,
            'reels_per_month' => $this->reels_per_month,
            'stories_per_month' => $this->stories_per_month,
            'price' => $this->price,
            'organizations' => OrganizationResource::collection($this->whenLoaded('organizations')),
        ];
    }
}
