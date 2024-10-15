<?php

namespace App\Http\Controllers\PSGC;

use App\Http\Controllers\Controller;
use App\Http\Resources\PSGC\BarangayResource;
use App\Models\PSGC\Barangay;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class BarangayController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $barangays = QueryBuilder::for(Barangay::class);

        if ($perPage === 'all') {
            return BarangayResource::collection($barangays->get());
        }

        return BarangayResource::collection($barangays->paginate($perPage));
    }

    /**
     * Display the specified resource.
     */
    public function show(Barangay $barangay)
    {
        $query = Barangay::where('id', $barangay->id);

        $data = QueryBuilder::for($query)
            ->first();

        return new BarangayResource($data);
    }
}
