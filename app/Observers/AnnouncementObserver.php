<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Announcement;

class AnnouncementObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param Announcement $announce
     */
    public function created(Announcement $announce)
    {
        Activity::create([
            'issuer_type' => 11, // 11 => Announcement
            'issuer_id' => $announce->id,
            'short' => 'Announce has been created.',
            'details' => "$announce->title [$announce->id] has been announced on $announce->created_at.",
            'attributes' => $announce->toJson()
        ]);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param Announcement $announce
     * @return void
     */
    public function updated(Announcement $announce)
    {
        Activity::create([
            'issuer_type' => 11, // 11 => Announcement
            'issuer_id' => $announce->id,
            'short' => 'Announce has been updated.',
            'details' => "Announcement $announce->id has been updated on $announce->updated_at.",
            'attributes' => $announce->toJson()
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param Announcement $announce
     * @return void
     */
    public function deleted(Announcement $announce)
    {
        Activity::create([
            'issuer_type' => 11, // 11 => Announcement
            'issuer_id' => $announce->id,
            'short' => 'Announcement has been soft-deleted.',
            'details' => "Announcement $announce->title has been soft-deleted on $announce->deleted_at.",
            'attributes' => $announce->toJson()
        ]);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param Announcement $announce
     * @return void
     */
    public function restored(Announcement $announce)
    {
        Activity::create([
            'issuer_type' => 11, // 11 => Announcement
            'issuer_id' => $announce->id,
            'short' => 'Announcement has been restored.',
            'details' => "Announcement $announce->title has been restored on $announce->updated_at.",
            'attributes' => $announce->toJson()
        ]);
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param Announcement $announce
     * @return void
     */
    public function forceDeleted(Announcement $announce)
    {
        Activity::create([
            'issuer_type' => 11, // 11 => Announcement
            'issuer_id' => $announce->id,
            'short' => 'Announce has been force-deleted.',
            'details' => "Announcement $announce->id has been force-deleted on $announce->updated_at.",
            'attributes' => $announce->toJson()
        ]);
    }
}
