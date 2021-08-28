<?php

namespace App\Http\Controllers\API\Permission;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionCollection;
use App\Http\Resources\ActivePermissionCollection;
use App\Models\Permission;
use App\Http\Resources\Permission as PermissionResource;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PermissionController extends BaseController
{
    protected $model = Permission::class;
    protected $resource = PermissionResource::class;
    protected $collection = PermissionCollection::class;

    protected $validations_create = [
        'name' => 'required|max:255|unique:permissions'
    ];

    protected $validations_update = [];

    public function get_active_permissions(Role $role) {
        $data = (new $this->model());
        $data->orderByDesc('name');
        $data = $data->get();
        $data = $data->map(function ($d) use ($role) {
            $d['active'] = $role->relations->contains($d->id);
           return $d;
        });

        return $this->sendResponse((new ActivePermissionCollection($data, $role)), 'Successfully get active permissions');
    }

    public function update(Request $request, int $id)
    {
        $item = $this->model::find($id);

        if (is_null($item)) {
            return $this->sendError('Item does not exists.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255|unique:permissions,name,' . $item->name
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $item->update($request->all());

        $saved = $item->save();
        $response = new $this->resource($item);
        $message = 'Item updated successfully.';

        if (!$saved) {
            $message = 'Item has been skipped, because no columns has been updated.';
        }

        return $this->sendResponse($response, $message);
    }

    public function test_permission(Request $request) {
        $input = $request->all();

        $validator = Validator::make($input, [
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $response = [];
        $permissions = $request->input('permissions');
        foreach ($permissions as $permission) {
            $response[$permission] = auth()->user()->hasPermission($permission);
        }

        return $this->sendResponse($response, 'Successfully tested permissions.');
    }
}
