<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Purchase\IndexRequest;
use App\Http\Requests\Admin\Purchase\StoreRequest;
use App\Http\Requests\Admin\Purchase\UpdateRequest;
use App\Models\Product;
use App\Models\Purchase;
use App\Traits\Authorizable;
use App\Transformers\PurchaseTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    use Authorizable;
    /**
     * @var PurchaseTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * PurchasesController constructor.
     * @param PurchaseTransformer $transformer The transformer used to transform the model
     */
    public function __construct(PurchaseTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request)
    {
        if ($request->get('limit')) {
            $this->setPagination($request->get('limit'));
        }

        $pagination = Purchase::search($request->get('q'), null, true)
            ->when($request->has('in_stock'), function (Builder $builder) {
                return $builder->where('qty', '>', 0);
            })
            ->sortable()
            ->paginate($this->getPagination());

        $data = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, ['data' => $data]);
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

        $purchase = Purchase::create($request->except(['products']));

        $purchase->user()->associate($request->user('api')->id);

        if ($request->has('products')) {
            foreach (collect($request->input('products')) as $key => $purchaseProduct) {
                $added = $purchase->products()->create($purchaseProduct);
                $product = Product::find($added->product_id);
                $product->update([
                    'qty' => $product->qty + $purchaseProduct['qty'],
                ]);
            }
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $purchase->addMedia($attachment)->toMediaCollection('purchase-attachments');
            }
        }

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($purchase),
            'message' => 'Purchase has been created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Purchase $purchase
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Purchase $purchase)
    {
        return $this->respond($this->transformer->transform($purchase));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Purchase $purchase
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Purchase $purchase)
    {
        DB::beginTransaction();

        $purchase->update($request->all());
        $products = $purchase->products()->get();
        $requestPurchases = collect($request->input('products'));
        if ($request->has('products')) {
            foreach ($products as $index => $requestPurchase) {
                if ($requestPurchases[$index]['product_id'] === $requestPurchase->product_id) {
                    $requestPurchase->qty = $requestPurchases[$index]['qty'];
                    $requestPurchase->cost = $requestPurchases[$index]['cost'];
                    $requestPurchase->sub_total = $requestPurchases[$index]['sub_total'];
                    $requestPurchase->save();
                    $product = Product::find($requestPurchase->product_id);
                    $product->update([
                        'qty' => $product->qty + $requestPurchases[$index]['qty'],
                    ]);
                }
            }
            foreach ($requestPurchases as $key => $purchaseProduct) {
                if ($purchaseProduct['is_new'] === true) {
                    $added = $purchase->products()->create($purchaseProduct);
                    $product = Product::find($added->product_id);
                    $product->update([
                        'qty' => $product->qty + $purchaseProduct['qty'],
                    ]);
                }
            }
        }

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $purchase->addMedia($attachment)->toMediaCollection('purchase-attachments');
            }
        }

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($purchase),
            'message' => 'Purchase has been updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Purchase $purchase
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Purchase $purchase)
    {
        $purchase->delete();
        return $this->respond([
            'data' => $this->transformer->transform($purchase),
            'message' => 'Purchase has been deleted.'
        ]);
    }
}
