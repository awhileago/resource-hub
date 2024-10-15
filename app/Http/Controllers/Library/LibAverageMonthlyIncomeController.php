<?php

namespace App\Http\Controllers\Library;

use App\Http\Controllers\Controller;
use App\Http\Resources\Library\LibAverageMonthlyIncomeResource;
use App\Models\Library\LibAverageMonthlyIncome;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class LibAverageMonthlyIncomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = QueryBuilder::for(LibAverageMonthlyIncome::class)
            ->defaultSort('id')
            ->allowedSorts('id');

        return LibAverageMonthlyIncomeResource::collection($query->get());
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
    public function show(LibAverageMonthlyIncome $monthlyIncome)
    {
        $query = LibAverageMonthlyIncome::where('id', $monthlyIncome->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new LibAverageMonthlyIncomeResource($data);
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
