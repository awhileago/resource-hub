<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibYearLevelResource;
use App\Models\Library\LibYearLevel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibYearLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibYearLevel::class)
            ->defaultSort('id')
            ->allowedSorts('id');

        return LibYearLevelResource::collection($query->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(LibYearLevel $yearLevel)
    {
        $query = LibYearLevel::where('id', $yearLevel->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibYearLevelResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
