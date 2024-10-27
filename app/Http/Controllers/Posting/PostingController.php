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
            ->when(isset($request->id), function ($q) use ($request, $columns) {
                $q->whereId($request->id);
            })
            ->when(!auth()->user()->is_admin, function($query) use($request) {
                $query->with(['applicants' => function($q) {
                    $q->whereUserId(auth()->id());
                }]);

                if(isset($request->lng) && isset($request->lat) && isset($request->radius)) {
                    $query->whereRaw("ST_Distance_Sphere(coordinates, ST_GeomFromText(?)) <= ?", [
                        "POINT($request->lng $request->lat)",
                        $request->radius
                    ]);
                }

                if(auth()->user()->scholar_flag) {
                    $query->where('no_scholar_flag', 0);
                }

                if(auth()->user()->shiftee_flag) {
                    $query->where('no_shiftee_flag', 0);
                }

                if(auth()->user()->irregular_flag) {
                    $query->where('no_irregular_flag', 0);
                }

                if (auth()->user()->parents()->where('ofw_flag', 1)->exists()) {
                    $query->where('no_ofw_flag', 0);
                }
            })
            /* ->when(!auth()->user()->is_admin, function($query) use($request) {
                $query->with(['applicants']);
            }) */
            ->when(isset($request->lib_posting_category_id), function ($q) use ($request) {
                $q->where('lib_posting_category_id', $request->lib_posting_category_id);
            })
            ->when(isset($request->is_published), function ($q) use ($request) {
                if($request->is_published == 'published') {
                    $q->whereNotNull('date_published');
                } else {
                    $q->whereNull('date_published');
                };
            })
            ->when(isset($request->start_date), function ($q) use ($request) {
                $q->where('date_published', '>=', $request->start_date);
            })
            ->when(isset($request->end_date), function ($q) use ($request) {
                $q->where('date_published', '<=', $request->end_date);
            })
            ->allowedIncludes(['category', 'barangay', 'user', 'applicants'])
            ->defaultSort(['date_published', 'title'])
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
        $data = Posting::query()->updateOrCreate(['id' => $request->id], $request->validated());
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
