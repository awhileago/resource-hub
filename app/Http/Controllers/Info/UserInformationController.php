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
            ->when(isset($request->is_verified) && $request->is_verified === 'pending', function ($q) use ($request) {
                $q->whereNull('user_verified');
            })
            ->when(isset($request->is_verified) && $request->is_verified === 'verified', function ($q) use ($request) {
                $q->where('user_verified', 1);
            })
            ->when(isset($request->is_verified) && $request->is_verified === 'rejected', function ($q) use ($request) {
                $q->where('user_verified', 0);
            })

            ->when(isset($request->active_status) && $request->active_status === 'active', function ($q) use ($request) {
                $q->where('is_active', 1);
            })
            ->when(isset($request->active_status) && $request->active_status === 'inactive', function ($q) use ($request) {
                $q->where('is_active', 0);
            })

            ->when(isset($request->user_info), function ($q) use ($request, $columns) {
                // $q->where('id', auth()->id())
                $q->with(['parents', 'parents.monthlyIncome', 'userEducation', 'userEducation.educationLevel', 'userEducation.academicProgram', 'employment', 'reference', 'skill']);
            })
            ->with(['school', 'academicProgram', 'yearLevel'])
            ->allowedIncludes('suffixName')
            ->defaultSort(['last_name', 'first_name', 'middle_name', 'birthdate'])
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
            ->with(['suffixName', 'parents', 'school', 'academicProgram', 'yearLevel', 'education', 'employment', 'reference', 'skill'])
            ->first();

        return new UserInformationResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $userInformation)
    {
        $userInformation->update($request->all());
        return $this->sendResponse($userInformation, 'User information successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
