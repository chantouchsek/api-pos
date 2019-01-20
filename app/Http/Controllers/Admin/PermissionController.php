<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Permission\IndexRequest;
use App\Http\Requests\Admin\Permission\StoreRequest;
use App\Http\Requests\Admin\Permission\UpdateRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Traits\Authorizable;
use App\Transformers\PermissionTransformer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    use Authorizable;
    /**
     * @var PermissionTransformer The transformer used to transform the model.
     */
    protected $transformer;

    /**
     * PermissionsController constructor.
     * @param PermissionTransformer $transformer The transformer used to transform the model
     */
    public function __construct(PermissionTransformer $transformer)
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

        $pagination = Permission::search($request->get('q'), null, true)->paginate($this->getPagination());

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
     * @throws \Exception
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();
        $permissions = $this->generatePermissions($request);

        $arrayPermissions = collect();

        foreach ($permissions as $permission) {
            $permizzion = Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web'
            ]);
            $arrayPermissions->push($permizzion);
        }

        // sync role for supper admin
        if ($role = Role::where('name', '=', 'Supper Admin')->first()) {
            $role->syncPermissions(Permission::all());
        }

        // sync role for admin
        if ($role = Role::where('name', '=', 'Admin')->first()) {
            $permizzions = Permission::where('name', 'LIKE', 'view-%')
                ->orWhere('name', 'LIKE', 'edit-%')
                ->orWhere('name', 'LIKE', 'add-%')
                ->get();
            $role->syncPermissions($permizzions);
        }
        DB::commit();
        return $this->respond([
            'data' => $arrayPermissions,
            'message' => 'Permission created.'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        return $this->respond($this->transformer->transform($permission));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateRequest $request
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function update(UpdateRequest $request, Permission $permission)
    {
        DB::beginTransaction();
        $permission->name = $request->input('name');
        $permission->save();
        $permission->syncRoles($request->input('roles'));
        DB::commit();
        return $this->respond([
            'data' => $permission,
            'message' => 'Permission updated'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Permission $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission)
    {
        //
    }

    /**
     * @param Request $request
     * @return array
     */
    private function generatePermissions(Request $request)
    {
        $abilities = ['view', 'add', 'edit', 'delete'];
        $name = $this->getNameArgument($request);

        return array_map(function ($val) use ($name) {
            return $val . '-' . $name;
        }, $abilities);
    }

    /**
     * @param Request $request
     * @return string
     */
    private function getNameArgument(Request $request)
    {
        return strtolower(str_plural($request->input('name')));
    }
}
