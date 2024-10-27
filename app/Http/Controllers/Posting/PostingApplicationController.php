<?php

namespace App\Http\Controllers\Posting;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posting\PostingApplicationRequest;
use App\Http\Resources\Posting\PostingApplicationResource;
use App\Models\Posting\PostingApplication;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PostingApplicationController extends  BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(PostingApplication::class)
            ->when(isset($request->lib_posting_category_id), function ($query) use ($request) {
                $query->whereHas('posting', function ($q) use ($request) {
                    $q->where('lib_posting_category_id', $request->lib_posting_category_id);
                })
                ->with('posting');
                /* $query->with(['posting' => function($q) use ($request){
                    $q->where('lib_posting_category_id', $request->lib_posting_category_id);
                }]); */
            })
            ->when(isset($request->show_list), function ($query) use ($request) {
                $query->where('posting_id', $request->posting_id)
                ->with(['user', 'user.school', 'user.parents', 'user.academicProgram', 'user.yearLevel', 'user.parents.monthlyIncome', 'user.userEducation', 'user.userEducation.educationLevel', 'user.userEducation.academicProgram', 'user.employment', 'user.reference', 'user.skill']);
            })
            ->when(isset($request->application_status) && $request->application_status === 'pending', function ($q) use ($request) {
                $q->whereNull('is_approved');
            })
            ->when(isset($request->application_status) && $request->application_status === 'verified', function ($q) use ($request) {
                $q->where('is_approved', 1);
            })
            ->when(isset($request->application_status) && $request->application_status === 'rejected', function ($q) use ($request) {
                $q->where('is_approved', 0);
            })
            ->when(!auth()->user()->is_admin, function($query) use($request) {
                $query->whereUserId(auth()->id());
            })
            ->allowedIncludes(['updatedBy', 'posting', 'user', 'user.school'])
            ->defaultSort('date_applied')
            ->allowedSorts(['date_applied']);
        if ($perPage === 'all') {
            return PostingApplicationResource::collection($data->get());
        }

        return PostingApplicationResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostingApplicationRequest $request)
    {
        $data = PostingApplication::query()->updateOrCreate(['posting_id' => $request->posting_id, 'user_id' => auth()->id()], $request->validated());
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, PostingApplication $postingApplication)
    {
        $data = $postingApplication->update($request->all());
        return $this->sendResponse($data, 'Application successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
