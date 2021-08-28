<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostHistoryCollection;
use App\Models\PostHistory;
use App\Http\Resources\PostHistory as PostHistoryResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostHistoryController extends Controller
{
    protected $model = PostHistory::class;
    protected $resource = PostHistoryResource::class;
    protected $collection = PostHistoryCollection::class;

    public function get_posts(Request $request, $post_id) {
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
            'sort' => 'array',
            'sort.column' => 'string|required_with:sort',
            'sort.method' => 'integer|required_with:sort',
            'additional' => 'array',
            'recent' => 'integer'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $data = $this->model::where('post_id', $post_id);

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);

        if ($recent > 0) {
            $data = $data->sortBy('updated_at', SORT_ASC)->take($recent);
        }

        if ($request->has('sort')) {
            $data = $data->sortBy(
                $request->get('sort.column', 'id'),
                $request->get('sort.method', SORT_ASC)
            );
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        }

        $response = (new $this->collection($data));

        if ($request->has('additional')) {
            $additional = $request->get('additional');

            $response = $response::additional(array_merge([
                'success' => true,
                'message' => 'Successfully retrieved announcements'
            ],
                $additional));
        }

        return $response;
    }

    // Overwrite methods wth empty ones
    public function update(Request $request, $id) {}
    public function store(Request $request) {}
}
