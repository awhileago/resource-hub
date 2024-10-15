<?php

namespace App\Http\Resources\PSGC;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProvinceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $condition = $request->include != 'provinces' && ! is_null($request->province) && is_null($request->municipality);

        return [
            'code' => $this->psgc_10_digit_code,
            'name' => $this->name,
            'income_class' => $this->income_class,
            'population' => $this->population,
            'region' => $this->when($condition, new RegionResource($this->region)),
            'municipalities' => MunicipalityResource::collection($this->whenLoaded('municipalities')),
        ];
    }
}
