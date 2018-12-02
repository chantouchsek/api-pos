<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\StoreRequest;
use App\Http\Requests\Admin\Category\UpdateRequest;
use App\Models\Category;
use App\Transformers\CategoryTransformer;
use Illuminate\Support\Facades\Input;
use Exception;

class CategoryController extends Controller
{
    /**
     * @var CategoryTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * CategoryController constructor.
     * @param CategoryTransformer $transformer The transformer used to transform the model
     */
    public function __construct(CategoryTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Category $category)
    {
        try {
            if (Input::get('limit')) {
                $this->setPagination(Input::get('limit'));
            }

            $pagination = $category->sortable()->paginate($this->getPagination());

            $data = $this->transformer->transformCollection(collect($pagination->items()));

            return $this->respondWithPagination($pagination, [
                'data' => $data
            ]);
        } catch (Exception $e) {
            return $this->respondInternalError($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $category = new Category($request->all());
        $category->save();
        return $this->respondCreated();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Category $category)
    {
        return $this->respond($this->transformer->transform($category));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function edit(Category $category)
    {
        return $this->respond($this->transformer->transform($category));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Category $category)
    {
        $category->update($request->all());
        return $this->respondCreated('Item updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Category $category
     * @return \Illuminate\Http\JsonResponse
     * @throws Exception
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return $this->respondCreated('Item deleted.');
        } catch (Exception $exception) {
            return $this->respondInternalError();
        }
    }
}
