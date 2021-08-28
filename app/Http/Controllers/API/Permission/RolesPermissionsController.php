<?php

namespace App\Http\Controllers\API\Permission;

use App\Http\Controllers\RelationController;
use App\Models\Role;
use App\Models\Permission;
use App\Http\Resources\RolePermissionCollection;
use App\Http\Resources\RolePermission as RolePermissionResource;

class RolesPermissionsController extends RelationController
{
    protected $parentModel = Role::class;
    protected $childModel = Permission::class;

    protected $resource = RolePermissionResource::class;
    protected $collection = RolePermissionCollection::class;
}
