<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibSuffixNameResource;
use App\Models\Library\LibSuffixName;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibSuffixNameController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibSuffixName::class)
            ->defaultSort('sequence')
            ->allowedSorts('sequence');

        return LibSuffixNameResource::collection($query->get());
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
    public function show(LibSuffixName $suffixName)
    {
        $query = LibSuffixName::where('code', $suffixName->code);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibSuffixNameResource($data);
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
