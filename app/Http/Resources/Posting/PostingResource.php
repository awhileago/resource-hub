<?php

namespace App\Http\Resources\Posting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostingResource extends JsonResource
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
            'user_id' => $this->when(! $this->relationLoaded('user'), $this->user_id),
            'user' => $this->whenLoaded('user'),
            'date_published' => $this->date_published->format('Y-m-d'),
            'date_end' => $this->date_end->format('Y-m-d'),
            'lib_posting_category_id' => $this->when(! $this->relationLoaded('category'), $this->lib_posting_category_id),
            'posting_category' => $this->whenLoaded('category'),
            'title' => $this->title,
            'description' => $this->description,
            'slot' => $this->slot,
            'address' => $this->address,
            'barangay_code' => $this->when(! $this->relationLoaded('barangay'), $this->barangay_code),
            'barangay' => $this->whenLoaded('barangay'),
            'latitude' => $this->coordinates->latitude,
            'longitude' => $this->coordinates->longitude,

        ];
    }
}
