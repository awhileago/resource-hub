<?php

namespace App\Http\Resources\Info;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ParentInformationResource extends JsonResource
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
            'fathers_name' => $this->fathers_name,
            'fathers_occupation' => $this->fathers_occupation,
            'fathers_company' => $this->fathers_company,
            'mothers_name' => $this->mothers_name,
            'mothers_occupation' => $this->mothers_occupation,
            'mothers_company' => $this->mothers_company,
            'average_monthly_income' => $this->average_monthly_income,
            'montly_income' => $this->whenLoaded('monthlyIncome'),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
