<?php

namespace App\Http\Controllers\Posting;

use App\Http\Controllers\Controller;
use App\Http\Requests\Posting\PostingApplicationRequest;
use App\Http\Resources\Posting\PostingApplicationResource;
use App\Models\Posting\PostingApplication;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PostingApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(PostingApplication::class)
            ->allowedIncludes(['updatedBy', 'posting', 'user'])
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
