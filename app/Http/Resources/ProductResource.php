<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'vendor' => $this->vendor->name,
            'ratings' => array_map(function ($rating) {
                return [
                    'name' => $rating['user_name'],
                    'text' => $rating['text'],
                    'rating' => $rating['stars'],
                ];
            }, $this->ratings->toArray()),
        ];
    }
}
