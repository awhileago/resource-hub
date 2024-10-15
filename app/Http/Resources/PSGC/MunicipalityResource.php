<?php

namespace App\Http\Resources\PSGC;

use App\Models\PSGC\Province;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MunicipalityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $condition = $request->include != 'municipalities' && ! is_null($request->municipality);

        return [
            'code' => $this->psgc_10_digit_code,
            'name' => $this->name,
            'geo_level' => $this->geo_level,
            'income_class' => $this->income_class,
            'population' => $this->population,
            'barangays' => BarangayResource::collection($this->whenLoaded('barangays')),
            'province' => $this->when($condition, new ProvinceResource($this->geographic)),
            'region' => $this->when($condition, new RegionResource($this->geographic->region)),
        ];
    }
}
