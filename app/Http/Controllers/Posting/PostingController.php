<?php

namespace App\Http\Controllers\Posting;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posting\PostingRequest;
use App\Http\Resources\Posting\PostingResource;
use App\Models\Posting\Posting;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PostingController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $columns = ['title', 'description'];
        $data = QueryBuilder::for(Posting::class)
            ->when(isset($request->search), function ($q) use ($request, $columns) {
                $q->orSearch($columns, 'LIKE', $request->search);
            })
            ->allowedIncludes(['category', 'barangay', 'user', 'applicants'])
            ->defaultSort('date_published', 'title')
            ->allowedSorts(['date_published', 'title', 'date_end']);
        if ($perPage === 'all') {
            return PostingResource::collection($data->get());
        }

        return PostingResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostingRequest $request)
    {
        $data = Posting::query()->updateOrCreate(['date_published' => $request->date_published, 'title' => $request->title], $request->validated());
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
