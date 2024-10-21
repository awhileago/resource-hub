<?php

namespace App\Http\Resources\Posting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostingApplicationResource extends JsonResource
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
            'posting_id' => $this->when(! $this->relationLoaded('posting'), $this->posting_id),
            'posting' => $this->whenLoaded('posting'),
            'is_save' => $this->is_save,
            'is_applied' => $this->is_applied,
            'date_applied' => $this->date_applied->format('Y-m-d'),
            'is_approved' => $this->is_approved,
            'status_date' => $this->date_applied->format('Y-m-d'),
            'remarks' => $this->remarks,
            'updated_by_id' => $this->when(! $this->relationLoaded('updatedBy'), $this->updated_by_id),
            'updated_by' => $this->whenLoaded('updatedBy'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
