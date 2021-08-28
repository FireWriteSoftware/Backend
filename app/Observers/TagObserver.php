<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Tag;

class TagObserver
{
    /**
     * Handle the Badge "created" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function created(Tag $tag)
    {
        Activity::create([
            'issuer_type' => 9, // 9 => Tags
            'issuer_id' => $tag->id,
            'short' => 'Tag has been created.',
            'details' => "Tag $tag->name has been created on $tag->created_at.",
            'attributes' => $tag->toJson()
        ]);
    }

    /**
     * Handle the Badge "updated" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function updated(Tag $tag)
    {
        Activity::create([
            'issuer_type' => 9, // 9 => Tags
            'issuer_id' => $tag->id,
            'short' => 'Tag has been updated.',
            'details' => "Tag $tag->name has been updated on $tag->updated_at.",
            'attributes' => $tag->toJson()
        ]);
    }

    /**
     * Handle the Badge "deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function deleted(Tag $tag)
    {
        Activity::create([
            'issuer_type' => 9, // 9 => Tags
            'issuer_id' => $tag->id,
            'short' => 'Tag has been soft-deleted.',
            'details' => "Tag $tag->name has been soft-deleted on $tag->deleted_at.",
            'attributes' => $tag->toJson()
        ]);
    }

    /**
     * Handle the Badge "restored" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function restored(Tag $tag)
    {
        Activity::create([
            'issuer_type' => 9, // 9 => Tags
            'issuer_id' => $tag->id,
            'short' => 'Tag has been restored.',
            'details' => "Tag $tag->name has been restored on $tag->updated_at.",
            'attributes' => $tag->toJson()
        ]);
    }

    /**
     * Handle the Badge "force deleted" event.
     *
     * @param Tag $tag
     * @return void
     */
    public function forceDeleted(Tag $tag)
    {
        Activity::create([
            'issuer_type' => 9, // 9 => Tags
            'issuer_id' => $tag->id,
            'short' => 'Tag has been force-deleted.',
            'details' => "Tag $tag->name has been force-deleted on $tag->updated_at.",
            'attributes' => $tag->toJson()
        ]);
    }
}
