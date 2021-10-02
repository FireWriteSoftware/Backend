<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\BaseController;
use App\Http\Resources\AnnouncementCollection;
use App\Http\Resources\Announcement as AnnouncementResource;
use App\Http\Resources\WebhookCollection;
use App\Models\Webhook;
use App\Http\Resources\Webhook as WebhookResource;
use App\Models\WebhookScope;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WebhookController extends BaseController
{
    protected $model = Webhook::class;
    protected $resource = WebhookResource::class;
    protected $collection = WebhookCollection::class;

    protected $validations_create = [
        'type' => 'required|string|max:255',
        'url' => 'required|string|max:255',
        'scopes' => 'required|array',
        'scopes.*' => 'required|string|max:255'
    ];

    protected $validations_update = [
        'type' => 'nullable|string|max:255',
        'url' => 'nullable|string|max:255'
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

        $data = array_merge($request->all(), $this->additionalCreateData);
        $scopes = $data['scopes'];
        $data['scopes'] = null;
        $created_object = $this->model::create($data);

        if (is_null($created_object)) {
            return $this->sendError(__('base.base.store_unknown_error'), [], 500);
        }

        $sscopes = [];
        foreach ($scopes as $scope) {
            $sscopes[] = WebhookScope::create([
                'webhook_id' => $created_object->id,
                'scope' => $scope
            ]);
        }

        $created_object->scopes()->saveMany($sscopes);

        $response = new $this->resource($created_object);
        return $this->sendResponse($response, __('base.base.store_success'));
    }

    public function add_scope(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'scopes' => 'required|array',
            'scopes.*' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $item = $this->model::find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $sscopes = [];
        foreach ($request->get('scopes') as $scope) {
            $sscopes[] = WebhookScope::create([
                'webhook_id' => $item->id,
                'scope' => $scope
            ]);
        }

        $item->scopes()->saveMany($sscopes);
        return $this->sendResponse(new $this->resource($item), __('base.base.store_success'));
    }

    public function rem_scope(Request $request, $id) {
        $validator = Validator::make($request->all(), [
            'scopes' => 'required|array',
            'scopes.*' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $item = $this->model::find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        foreach ($request->get('scopes') as $scope) {
            WebhookScope::where('scope', $scope)->delete();
        }

        return $this->sendResponse(new $this->resource($item), __('base.base.force_delete_success'));
    }
}
