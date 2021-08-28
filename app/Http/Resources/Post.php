<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Post extends JsonResource
{
    private $detailed;

    public function __construct($resource, $detailed=false)
    {
        parent::__construct($resource);
        $this->detailed = $detailed;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vote = \App\Models\PostVote::where([
            ['user_id', auth()->user()->id],
            ['post_id', $this->id]
        ])->get();

        $liked = sizeof($vote) > 0 ? $vote[0]["vote"] : 0;

        $data = [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'user' => new User($this->user),
            'thumbnail' => $this->thumbnail,
            'tags' => new TagCollection($this->tags),
            'parent' => $this->category,
            'histories_count' => sizeof($this->histories),
            'like_votes_count' => $this->votes->where('vote', 1)->count(),
            'dislike_votes_count' => $this->votes->where('vote', 2)->count(),
            'comments_count' => $this->comments->count(),
            'liked' => $liked,
            'is_bookmarked' => $this->is_bookmarked(),
            'created_at' => $this->created_at->format('Y-m-d h:m:i'),
            'updated_at' => $this->updated_at->format('Y-m-d h:m:i')
        ];

        if ($this->approved_at != null) {
            $data['approved_at'] = $this->approved_at->format('Y-m-d h:m:i');
            $data['approved_by'] = $this->approved_user;
        }

        if ($this->detailed) {
            $data['histories'] = new PostHistoryCollection($this->histories);
            $data['votes'] = new PostVoteCollection($this->votes);
            $data['comments'] = new PostCommentCollection($this->comments);
        }

        return $data;
    }
}
