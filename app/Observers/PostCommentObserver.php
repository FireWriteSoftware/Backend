<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\PostComment;

class PostCommentObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param PostComment $comment
     * @return void
     */
    public function created(PostComment $comment)
    {
        Activity::create([
            'issuer_type' => 10, // 10 => Post Comments
            'issuer_id' => $comment->id,
            'short' => 'Comment has been created.',
            'details' => "Comment has been created on $comment->created_at.",
            'attributes' => $comment->toJson()
        ]);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param PostComment $comment
     * @return void
     */
    public function updated(PostComment $comment)
    {
        Activity::create([
            'issuer_type' => 10, // 10 => Post Comments
            'issuer_id' => $comment->id,
            'short' => 'Comment has been updated.',
            'details' => "Comment has been updated on $comment->updated_at.",
            'attributes' => $comment->toJson()
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param PostComment $comment
     * @return void
     */
    public function deleted(PostComment $comment)
    {
        Activity::create([
            'issuer_type' => 10, // 10 => Post Comments
            'issuer_id' => $comment->id,
            'short' => 'Comment has been soft-deleted.',
            'details' => "Comment has been soft-deleted on $comment->deleted_at.",
            'attributes' => $comment->toJson()
        ]);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param PostComment $comment
     * @return void
     */
    public function restored(PostComment $comment)
    {
        Activity::create([
            'issuer_type' => 10, // 10 => Post Comments
            'issuer_id' => $comment->id,
            'short' => 'Comment has been restored.',
            'details' => "Comment has been restored on $comment->updated_at.",
            'attributes' => $comment->toJson()
        ]);
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param PostComment $comment
     * @return void
     */
    public function forceDeleted(PostComment $comment)
    {
        Activity::create([
            'issuer_type' => 10, // 10 => Post Comments
            'issuer_id' => $comment->id,
            'short' => 'Comment has been force-deleted.',
            'details' => "Comment has been force-deleted on $comment->created_at.",
            'attributes' => $comment->toJson()
        ]);
    }
}
