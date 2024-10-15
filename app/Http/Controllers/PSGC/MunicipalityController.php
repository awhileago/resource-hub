<?php

namespace App\Http\Controllers\PSGC;

use App\Http\Controllers\Controller;
use App\Http\Resources\PSGC\MunicipalityResource;
use App\Models\PSGC\Municipality;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class MunicipalityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $municipalities = QueryBuilder::for(Municipality::class)->allowedIncludes('barangays');

        if ($perPage === 'all') {
            return MunicipalityResource::collection($municipalities->get());
        }

        return MunicipalityResource::collection($municipalities->paginate($perPage));
    }

    /**
     * Display the specified resource.
     */
    public function show(Municipality $municipality)
    {
        $query = Municipality::where('id', $municipality->id);

        $data = QueryBuilder::for($query)
            ->allowedIncludes('barangays')
            ->first();

        return new MunicipalityResource($data);
    }
}
