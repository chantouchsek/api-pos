<?php

namespace App\Models;

use App\Traits\Searchable;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * App\Models\Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Permission[] $permissions
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\Permission\Models\Role[] $roles
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $users
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\Spatie\Permission\Models\Permission role($roles)
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $guard_name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Permission searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 */
class Permission extends BasePermission
{
    use Searchable;

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'permissions.name' => 10
        ]
    ];

    /**
     * @return array
     */
    public static function defaultPermissions()
    {
        return [
            'view-users',
            'add-users',
            'edit-users',
            'delete-users',

            'view-roles',
            'add-roles',
            'edit-roles',
            'delete-roles',

            'view-products',
            'add-products',
            'edit-products',
            'delete-products',

            'view-categories',
            'add-categories',
            'edit-categories',
            'delete-categories',

            'view-suppliers',
            'add-suppliers',
            'edit-suppliers',
            'delete-suppliers',

            'view-models',
            'add-models',
            'edit-models',
            'delete-models',

            'view-colors',
            'add-colors',
            'edit-colors',
            'delete-colors',

            'view-customers',
            'add-customers',
            'edit-customers',
            'delete-customers',

            'view-loans',
            'add-loans',
            'edit-loans',
            'delete-loans',

            'view-permissions',
            'add-permissions',
            'edit-permissions',
            'delete-permissions',

            'view-sales',
            'add-sales',
            'edit-sales',
            'delete-sales',

            'view-expenses',
            'add-expenses',
            'edit-expenses',
            'delete-expenses',

            'view-makes',
            'add-makes',
            'edit-makes',
            'delete-makes',

            'view-payrolls',
            'add-payrolls',
            'edit-payrolls',
            'delete-payrolls',

            'view-reports',
            'add-reports',
            'edit-reports',
            'delete-reports'
        ];
    }
}
