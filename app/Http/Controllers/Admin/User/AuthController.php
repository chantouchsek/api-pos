<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\ShowRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * @var UserTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * RolesController constructor.
     * @param UserTransformer $transformer The transformer used to transform the model
     */
    public function __construct(UserTransformer $transformer)
    {
        $this->transformer = $transformer;
    }


    /**
     * Display the specified resource.
     *
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(ShowRequest $request)
    {
        $user = $request->user('api');
        return $this->respond($this->transformer->transform($user));
    }
}
