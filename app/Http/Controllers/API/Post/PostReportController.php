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
        return (new PostReportCollection(PostReport::paginate($per_page)))->additional([
            'success' => true,
            'message' => 'Successfully retrieved reports'
        ]);
    }

    public function get_posts(Request $request, $post_id) {
        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError('Post does not exists.');
        }

        $per_page = $request->get('per_page', 15);
        return (new PostReportCollection(PostReport::where('post_id', $post->id)->paginate($per_page)))->additional([
            'success' => true,
            'message' => 'Successfully retrieved post reports'
        ]);
    }

    public function show(Request $request, $report_id) {
        $report = Post::find($report_id);

        if (is_null($report)) {
            return $this->sendError('Report does not exists.');
        }

        return $this->sendResponse(new PostReportResource($report), 'Post Report retrieved successfully.');
    }

    public function store(Request $request, $post_id) {
        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError('Post does not exists.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $input['post_id'] = $post_id;
        $input['user_id'] = auth()->user()->id;

        $report = PostReport::create($input);

        return $this->sendResponse(new PostReportResource($report), 'Post Report created successfully');
    }

    public function update(Request $request, $report_id) {
        $report = PostReport::find($report_id);

        if (is_null($report)) {
            return $this->sendError('Post Report does not exists.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'content' => 'required',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $report->content = $input['content'];
        $report->active = $input['active'];
        $report->save();

        return $this->sendResponse(new PostReportResource($report), 'Post Report updated successfully.');
    }

    public function destroy(Request $request, $report_id) {
        $report = PostReport::find($report_id);

        if (is_null($report)) {
            return $this->sendError('Post Report does not exists.');
        }

        $report->delete();

        return $this->sendResponse([], 'Post Report soft-deleted successfully.');
    }
}
