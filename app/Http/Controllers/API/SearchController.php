<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    public function search(Request $request) {
        $validator = Validator::make($request->all(), [
            "keywords" => "required|string"
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', ['errors' => $validator->errors()], 400);
        }

        $exact_term = $request->get('keywords');
        $term_list = explode(' ', $exact_term);

        $posts = Post::query();
        $cats = Category::query();
        $users = User::query();
        $tags = Tag::query();

        foreach ($term_list as $word) {
            $posts
                ->where('title', 'LIKE', "%$word%")
                ->orWhere('content', 'LIKE', "%$word%");

            $cats
                ->where('title', 'LIKE', "%$word%")
                ->orWhere('description', 'LIKE', "%$word%");

            $users
                ->where('name', 'LIKE', "%$word%")
                ->orWhere('pre_name', 'LIKE', "%$word%")
                ->orWhere('last_name', 'LIKE', "%$word%");

            $tags
                ->where('name', 'LIKE', "%$word%")
                ->orWhere('description', 'LIKE', "%$word%");
        }

        $tag_posts = [];
        foreach ($tags->distinct()->get() as $tag) {
            foreach($tag->posts as $post_rel) {
                $tag_posts[] = $post_rel->post;
            }
        }

        $query = [
            "posts" => $posts->distinct()->get(),
            "cats" => $cats->distinct()->get(),
            "users" => $users->distinct()->get(),
            "tags" => $tag_posts
        ];

        return $this->sendResponse($query, 'Successfully searched data');
    }
}
