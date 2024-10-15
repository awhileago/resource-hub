<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibEducationLevelResource;
use App\Models\Library\LibEducationLevel;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibEducationLevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibEducationLevel::class)
            ->defaultSort('id')
            ->allowedSorts('id');

        return LibEducationLevelResource::collection($query->get());
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
    public function show(LibEducationLevel $educationLevel)
    {
        $query = LibEducationLevel::where('id', $educationLevel->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibEducationLevelResource($data);
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
