<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\BaseController;
use App\Http\Resources\CategoryCollection;
use App\Http\Resources\Category as CategoryResource;
use App\Http\Resources\MiniCategoryCollection;
use App\Http\Resources\NoDepthCategory;
use App\Http\Resources\SmallCategoryCollection;
use App\Http\Resources\StructuredCategory as StructuredCategoryResource;
use App\Models\Category;
use App\Http\Resources\StructuredCategoryCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    protected $model = Category::class;
    protected $resource = CategoryResource::class;
    protected $collection = CategoryCollection::class;

    protected $validations_create = [
        'title' => 'required|max:255',
        'description' => 'required',
        'thumbnail' => 'nullable|max:255',
        'parent_id' => 'nullable|integer|exists:categories,id'
    ];

    protected $validations_update = [
        'title' => 'string|max:255',
        'description' => 'string',
        'thumbnail' => 'nullable|string|max:255',
        'parent_id' => 'nullable|integer|exists:categories,id|nullable'
    ];

    public function structured(Request $request) {
        $per_page = $request->get('per_page', 15);
        return (new StructuredCategoryCollection(Category::where('parent_id', null)->paginate($per_page)))->additional([
            'success' => true,
            'message' => 'Successfully retrieved categories'
        ]);
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
            'sort' => 'array',
            'sort.column' => 'string|required_with:sort',
            'sort.method' => 'string|required_with:sort',
            'additional' => 'array',
            'recent' => 'integer',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $data = $this->model::all();

        $per_page = $request->get('per_page', 15);
        $paginate_data = $request->get('paginate', true);
        $recent = $request->get('recent', 0);

        if ($recent > 0) {
            $data = $data->sortBy('updated_at', SORT_ASC)->take($recent);
        }

        if ($request->has('sort.column') && $request->has('sort.method')) {
            error_log(print_r($request->get('sort'), true));

            $data = $data->orderBy(
                $request->get('sort')['column'],
                $request->get('sort')['method'],
            );
        }

        if ($paginate_data) {
            $data = $data->paginate($per_page);
        }

        $response = (new SmallCategoryCollection($data));

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

    /**
     * Get single data
     *
     * @param Request $request
     * @param int $id
     */
    public function get_single(Request $request, int $id)
    {
        $load_depth = $request->get('load_depth', true);

        if ($id != 0) {
            $item = $this->model::find($id);
            if (is_null($item)) {
                return $this->sendError('Item does not exists.');
            }

            $response = [];

            if ($load_depth) {
                $response = (new $this->resource($item))->onlyVerified(true);
            } else {
                $response = (new NoDepthCategory($item))->onlyVerified(true);
            }

            return $this->sendResponse($response, 'Successfully fetched item');
        } else {
            $data = $this->model::where('parent_id', null)->get();
            return $this->sendResponse(new MiniCategoryCollection($data), 'Successfully fetched items of page 0');
        }
    }
}
