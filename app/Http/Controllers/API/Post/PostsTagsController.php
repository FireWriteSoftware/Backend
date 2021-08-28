<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\RelationController;
use App\Http\Resources\PostTag;
use App\Http\Resources\PostTagCollection;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostsTagsController extends RelationController
{
    protected $parentModel = Post::class;
    protected $childModel = Tag::class;

    protected $resource = PostTag::class;

    protected $collection = PostTagCollection::class;

    public function __construct() {}

    public function get_all(Request $request, $parent_id) {
        $per_page = $request->get('per_page', 15);

        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError('Invalid parent id.', ['parent_id' => $parent_id]);
        }

        return (new $this->collection($parent->tags->paginate($per_page)))->additional([
            'success' => true,
            'message' => 'Successfully retrieved relation data'
        ]);
    }

    public function check(Request $request, $parent_id, $child_id): JsonResponse
    {
        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError('Invalid parent id.', ['parent_id' => $parent_id]);
        }

        $child = $this->childModel::find($child_id);

        if (is_null($child)) {
            return  $this->sendError('Invalid child id.', ['child_id' => $parent_id]);
        }

        $is_attached = $parent->tags->contains($child);

        return $this->sendResponse([
            'attached' => $is_attached
        ], 'Successfully checked relation');
    }

    public function attach(Request $request, $parent_id, $child_id): JsonResponse
    {
        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError('Invalid parent id.', ['parent_id' => $parent_id]);
        }

        $child = $this->childModel::find($child_id);

        if (is_null($child)) {
            return  $this->sendError('Invalid child id.', ['child_id' => $parent_id]);
        }

        $parent->tags()->attach($child);

        return $this->sendResponse([], 'Successfully attached relation');
    }

    public function detach(Request $request, $parent_id, $child_id): JsonResponse
    {
        $parent = $this->parentModel::find($parent_id);

        if (is_null($parent)) {
            return  $this->sendError('Invalid parent id.', ['parent_id' => $parent_id]);
        }

        $child = $this->childModel::find($child_id);

        if (is_null($child)) {
            return  $this->sendError('Invalid child id.', ['child_id' => $parent_id]);
        }

        $parent->tags()->detach($child);

        return $this->sendResponse([], 'Successfully detached relation');
    }
}
