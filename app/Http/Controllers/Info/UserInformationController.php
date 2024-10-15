<?php

namespace App\Http\Controllers\Info;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Info\UserRequest;
use App\Http\Resources\Info\UserInformationResource;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserInformationController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $columns = ['last_name', 'first_name', 'middle_name'];
        $user = QueryBuilder::for(User::class)
            ->when(isset($request->search), function ($q) use ($request, $columns) {
                $q->orSearch($columns, 'LIKE', $request->search);
            })
            ->with(['parents'])
            ->allowedIncludes('suffixName')
            ->defaultSort('last_name', 'first_name', 'middle_name', 'birthdate')
            ->allowedSorts(['last_name', 'first_name', 'middle_name', 'birthdate']);
        if ($perPage === 'all') {
            return UserInformationResource::collection($user->get());
        }

        return UserInformationResource::collection($user->paginate($perPage)->withQueryString());
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
    public function show(User $userInformation)
    {
        $query = User::where('id', $userInformation->id);
        $data = QueryBuilder::for($query)
            ->with(['suffixName', 'parents'])
            ->first();

        return new UserInformationResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, User $userInformation)
    {
        $data = $userInformation->update($request->validated());
        return $this->sendResponse($request->validated(), 'User information successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
