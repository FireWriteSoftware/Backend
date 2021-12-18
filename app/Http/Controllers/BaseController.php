<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BaseController extends Controller
{
    protected $model;
    protected $resource;
    protected $collection;

    protected $validations_create = [];
    protected $validations_update = [];
    protected $additionalCreateData = [];

    public function __construct() {
        $this->middleware(function ($request, $next) {
            $this->additionalCreateData = [
                'user_id' => Auth::user()->id ?? 0,
                'guest' => Auth::user() != null
            ];

            return $next($request);
        });
    }

    /**
     * Get all data
     *
     * @param Request $request
     */
    public function get_all(Request $request)
    {
        /**
         * Sort Indices
         *
         * 0 - SORT_REGULAR
         * 1 - SORT_STRING
         * 3 - SORT_DESC
         * 4 - SORT_ASC
         * 5 - SORT_LOCALE_STRING
         * 6 - SORT_NATURAL
         * 8 - SORT_FLAG_CASE
         */
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer',
            'paginate' => 'boolean',
            'search' => 'string',
            'sort' => 'array',
            'sort.column' => 'string|required_with:sort',
            'sort.method' => 'string|required_with:sort',
            'additional' => 'array',
            'recent' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $data = (new $this->model);

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);
        $search = $request->get('search');

        if ($request->has('sort.column') && $request->has('sort.method')) {
            error_log(print_r($request->get('sort'), true));

            $data = $data->orderBy(
                $request->get('sort')['column'],
                $request->get('sort')['method'],
            );
        }

        if ($recent > 0) {
            $data = $data->take($recent);
        }

        if ($search) {
            foreach ((new $this->model())->getFillable() as $inx => $column) {
                if ($inx === 0) {
                    $data = $data->where($column, 'LIKE', '%' . $search . '%');
                } else {
                    $data = $data->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            }
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        } else {
            $data = $data->get();
        }

        $response = (new $this->collection($data));

        if ($request->has('additional')) {
            $additional = $request->get('additional');

            $response = $response::additional(array_merge([
                'success' => true,
                'message' => __('base.base.get_all_success+')
            ],
            $additional));
        }

        return $response;
    }

    /**
     * Get single data
     *
     * @param Request $request
     * @param int $id
     */
    public function get_single(Request $request, int $id)
    {
        $item = $this->model::find($id);
        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $response = new $this->resource($item);
        return $this->sendResponse($response, __('base.base.get_success'));
    }

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

        $item->update($request->all());

        $saved = $item->save();
        $response = new $this->resource($item);
        $message = __('base.base.update_success');

        if (!$saved) {
            $message = __('base.base.update_skipped');
        }

        return $this->sendResponse($response, $message);
    }

    /**
     * Delete item
     *
     * @param Request $request
     * @param int $id
     */
    public function delete(Request $request, int $id)
    {
        $item = $this->model::find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $item->delete();

        return $this->sendResponse([
            'id' => $id
        ], __('base.base.soft_delete_success'));
    }

    /**
     * Force/Final Delete Item
     *
     * @param Request $request
     * @param int $id
     */
    public function force_delete(Request $request, int $id)
    {
        $item = $this->model::withTrashed()->find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $item->forceDelete();

        return $this->sendResponse([
            'id' => $id
        ], __('base.base.force_delete_success'));
    }

    /**
     * Recover item
     *
     * @param Request $request
     * @param int $id
     */
    public function recover(Request $request, int $id) {
        $item = $this->model::withTrashed()->find($id);

        if (is_null($item)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $item->restore();
        $response = (new $this->resource($item));

        return $this->sendResponse($response, __('base.base.recover_success'));
    }
}
