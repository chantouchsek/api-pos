<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\IndexRequest;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use App\Traits\Authorizable;
use App\Transformers\ProductTransformer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    use Authorizable;
    /**
     * @var ProductTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * ProductsController constructor.
     * @param ProductTransformer $transformer The transformer used to transform the model
     */
    public function __construct(ProductTransformer $transformer)
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

        $pagination = Product::search($request->get('q'), null, true)
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

        $product = new Product($request->all());

        $product->user()->associate($request->user('api')->id);

        $allowedMimeTypes = ['image/jpeg', 'image/pipeg', 'image/gif', 'image/png'];

        if ($request->has('file') && strpos($request->get('file'), ';base64') !== false) {
            $product->addMediaFromBase64($request->get('file'), $allowedMimeTypes)->toMediaCollection('feature-image');
        }

        $product->save();

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($product),
            'message' => 'Product has been created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Product $product)
    {
        return $this->respond($this->transformer->transform($product));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Product $product)
    {
        DB::beginTransaction();

        $product->fill($request->all());

        $allowedMimeTypes = ['image/jpeg', 'image/pipeg', 'image/gif', 'image/png'];

        if ($request->has('file') && strpos($request->get('file'), ';base64') !== false) {
            $product->addMediaFromBase64($request->get('file'), $allowedMimeTypes)->toMediaCollection('feature-image');
        }

        $product->save();

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($product),
            'message' => 'Product has been updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        $product->delete();

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($product),
            'message' => 'Product has been destroyed.'
        ]);
    }
}
