<?php

namespace App\Http\Controllers\PSGC;

use App\Http\Controllers\Controller;
use App\Http\Resources\PSGC\ProvinceResource;
use App\Models\PSGC\Province;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $provinces = QueryBuilder::for(Province::class)->allowedIncludes('municipalities');

        if ($perPage === 'all') {
            return ProvinceResource::collection($provinces->get());
        }

        return ProvinceResource::collection($provinces->paginate($perPage));
    }

    /**
     * Display the specified resource.
     */
    public function show(Province $province)
    {
        $query = Province::where('id', $province->id);

        $data = QueryBuilder::for($query)
            ->allowedIncludes('municipalities')
            ->first();

        return new ProvinceResource($data);
    }
}
