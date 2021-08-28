<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\PostVote;

class PostVoteObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param PostVote $vote
     * @return void
     */
    public function created(PostVote $vote)
    {
        Activity::create([
            'issuer_type' => 8, // 8 => Post Votes
            'issuer_id' => $vote->id,
            'short' => 'Vote has been created.',
            'details' => "Vote has been created on $vote->created_at.",
            'attributes' => $vote->toJson()
        ]);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param PostVote $vote
     * @return void
     */
    public function updated(PostVote $vote)
    {
        Activity::create([
            'issuer_type' => 8, // 8 => Post Votes
            'issuer_id' => $vote->id,
            'short' => 'Vote has been updated.',
            'details' => "Vote has been updated on $vote->updated_at.",
            'attributes' => $vote->toJson()
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param PostVote $vote
     * @return void
     */
    public function deleted(PostVote $vote)
    {
        Activity::create([
            'issuer_type' => 8, // 8 => Post Votes
            'issuer_id' => $vote->id,
            'short' => 'Vote has been soft-deleted.',
            'details' => "Vote has been soft-deleted on $vote->deleted_at.",
            'attributes' => $vote->toJson()
        ]);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param PostVote $vote
     * @return void
     */
    public function restored(PostVote $vote)
    {
        Activity::create([
            'issuer_type' => 8, // 8 => Post Votes
            'issuer_id' => $vote->id,
            'short' => 'Vote has been restored.',
            'details' => "Vote has been restored on $vote->updated_at.",
            'attributes' => $vote->toJson()
        ]);
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param PostVote $vote
     * @return void
     */
    public function forceDeleted(PostVote $vote)
    {
        Activity::create([
            'issuer_type' => 8, // 8 => Post Votes
            'issuer_id' => $vote->id,
            'short' => 'Vote has been force-deleted.',
            'details' => "Vote has been force-deleted on $vote->created_at.",
            'attributes' => $vote->toJson()
        ]);
    }
}
