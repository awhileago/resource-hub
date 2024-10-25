<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEmploymentRequest;
use App\Http\Resources\User\UserEmploymentResource;
use App\Models\User\UserEmployment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserEmploymentController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(UserEmployment::class)
            ->when(isset($request->user_id), function ($query) use ($request) {
                $query->whereUserId($request->user_id);
            })
            ->with(['user'])
            ->orderByRaw('ISNULL(end_date) DESC, end_date DESC');

        if ($perPage === 'all') {
            return UserEmploymentResource::collection($data->get());
        }

        return UserEmploymentResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserEmploymentRequest $request)
    {
        $startDate = Carbon::createFromFormat('Y-m', $request->start_date)->startOfMonth()->format('Y-m-d');

        $data = UserEmployment::query()->updateOrCreate(['user_id' => auth()->id(), 'employer_name' => $request->employer_name, 'start_date' => $startDate], $request->validated());
        return $this->sendResponse($data, 'User employment successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserEmployment $userEmployment)
    {
        $query = UserEmployment::where('id', $userEmployment->id);
        $data = QueryBuilder::for($query)
            ->with(['user'])
            ->first();

        return new UserEmploymentResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserEmploymentRequest $request, UserEmployment $userEmployment)
    {
        $userEmployment->update($request->validated());
        return $this->sendResponse($userEmployment, 'User employment successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
