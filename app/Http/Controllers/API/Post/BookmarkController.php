<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\BaseController;
use App\Http\Resources\Bookmark as BookmarkResource;
use App\Http\Resources\BookmarkCollection;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookmarkController extends BaseController
{
    protected $model = Bookmark::class;
    protected $resource = BookmarkResource::class;
    protected $collection = BookmarkCollection::class;

    protected $validations_create = [
        'is_category' => 'boolean|required_if:is_post,0',
        'category_id' => 'required_if:is_category,0|integer|exists:categories,id',
        'is_post' => 'boolean|required_if:is_category,0',
        'post_id' => 'required_if:is_post,0|integer|exists:posts,id'
    ];

    protected $validations_update = [
        'is_category' => 'boolean',
        'category_id' => 'integer|exists:categories,id',
        'is_post' => 'boolean',
        'post_id' => 'integer|exists:posts,id'
    ];

    public function get_posts(Request $request, $post_id)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer',
            'paginate' => 'boolean',
            'additional' => 'array',
            'recent' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $data = $this->model::where('post_id', $post_id)->get();

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);

        if ($recent > 0) {
            $data = $data->sortBy('updated_at', SORT_ASC)->take($recent);
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        }

        $response = (new $this->collection($data));

        if ($request->has('additional')) {
            $additional = $request->get('additional');

            $response = $response::additional(array_merge([
                'success' => true,
                'message' => 'Successfully retrieved bookmarks'
            ],
                $additional));
        }

        return $response;
    }

    public function get_category(Request $request, $cat_id)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer',
            'paginate' => 'boolean',
            'additional' => 'array',
            'recent' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $data = $this->model::where('category_id', $cat_id)->get();

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);

        if ($recent > 0) {
            $data = $data->sortBy('updated_at', SORT_ASC)->take($recent);
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        }

        $response = (new $this->collection($data));

        if ($request->has('additional')) {
            $additional = $request->get('additional');

            $response = $response::additional(array_merge([
                'success' => true,
                'message' => 'Successfully retrieved bookmarks'
            ],
                $additional));
        }

        return $response;
    }

    public function get_users(Request $request, $user_id)
    {
        $validator = Validator::make($request->all(), [
            'per_page' => 'integer',
            'paginate' => 'boolean',
            'additional' => 'array',
            'recent' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $data = $this->model::where('user_id', $user_id)->get();

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);

        if ($recent > 0) {
            $data = $data->sortBy('updated_at', SORT_ASC)->take($recent);
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        }

        $response = (new $this->collection($data));

        if ($request->has('additional')) {
            $additional = $request->get('additional');

            $response = $response::additional(array_merge([
                'success' => true,
                'message' => 'Successfully retrieved bookmarks'
            ],
                $additional));
        }

        return $response;
    }
}
