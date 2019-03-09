<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Sale\StoreRequest;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleProduct;
use App\Traits\Authorizable;
use App\Transformers\SaleTransformer;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use Authorizable;
    /**
     * @var SaleTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * SalesController constructor.
     * @param SaleTransformer $transformer The transformer used to transform the model
     */
    public function __construct(SaleTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        if ($request->get('limit')) {
            $this->setPagination($request->get('limit'));
        }

        $pagination = Sale::search($request->get('q'), null, true)
            ->sortable()
            ->paginate($this->getPagination());

        $data = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, [
            'data' => $data
        ]);
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

        $data = $request->all();

        $data['user_id'] = $request->user('api')->id;

        $sale = Sale::create($data);

        if ($request->has('payment')) {
            $sale->payments()->create([
                'date' => Carbon::parse($request->get('payment.date')),
                'amount' => $request->input('payment.amount'),
                'reference' => $request->input('payment.reference'),
                'paid_by' => $request->input('payment.paid_by'),
                'notes' => $request->input('payment.notes'),
                'user_id' => $request->user('api')->id
            ]);
        }

        if ($request->has('products')) {
            foreach ($data['products'] as $product) {
                SaleProduct::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product['id'],
                    'qty' => $product['qty'],
                    'price' => $product['price'],
                    'sub_total' => $product['sub_total']
                ]);
                $findProduct = Product::find($product['id']);
                $findProduct->qtyDecrement($product['qty']);
            }
        }

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($sale),
            'message' => 'Sale added.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sale $sale
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Sale $sale): JsonResponse
    {
        return $this->respond($this->transformer->transform($sale));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Sale $sale
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, Sale $sale)
    {
        DB::beginTransaction();

        $data = $request->all();

        $sale->update($data);

        if ($request->has('products')) {
            $products = $sale->products()->get();
            $requestProducts = collect($request->get('products'));
            if (count($requestProducts)) {
                $i = 0;
                foreach ($products as $product) {
                    $product->update([
                        'qty' => $product['qty'],
                        'price' => $product['price'],
                        'sub_total' => $product['sub_total']
                    ]);
                    $i++;
                }
            }
        }

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($sale),
            'message' => 'Sale has been updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sale $sale
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy(Sale $sale)
    {
        DB::beginTransaction();

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($sale),
            'message' => 'Sale has been deleted.'
        ]);
    }
}
