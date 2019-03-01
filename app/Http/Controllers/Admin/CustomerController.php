<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\IndexRequest;
use App\Http\Requests\Admin\Customer\StoreRequest;
use App\Http\Requests\Admin\Customer\UpdateRequest;
use App\Models\Customer;
use App\Traits\Authorizable;
use App\Transformers\CustomerTransformer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class CustomerController extends Controller
{
    use Authorizable;
    /**
     * @var CustomerTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * CustomerController constructor.
     * @param CustomerTransformer $transformer The transformer used to transform the model
     */
    public function __construct(CustomerTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Customer $customer
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Customer $customer, IndexRequest $request)
    {
        try {
            if (Input::get('limit')) {
                $this->setPagination(Input::get('limit'));
            }

            $pagination = $customer->search($request->get('q'), null, true)
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

        $customer = Customer::create($request->all());

        DB::commit();

        return $this->respond([
            'data' => $this->transformer->transform($customer),
            'message' => 'The customer has been added'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Customer $customer)
    {
        return $this->respond($this->transformer->transform($customer));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Customer $customer)
    {
        DB::beginTransaction();

        $customer->update($request->all());

        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($customer),
            'message' => 'The customer has been updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer $customer
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();
        return $this->respond([
            'data' => $this->transformer->transform($customer),
            'message' => 'The Customer has been deleted.'
        ]);
    }
}
