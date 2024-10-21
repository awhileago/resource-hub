<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibPostingCategoryResource;
use App\Models\Library\LibPostingCategory;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibPostingCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibPostingCategory::class)
            ->defaultSort('id')
            ->allowedSorts('id');

        return LibPostingCategoryResource::collection($query->get());
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
    public function show(LibPostingCategory $category)
    {
        $query = LibPostingCategory::where('id', $category->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibPostingCategoryResource($data);
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
