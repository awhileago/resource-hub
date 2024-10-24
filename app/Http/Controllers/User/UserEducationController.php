<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserEducationRequest;
use App\Http\Resources\User\UserEducationResource;
use App\Models\User\UserEducation;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class UserEducationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(UserEducation::class)
            ->when(isset($request->user_id), function ($query) use ($request) {
                $query->whereUserId($request->user_id);
            })
            ->with(['educationLevel', 'academicProgram'])
            ->orderBy('end_year', 'DESC');

        if ($perPage === 'all') {
            return UserEducationResource::collection($data->get());
        }

        return UserEducationResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserEducationRequest $request)
    {
        $data = UserEducation::query()->updateOrCreate(['id' => $request->id], $request->validated());
        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
