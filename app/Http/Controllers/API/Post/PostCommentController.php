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
        return (new PostCommentCollection(PostComment::paginate($per_page)))->additional([
            'success' => true,
            'message' => 'Successfully retrieved comments'
        ]);
    }

    public function post_comments(Request $request, $post_id)
    {
        $post = Post::find($post_id);

        if (is_null($post)) {
            return $this->sendError('Post does not exists.');
        }

        $per_page = $request->get('per_page', 15);
        return (new PostCommentCollection(PostComment::where('post_id', $post->id)->paginate($per_page)))->additional([
            'success' => true,
            'message' => 'Successfully retrieved post comments'
        ]);
    }

    public function show($id) {
        $comment = PostComment::find($id);

        if (is_null($comment)) {
            return $this->sendError('Post Comment does not exists.');
        }

        return $this->sendResponse(new PostCommentResource($comment), 'Post Comment retrieved successfully.');
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
                'message' => "User has comment ban",
            ], 403);
        }

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

        if (!auth()->user()->email_verified_at) {
            return $this->sendError('Not permitted.', ['errors' => ['You need a verified email address to write a comment']], 403);
        }

        $input['post_id'] = $post_id;
        $input['user_id'] = auth()->user()->id;

        $comment = PostComment::create($input);
        return $this->sendResponse(new PostCommentResource($comment), 'Post Comment created successfully');
    }

    public function update(Request $request, $comment_id) {
        $comment = PostComment::find($comment_id);

        if (is_null($comment)) {
            return $this->sendError('Post Comment does not exists.');
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $comment->content = $input['content'];
        $comment->save();

        return $this->sendResponse(new PostCommentResource($comment), 'Post Comment updated successfully.');
    }

    public function destroy($comment_id) {
        $comment = PostComment::find($comment_id);

        if (is_null($comment)) {
            return $this->sendError('Post Comment does not exists.');
        }

        $comment->delete();

        return $this->sendResponse([], 'Post Comment soft-deleted successfully.');
    }
}
