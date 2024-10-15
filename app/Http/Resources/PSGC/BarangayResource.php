<?php

namespace App\Http\Resources\PSGC;

use App\Models\PSGC\Municipality;
use App\Models\PSGC\Province;
use App\Models\PSGC\Region;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BarangayResource extends JsonResource
{
    protected $level1Geographics = [
        Municipality::class => [
            'resource' => MunicipalityResource::class,
            'index' => 'municipality',
        ]
    ];

    protected $level2Geographics = [
        Region::class => [
            'resource' => RegionResource::class,
            'index' => 'region',
        ],
        Province::class => [
            'resource' => ProvinceResource::class,
            'index' => 'province',
        ]
    ];

    protected $condition = null;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        $this->condition = $request->include != 'barangays' && ! is_null($request->barangay) || $request->location == 'show';

        [$level1, $level1Resource, $level1Index] = $this->level1();
        [$level2, $level2Resource, $level2Index] = $this->level2($level1);
        [$level3, $level3Value, $level3Index] = $this->level3($level2);

        $region = isset($level3) ? $level3->region : (get_class($level2) != Region::class ? $level2->region : $level2);

        return array_filter([
            'code' => $this->psgc_10_digit_code,
            'name' => $this->name,
            'urban_rural' => $this->urban_rural,
            'population' => $this->population,
            $level1Index => $this->when($this->condition, new $level1Resource($level1)),
            $level2Index => $this->when($this->condition, new $level2Resource($level2)),
            $level3Index => isset($level3Value) ? $level3Value : '',
            'region' => $this->when($this->condition, new RegionResource($region)),
        ]);
    }

    private function level1()
    {
        $level1 = $this->geographic;
        $level1Geographic = $this->level1Geographics[get_class($level1)];
        $level1Resource = $level1Geographic['resource'];
        $level1Index = $level1Geographic['index'];

        return [$level1, $level1Resource, $level1Index];
    }

    private function level2($level1)
    {
        $level2Geographic = $this->level2Geographics[get_class($level1->geographic)];
        $level2 = $level1->geographic;
        $level2Resource = $level2Geographic['resource'];
        $level2Index = $level2Geographic['index'];

        return [$level2, $level2Resource, $level2Index];
    }

    private function level3($level2)
    {
        $level3Index = '';
        if (isset($level3Geographic)) {
            $level3Resource = $level3Geographic['resource'];
            $level3Index = $level3Geographic['index'];

            $level3Value = $this->when($this->condition && isset($level3), new $level3Resource($level2->geographic));
        }

        return [
            isset($level3) ? $level3 : null,
            $level3Value ?? null,
            $level3Index ?? null,
        ];
    }
}
