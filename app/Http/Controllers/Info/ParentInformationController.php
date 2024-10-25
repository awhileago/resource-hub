<?php

namespace App\Http\Controllers\Info;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Info\ParentInformationRequest;
use App\Http\Resources\Info\ParentInformationResource;
use App\Models\Info\ParentInformation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class ParentInformationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $data = QueryBuilder::for(ParentInformation::class)
            ->when(isset($request->user_id), function ($query) use ($request) {
                $query->whereUserId($request->user_id);
            })
            ->with(['monthlyIncome']);

        return ['data' => $data->first()];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ParentInformationRequest $request)
    {
        $data = ParentInformation::updateOrCreate(['user_id' => auth()->id()], $request->validated());

        return $this->sendResponse(new ParentInformationResource($data), 'Parent information successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ParentInformation $parentInformation)
    {
        $query = ParentInformation::where('id', $parentInformation->id);
        $data = QueryBuilder::for($query)
            ->first();

        return new ParentInformationResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ParentInformationRequest $request, ParentInformation $parentInformation)
    {
        $data = $parentInformation->update($request->validated());
        return $this->sendResponse($request->validated(), 'Parent information successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
