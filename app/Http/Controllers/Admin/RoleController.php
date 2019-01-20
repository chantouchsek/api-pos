<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\DeleteRequest;
use App\Http\Requests\Admin\Role\IndexRequest;
use App\Http\Requests\Admin\Role\StoreRequest;
use App\Http\Requests\Admin\Role\UpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Authorizable;
use App\Transformers\RoleTransformer;
use Illuminate\Support\Facades\DB;


class RoleController extends Controller
{
    use Authorizable;
    /**
     * @var RoleTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * RolesController constructor.
     * @param RoleTransformer $transformer The transformer used to transform the model
     */
    public function __construct(RoleTransformer $transformer)
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

        $pagination = Role::search($request->get('q'), null, true)->paginate($this->getPagination());

        $data = $this->transformer->transformCollection(collect($pagination->items()));

        return $this->respondWithPagination($pagination, [
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request)
    {
        $role = Role::firstOrCreate(['name' => trim($request->input('name'))]);

        if ($role->name == 'Supper Admin') {
            // assign all permissions
            $role->syncPermissions(Permission::all());
        } elseif ($role->name == 'Admin') {
            // for Admin by default only read, write access
            $role->syncPermissions(Permission::where('name', 'LIKE', 'view-%')
                ->orWhere('name', 'LIKE', 'edit-%')
                ->orWhere('name', 'LIKE', 'add-%')
                ->get());
        } else {
            // for others by default only read access
            $role->syncPermissions(Permission::where('name', 'LIKE', 'view-%')->get());
        }

        return $this->respond([
            'data' => $role,
            'message' => 'Role created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        return $this->respond($this->transformer->transform($role));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param  \App\Models\Role $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, Role $role)
    {
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permissions'));

        return $this->respond([
            'data' => $role,
            'message' => 'Role updated.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Role $role
     * @param DeleteRequest $request
     * @return void
     */
    public function destroy(Role $role, DeleteRequest $request)
    {
        //
    }
}
