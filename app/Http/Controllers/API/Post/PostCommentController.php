<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Models\PostComment;
use App\Http\Resources\PostCommentCollection;
use App\Http\Resources\PostComment as PostCommentResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Validator;

class PostCommentController extends Controller
{
    public function index(Request $request)
    {
        $per_page = $request->get('per_page', 15);
        $data = PostComment::orderByDesc('updated_at')->paginate($per_page);

        return (new PostCommentCollection($data))->additional([
            'success' => true,
            'message' => __('base.base.get_all_success')
        ]);
    }

    public function post_comments(Request $request, $post_id)
    {
        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $per_page = $request->get('per_page', 15);
        return (new PostCommentCollection(PostComment::where('post_id', $post->id)->paginate($per_page)))->additional([
            'success' => true,
            'message' => __('base.base.get_all_success')
        ]);
    }

    public function show($id) {
        $comment = PostComment::find($id);

        if (is_null($comment)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        return $this->sendResponse(new PostCommentResource($comment), __('base.base.get_success'));
    }

    public function store(Request $request, $post_id): JsonResponse
    {
        $active_bans = $request->user()->bans()->where(['type' => 1])->get()->filter(function ($b) {
            if ($b->is_active()) {
                return $b;
            }
        });

        if (sizeof($active_bans) > 0) {
            return response()->json([
                'success' => false,
                'data'    => [
                    'banned' => true,
                    'bans' => $active_bans
                ],
                'message' => __('ban.is_comment'),
            ], 403);
        }

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

        if (!auth()->user()->email_verified_at) {
            return $this->sendError(__('ban.is_comment'), ['errors' => [__('ban.comment_ban_reason')]], 403);
        }

        $input['post_id'] = $post_id;
        $input['user_id'] = auth()->user()->id;

        $comment = PostComment::create($input);
        return $this->sendResponse(new PostCommentResource($comment), __('base.base.store_success'));
    }

    public function update(Request $request, $comment_id) {
        $comment = PostComment::find($comment_id);

        if (is_null($comment)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $comment->content = $input['content'];
        $comment->save();

        return $this->sendResponse(new PostCommentResource($comment), __('base.base.update_success'));
    }

    public function destroy($comment_id) {
        $comment = PostComment::find($comment_id);

        if (is_null($comment)) {
            return $this->sendError(__('base.base.get_not_found'));
        }

        $comment->delete();

        return $this->sendResponse([], __('base.base.soft_delete_success'));
    }
}
