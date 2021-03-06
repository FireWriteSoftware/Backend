<?php

namespace App\Http\Controllers\API\Permission;

use App\Http\Controllers\BaseController;
use App\Http\Resources\MiniUserCollection;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\UserCollection;
use App\Models\Role;
use App\Http\Resources\Role as RoleResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RoleController extends BaseController
{
    protected $model = Role::class;
    protected $resource = RoleResource::class;
    protected $collection = RoleCollection::class;

    protected $validations_create = [
        'name' => 'required|max:255',
        'description' => 'nullable|string',
        'color' => 'required|regex:^(?:[0-9a-fA-F]{3}){1,2}$^',
        'is_default' => 'nullable|boolean'
    ];

    protected $validations_update = [
        'name' => 'max:255',
        'description' => 'nullable|string',
        'color' => 'regex:^(?:[0-9a-fA-F]{3}){1,2}$^',
        'is_default' => 'nullable|boolean'
    ];

    /**
     * Store data
     *
     * @param Request $request
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->validations_create);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $this->model::where('is_default', 1)->update(['is_default' => 0]);

        $data = array_merge($request->all(), $this->additionalCreateData);
        $created_object = $this->model::create($data);

        if (is_null($created_object)) {
            return $this->sendError(__('base.base.store_unknown_error'), [], 500);
        }

        $response = new $this->resource($created_object);
        return $this->sendResponse($response, __('base.base.store_success'));
    }

    /**
     * Update item
     *
     * @param Request $request
     * @param int $id
     */
    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), $this->validations_update);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $item = $this->model::find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $this->model::where('is_default', 1)->update(['is_default' => 0]);

        $item->update($request->all());

        $saved = $item->save();
        $response = new $this->resource($item);
        $message = __('base.base.update_success');

        if (!$saved) {
            $message = __('base.base.update_skipped');
        }

        return $this->sendResponse($response, $message);
    }

    public function get_users(Role $role) {
        return new MiniUserCollection(User::where('role_id', $role->id)->get());
    }
}
