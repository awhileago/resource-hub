<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibSchoolResource;
use App\Models\Library\LibSchool;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibSchoolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibSchool::class)
            ->defaultSort('desc')
            ->allowedSorts('desc');

        return LibSchoolResource::collection($query->get());
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
    public function show(LibSchool $school)
    {
        $query = LibSchool::where('id', $school->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibSchoolResource($data);
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
