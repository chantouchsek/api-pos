<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\GiftCard\IndexRequest;
use App\Http\Requests\Admin\GiftCard\StoreRequest;
use App\Http\Requests\Admin\GiftCard\UpdateRequest;
use App\Models\GiftCard;
use App\Traits\Authorizable;
use App\Transformers\GiftCardTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class GiftCardController extends Controller
{
    use Authorizable;
    /**
     * @var GiftCardTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * GiftCardController constructor.
     * @param GiftCardTransformer $transformer The transformer used to transform the model
     */
    public function __construct(GiftCardTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param GiftCard $giftCard
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(GiftCard $giftCard, IndexRequest $request)
    {
        try {
            if (Input::get('limit')) {
                $this->setPagination(Input::get('limit'));
            }

            $pagination = $giftCard->when($request->input('active'), function (Builder $query) use ($request) {
                return $query->where('active', $request->input('active', 1));
            })
                ->search($request->get('q'), null, true)
                ->sortable()
                ->orderBy('id', 'asc');

            if ($request->has('all')) {
                $data = $this->transformer->transformCollection(collect($pagination->where('active', true)->get()));
                return $this->respond([
                    'data' => $data,
                    'pagination' => [
                        'total_count' => count($data),
                        'total_pages' => 1,
                        'current_page' => 1,
                        'limit' => 10,
                    ]
                ]);
            }

            $pagination = $pagination->paginate($this->getPagination());

            $data = $this->transformer->transformCollection(collect($pagination->items()));

            return $this->respondWithPagination($pagination, ['data' => $data]);

        } catch (\Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $giftCard = GiftCard::create($request->all());
        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($giftCard),
            'message' => 'Gift card has been created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\GiftCard $giftCard
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(GiftCard $giftCard)
    {
        return $this->respond($this->transformer->transform($giftCard));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\GiftCard $giftCard
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, GiftCard $giftCard)
    {
        DB::beginTransaction();
        $giftCard->update($request->all());
        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($giftCard),
            'message' => 'Gift card has been updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\GiftCard $giftCard
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(GiftCard $giftCard)
    {
        $giftCard->delete();
        return $this->respond([
            'data' => $this->transformer->transform($giftCard),
            'message' => 'Gift card has been deleted.'
        ]);
    }
}
