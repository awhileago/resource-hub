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
            'date_published' => isset($this->date_published) ? $this->date_published->format('Y-m-d') : null,
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
            'applicants' => PostingApplicationResource::collection($this->whenLoaded('applicants')),
            'no_scholar_flag' => $this->no_scholar_flag,
            'no_ofw_flag' => $this->no_ofw_flag,
            'no_shiftee_flag' => $this->no_shiftee_flag,
            'no_irregular_flag' => $this->no_irregular_flag,
            'posting_applications_count' => $this->applicants_count,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
