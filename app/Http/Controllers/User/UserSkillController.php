<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserSkillRequest;
use App\Http\Resources\User\UserSkillResource;
use App\Models\User\UserSkill;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserSkillController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(UserSkill::class)
            ->when(isset($request->user_id), function ($query) use ($request) {
                $query->whereUserId($request->user_id);
            })
            // ->with(['user'])
            ->orderBy('description');

        if ($perPage === 'all') {
            return UserSkillResource::collection($data->get());
        }

        return UserSkillResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserSkillRequest $request)
    {
        $data = UserSkill::query()->updateOrCreate(['user_id' => auth()->id(), 'description' => $request->description], $request->validated());
        return $this->sendResponse($data, 'User skill successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(UserSkill $userSkill)
    {
        $query = UserSkill::where('id', $userSkill->id);
        $data = QueryBuilder::for($query)
            ->with(['user'])
            ->first();

        return new UserSkillResource($data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserSkillRequest $request, UserSkill $userSkill)
    {
        $userSkill->update($request->validated());
        return $this->sendResponse($userSkill, 'User skill successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
