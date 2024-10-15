<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibAcademicProgramResource;
use App\Models\Library\LibAcademicProgram;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibAcademicProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibAcademicProgram::class)
            ->defaultSort('desc')
            ->allowedSorts('desc');

        return LibAcademicProgramResource::collection($query->get());
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
    public function show(LibAcademicProgram $program)
    {
        $query = LibAcademicProgram::where('id', $program->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibAcademicProgramResource($data);
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
