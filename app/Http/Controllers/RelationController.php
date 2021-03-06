<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RelationController extends Controller
{
    protected $parentModel;
    protected $childModel;

    protected $resource;

    protected $collection;

    public function __construct() {}

    public function get_all(Request $request, $parent_id) {
        $per_page = $request->get('per_page', 15);

        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['parent_id' => $parent_id]);
        }

        return (new $this->collection($parent->relations->paginate($per_page)))->additional([
            'success' => true,
            'message' => __('base.relation.get_all_success')
        ]);
    }

    public function check(Request $request, $parent_id, $child_id): JsonResponse
    {
        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['parent_id' => $parent_id]);
        }

        $child = $this->childModel::find($child_id);

        if (is_null($child)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['child_id' => $parent_id]);
        }

        $is_attached = $parent->relations->contains($child);

        return $this->sendResponse([
            'attached' => $is_attached
        ], __('base.relation.checked_success'));
    }

    public function attach(Request $request, $parent_id, $child_id): JsonResponse
    {
        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['parent_id' => $parent_id]);
        }

        $child = $this->childModel::find($child_id);

        if (is_null($child)) {
            return  $this->sendError(__('base.relation.invalid_child'), ['child_id' => $parent_id]);
        }

        if ($parent->relations->contains($child)) {
            return $this->sendError(__('base.relation.duplicate_combi'), ['parent_id' => $parent_id, 'child_id' => $child_id]);
        }

        $parent->relations()->attach($child);

        return $this->sendResponse([], __('base.relation.attached_success'));
    }

    public function detach(Request $request, $parent_id, $child_id): JsonResponse
    {
        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['parent_id' => $parent_id]);
        }

        $child = $this->childModel::find($child_id);

        if (is_null($child)) {
            return  $this->sendError(__('base.relation.invalid_child'), ['child_id' => $parent_id]);
        }

        $parent->relations()->detach($child);

        return $this->sendResponse([], __('base.relation.detached_success'));
    }
}
