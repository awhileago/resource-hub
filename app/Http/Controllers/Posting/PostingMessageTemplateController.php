<?php

namespace App\Http\Controllers\Posting;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posting\PostingMessageTemplateRequest;
use App\Http\Resources\Posting\PostingMessageTemplateResource;
use App\Models\Posting\PostingMessageTemplate;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class PostingMessageTemplateController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page ?? self::ITEMS_PER_PAGE;

        $data = QueryBuilder::for(PostingMessageTemplate::class)
            ->when(isset($request->posting_id), function ($query) use ($request) {
                $query->where('posting_id', $request->posting_id);
            })
            ->when(isset($request->is_approved), function ($query) use ($request) {
                $query->where('is_approved', $request->is_approved);
            })
            ->with(['user', 'posting']);

        if ($perPage === 'all') {
            return PostingMessageTemplateResource::collection($data->get());
        }

        return PostingMessageTemplateResource::collection($data->paginate($perPage)->withQueryString());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PostingMessageTemplateRequest $request)
    {
        $data = PostingMessageTemplate::query()->updateOrCreate(['posting_id' => $request->posting_id], $request->validated());
        return $this->sendResponse($data, 'Posting message template successfully created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(PostingMessageTemplate $messageTemplate)
    {
        $query = PostingMessageTemplate::where('id', $messageTemplate->id);
        $data = QueryBuilder::for($query)
            ->with(['user', 'posting'])
            ->first();

        return new PostingMessageTemplateResource($data);
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
