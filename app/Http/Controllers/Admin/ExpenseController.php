<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Expense\IndexRequest;
use App\Http\Requests\Admin\Expense\StoreRequest;
use App\Http\Requests\Admin\Expense\UpdateRequest;
use App\Models\Expense;
use App\Traits\Authorizable;
use App\Transformers\ExpenseTransformer;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;

class ExpenseController extends Controller
{
    use Authorizable;
    /**
     * @var ExpenseTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * ExpenseController constructor.
     * @param ExpenseTransformer $transformer The transformer used to transform the model
     */
    public function __construct(ExpenseTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Expense $expense
     * @param IndexRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Expense $expense, IndexRequest $request)
    {
        try {
            if (Input::get('limit')) {
                $this->setPagination(Input::get('limit'));
            }

            $pagination = $expense->search($request->get('q'), null, true)
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
        $data = $request->all();

        $expense = Expense::create($data);

        $expense->user()->associate($request->user('api')->id);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $expense->addMedia($attachment)->toMediaCollection('expense-attachments');
            }
        }

        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($expense),
            'message' => 'The expense has been created'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Expense $expense)
    {
        return $this->respond($this->transformer->transform($expense));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Expense $expense)
    {
        DB::beginTransaction();
        $expense->update($request->all());

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $attachment) {
                $expense->addMedia($attachment)->toMediaCollection('expense-attachments');
            }
        }

        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($expense),
            'message' => 'The expense has been updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Expense $expense
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(Expense $expense)
    {
        $expense->delete();
        return $this->respond([
            'data' => $this->transformer->transform($expense),
            'message' => 'The expense has been deleted.'
        ]);
    }
}
