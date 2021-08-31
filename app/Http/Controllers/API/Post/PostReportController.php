<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostReportCollection;
use App\Http\Resources\PostReport as PostReportResource;
use App\Models\Post;
use App\Models\PostReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostReportController extends Controller
{
    public function index(Request $request) {
        $per_page = $request->get('per_page', 15);
        $search = $request->get('search');
        $data = (new PostReport);

        if ($search) {
            foreach ((new PostReport())->getFillable() as $inx => $column) {
                if ($inx === 0) {
                    $data = $data->where($column, 'LIKE', '%' . $search . '%');
                } else {
                    $data = $data->orWhere($column, 'LIKE', '%' . $search . '%');
                }
            }
        }

        return (new PostReportCollection($data->paginate($per_page)))->additional([
            'success' => true,
            'message' => __('base.base.get_all_success')
        ]);
    }

    public function get_posts(Request $request, $post_id) {
        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $per_page = $request->get('per_page', 15);
        return (new PostReportCollection(PostReport::where('post_id', $post->id)->paginate($per_page)))->additional([
            'success' => true,
            'message' => __('base.base.get_all_success')
        ]);
    }

    public function show(Request $request, $report_id) {
        $report = Post::find($report_id);

        if (is_null($report)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        return $this->sendResponse(new PostReportResource($report), __('base.base.get_success'));
    }

    public function store(Request $request, $post_id) {
        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $input['post_id'] = $post_id;
        $input['user_id'] = auth()->user()->id;

        $report = PostReport::create($input);

        return $this->sendResponse(new PostReportResource($report), __('base.base.store_success'));
    }

    public function update(Request $request, $report_id) {
        $report = PostReport::find($report_id);

        if (is_null($report)) {
            return $this->sendError(__('base.base.update_success'));
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'content' => 'required',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $report->content = $input['content'];
        $report->active = $input['active'];
        $report->save();

        return $this->sendResponse(new PostReportResource($report), __('base.base.update_success'));
    }

    public function destroy(Request $request, $report_id) {
        $report = PostReport::find($report_id);

        if (is_null($report)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $report->delete();

        return $this->sendResponse([], __('base.base.soft_delete_success'));
    }
}
