<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Supplier\IndexRequest;
use App\Http\Requests\Admin\Supplier\StoreRequest;
use App\Http\Requests\Admin\Supplier\UpdateRequest;
use App\Models\Supplier;
use App\Traits\Authorizable;
use App\Transformers\SupplierTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class SupplierController extends Controller
{
    use Authorizable;
    /**
     * @var SupplierTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * SupplierController constructor.
     * @param SupplierTransformer $transformer The transformer used to transform the model
     */
    public function __construct(SupplierTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Supplier $supplier
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Supplier $supplier, IndexRequest $request)
    {
        try {
            if (Input::get('limit')) {
                $this->setPagination(Input::get('limit'));
            }

            $pagination = $supplier->search($request->get('q'), null, true)
                ->sortable()
                ->orderBy('id', 'asc');

            if ($request->has('all')) {
                $data = $this->transformer->transformCollection(collect($pagination->get()));
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

        $supplier = Supplier::create($request->all());

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($supplier),
            'message' => 'The supplier has been added'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Supplier $supplier)
    {
        return $this->respond($this->transformer->transform($supplier));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Supplier $supplier)
    {
        DB::beginTransaction();

        $supplier->update($request->all());

        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($supplier),
            'message' => 'The supplier has been updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();
        return $this->respond([
            'data' => $this->transformer->transform($supplier),
            'message' => 'The Supplier has been deleted.'
        ]);
    }
}
