<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserReferenceRequest;
use App\Http\Resources\User\UserReferenceResource;
use App\Models\User\UserReference;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserReferenceController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(UserReference::class)
            ->when(isset($request->user_id), function ($query) use ($request) {
                $query->whereUserId($request->user_id);
            })
            // ->with(['user'])
            ->orderBy('full_name');

        if ($perPage === 'all') {
            return UserReferenceResource::collection($data->get());
        }

        return UserReferenceResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserReferenceRequest $request)
    {
        $data = UserReference::query()->updateOrCreate(['id' => $request->id, 'user_id' => auth()->id(), 'full_name' => $request->full_name], $request->validated());
        return $this->sendResponse($data, 'User reference successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserReference $userReference)
    {
        $query = UserReference::where('id', $userReference->id);
        $data = QueryBuilder::for($query)
            ->with(['user'])
            ->first();

        return new UserReferenceResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserReferenceRequest $request, UserReference $userReference)
    {
        $userReference->update($request->validated());
        return $this->sendResponse($userReference, 'User reference successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
