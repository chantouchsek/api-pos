<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\User\DeleteRequest;
use App\Http\Requests\Admin\User\IndexRequest;
use App\Http\Requests\Admin\User\ShowRequest;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\User;
use App\Models\Role;
use App\Traits\Authorizable;
use App\Transformers\UserTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    use Authorizable;
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
     * Display a listing of the resource.
     *
     * @param IndexRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(IndexRequest $request, User $user)
    {
        if ($request->get('limit')) {
            $this->setPagination($request->get('limit'));
        }

        $pagination = $user->when($request->input('active'), function ($query) use ($request) {
            return $query->where('active', $request->input('active', 1));
        })
            ->search($request->get('q'), null, true)
            ->sortable()->paginate($this->getPagination());

        $users = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, [
            'data' => $users
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded
     * @throws \Spatie\MediaLibrary\Exceptions\FileCannotBeAdded\InvalidBase64Data
     * @throws \Exception
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $input = $request->except(['avatar_url']);
        $input['password'] = Hash::make($input['phone_number']);

        $user = User::create($input);

        // $user->assignRole($input['roles']);

        // Handle the user roles
        $this->syncPermissions($request, $user);

        $allowedMimeTypes = ['image/jpeg', 'image/pipeg', 'image/gif', 'image/png'];

        if ($request->has('avatar_url') && strpos($request->get('avatar_url'), ';base64') !== false) {
            $user->addMediaFromBase64($request->get('avatar_url'), $allowedMimeTypes)->toMediaCollection('avatar');
        }

        DB::commit();
        return $this->respond([
            'data' => $this->transformer->transform($user),
            'message' => 'User has been created.'
        ]);
    }


    /**
     * Display the specified resource.
     *
     * @param User $user
     * @param ShowRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user, ShowRequest $request)
    {
        return $this->respond($this->transformer->transform($user));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, User $user)
    {
        DB::beginTransaction();
        $input = $request->except(['avatar_url']);

        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = array_except($input, array('password'));
        }

        $user->update($input);

        // Handle the user roles
        $this->syncPermissions($request, $user);

        $allowedMimeTypes = ['image/jpeg', 'image/pipeg', 'image/gif', 'image/png'];


        if ($request->has('avatar_url') && strpos($request->get('avatar_url'), ';base64') !== false) {
            $user->addMediaFromBase64($request->get('avatar_url'), $allowedMimeTypes)->toMediaCollection('avatar');
        }

        DB::commit();
        return $this->respond(['data' => $this->transformer->transform($user), 'message' => 'User updated.']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param User $user
     * @param DeleteRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function destroy(User $user, DeleteRequest $request)
    {
        DB::beginTransaction();
        $user->delete();
        DB::commit();
        return $this->respond(['data' => $this->transformer->transform($user), 'message' => 'User Deleted.']);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return mixed
     */
    private function syncPermissions($request, User $user)
    {
        // Get the submitted roles
        $roles = $request->input('roles', []);
        $permissions = $request->input('permissions', []);

        // Get the roles
        $roles = Role::whereIn('name', $roles)->get();

        // check for current role changes
        if (!$user->hasAllRoles($roles)) {
            // reset all direct permissions for user
            $user->permissions()->sync([]);
        } else {
            // handle permissions
            $user->syncPermissions($permissions);
        }

        $user->syncRoles($roles);
        return $user;
    }
}
