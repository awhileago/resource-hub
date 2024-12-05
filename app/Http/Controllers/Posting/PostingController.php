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
            ->when(isset($request->id), function ($q) use ($request) {
                $q->whereId($request->id);
            })
            ->withCount('applicants') // Global applicant count for filtering
            ->withCount('approvedApplicants')
            ->when(isset($request->slots_filled) && $request->slots_filled == 'filled', function ($q) use ($request) {
                $q->whereRaw('slot <= (select count(*) from posting_applications where postings.id = posting_applications.posting_id AND is_approved = 1)');
            })
            ->when(isset($request->slots_filled) && $request->slots_filled == 'unfilled', function ($q) use ($request) {
                $q->whereRaw('slot > (select count(*) from posting_applications where postings.id = posting_applications.posting_id)');
            })
            ->when(!auth()->user()->is_admin, function($query) use($request) {
                // Filter to ensure only postings with available slots are shown to non-admins
                $query->whereRaw('slot > (select count(*) from posting_applications where postings.id = posting_applications.posting_id AND is_approved = 1)')
                    ->with(['applicants' => function($q) {
                        $q->whereUserId(auth()->id());
                    }]);

                // Additional non-admin filters based on location and user attributes
                if(isset($request->lng, $request->lat, $request->radius)) {
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

                if(auth()->user()->pwd_flag) {
                    $query->where('pwd_flag', 1);
                }

                if(auth()->user()->gwa) {
                    $query->where('gwa', '>=', auth()->user()->gwa);
                }

                if(auth()->user()->lib_year_level_id) {
                    $query->where('lib_year_level_id', auth()->user()->lib_year_level_id)
                    ->orWhereNull('lib_year_level_id');
                }

                if(auth()->user()->lib_academic_program_id) {
                    $query->where('lib_academic_program_id', auth()->user()->lib_academic_program_id)
                    ->orWhereNull('lib_academic_program_id');
                }

                $parent = auth()->user()->parents()->first();
                if ($parent && $parent->average_monthly_income) {
                    $query->where('lib_average_monthly_income_id', $parent->average_monthly_income)
                    ->orWhereNull('lib_average_monthly_income_id');
                }

                if (auth()->user()->parents()->where('ofw_flag', 1)->exists()) {
                    $query->where('no_ofw_flag', 0);
                }

                if (auth()->user()->parents()->where('solo_parent_flag', 0)->exists()) {
                    $query->where('solo_parent_flag', 0);
                }
            })
            ->when(isset($request->lib_posting_category_id), function ($q) use ($request) {
                $q->where('lib_posting_category_id', $request->lib_posting_category_id);
            })
            ->when(isset($request->is_published), function ($q) use ($request) {
                if($request->is_published == 'published') {
                    $q->whereNotNull('date_published');
                } else {
                    $q->whereNull('date_published');
                }

            })
            ->when(isset($request->start_date), function ($q) use ($request) {
                $q->where('date_published', '>=', $request->start_date);
            })
            ->when(isset($request->end_date), function ($q) use ($request) {
                $q->where('date_published', '<=', $request->end_date);
            })
            ->when(isset($request->municipality_code), function ($q) use ($request) {
                $q->whereHas('barangay.geographic', function ($q) use ($request) {
                    $q->where('psgc_10_digit_code', $request->municipality_code);
                });
            })
            ->with('barangay.geographic')
            ->allowedIncludes(['category', 'barangay', 'user', 'applicants', 'approvedApplicants'])
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
        if(!auth()->user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
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
    public function update(Request $request, Posting $postingInformation)
    {
        $data = $postingInformation->update($request->all());
        return $this->sendResponse($data, 'Posting successfully updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if (!auth()->user()->is_admin) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $posting = Posting::find($id);

        if (!$posting) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (!is_null($posting->date_published)) {
            return response()->json(['message' => 'Cannot delete. Posting has already been published.'], 400);
        }

        $posting->delete();

        return response()->json(['message' => 'Posting deleted successfully']);
    }

    public function publicInfo(Request $request)
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
            ->when(isset($request->lng) && isset($request->lat) && isset($request->radius), function($query) use($request) {
                $query->whereRaw("ST_Distance_Sphere(coordinates, ST_GeomFromText(?)) <= ?", [
                    "POINT($request->lng $request->lat)",
                    $request->radius
                ]);
            })
            ->when(isset($request->is_published), function ($q) use ($request) {
                if($request->is_published == 'published') {
                    $q->whereNotNull('date_published');
                } else {
                    $q->whereNull('date_published');
                };
            })
            ->when(isset($request->lib_posting_category_id), function ($q) use ($request) {
                $q->where('lib_posting_category_id', $request->lib_posting_category_id);
            })
            ->withCount('applicants')
            ->allowedIncludes(['category', 'barangay', 'user', 'applicants'])
            ->defaultSort(['date_published', 'title'])
            ->allowedSorts(['date_published', 'title', 'date_end']);
        if ($perPage === 'all') {
            return PostingResource::collection($data->get());
        }

        return PostingResource::collection($data->paginate($perPage)->withQueryString());
    }
}
