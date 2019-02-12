<?php

namespace App\Http\Controllers\Admin\User;

use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UploadAvatarRequest;
use App\Transformers\UserTransformer;
use Illuminate\Support\Facades\Storage;

class UploadAvatarController extends Controller
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
     * Update the specified resource in storage.
     *
     * @param  UploadAvatarRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function upload(UploadAvatarRequest $request, User $user)
    {
        $input = $request->all();
        if ($request->file('avatar') && $request->file('avatar')->isValid()) {
            if ($user->hasMedia('avatars')) {
                $user->clearMediaCollection('avatars');
            }
            $user->addMediaFromRequest('avatar')->toMediaCollection('avatars');
        }
        $user->update($input);
        return $this->respond(['data' => $this->transformer->transform($user), 'message' => 'User avatar uploaded.']);
    }
}
