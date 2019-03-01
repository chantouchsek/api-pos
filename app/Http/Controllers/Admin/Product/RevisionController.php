<?php

namespace App\Http\Controllers\Admin\Product;

use App\Http\Requests\Admin\Product\IndexRequest;
use App\Models\Product;
use App\Traits\Authorizable;
use App\Transformers\RevisionTransformer;
use App\Http\Controllers\Controller;

class RevisionController extends Controller
{
    use Authorizable;

    /**
     * @var RevisionTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * ProductController constructor.
     * @param RevisionTransformer $transformer The transformer used to transform the model
     */
    public function __construct(RevisionTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @param Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request, Product $product)
    {
        if ($request->get('limit')) {
            $this->setPagination($request->get('limit'));
        }

        $pagination = $product->revisionHistory()->paginate($this->getPagination());

        $data = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, [
            'data' => $data
        ]);
    }
}
