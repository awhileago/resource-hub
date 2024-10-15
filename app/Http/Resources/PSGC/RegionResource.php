<?php

namespace App\Http\Resources\PSGC;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'code' => $this->psgc_10_digit_code,
            'name' => $this->name,
            'population' => $this->population,
            'provinces' => ProvinceResource::collection($this->whenLoaded('provinces')),
        ];
    }
}
