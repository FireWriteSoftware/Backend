<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Post;

class PostObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param Post $post
     * @return void
     */
    public function created(Post $post)
    {
        Activity::create([
            'issuer_type' => 7, // 7 => Post
            'issuer_id' => $post->id,
            'short' => 'Post has been created.',
            'details' => "Post \"$post->title\" has been created on $post->created_at.",
            'attributes' => $post->toJson()
        ]);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param Post $post
     * @return void
     */
    public function updated(Post $post)
    {
        Activity::create([
            'issuer_type' => 7, // 7 => Post
            'issuer_id' => $post->id,
            'short' => 'Post has been updated.',
            'details' => "Post \"$post->title\" has been updated on $post->updated_at.",
            'attributes' => $post->toJson()
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param Post $post
     * @return void
     */
    public function deleted(Post $post)
    {
        Activity::create([
            'issuer_type' => 7, // 7 => Post
            'issuer_id' => $post->id,
            'short' => 'Post has been soft-deleted.',
            'details' => "Post \"$post->title\" has been soft-deleted on $post->deleted_at.",
            'attributes' => $post->toJson()
        ]);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param Post $post
     * @return void
     */
    public function restored(Post $post)
    {
        Activity::create([
            'issuer_type' => 7, // 7 => Post
            'issuer_id' => $post->id,
            'short' => 'Post has been restored.',
            'details' => "Post \"$post->title\" has been restored on $post->updated_at.",
            'attributes' => $post->toJson()
        ]);
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param Post $post
     * @return void
     */
    public function forceDeleted(Post $post)
    {
        Activity::create([
            'issuer_type' => 7, // 7 => Post
            'issuer_id' => $post->id,
            'short' => 'Post has been force-deleted.',
            'details' => "Post \"$post->title\" has been force-deleted on $post->created_at.",
            'attributes' => $post->toJson()
        ]);
    }
}
