<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Badge;

class BadgeObserver
{
    /**
     * Handle the Badge "created" event.
     *
     * @param Badge $badge
     * @return void
     */
    public function created(Badge $badge)
    {
        Activity::create([
            'issuer_type' => 4, // 4 => Permissions
            'issuer_id' => $badge->id,
            'short' => 'Badge has been created.',
            'details' => "Badge $badge->name has been created on $badge->created_at.",
            'attributes' => $badge->toJson()
        ]);
    }

    /**
     * Handle the Badge "updated" event.
     *
     * @param Badge $badge
     * @return void
     */
    public function updated(Badge $badge)
    {
        Activity::create([
            'issuer_type' => 4, // 4 => Permissions
            'issuer_id' => $badge->id,
            'short' => 'Badge has been updated.',
            'details' => "Badge $badge->name has been updated on $badge->updated_at.",
            'attributes' => $badge->toJson()
        ]);
    }

    /**
     * Handle the Badge "deleted" event.
     *
     * @param Badge $badge
     * @return void
     */
    public function deleted(Badge $badge)
    {
        Activity::create([
            'issuer_type' => 4, // 4 => Permissions
            'issuer_id' => $badge->id,
            'short' => 'Badge has been soft-deleted.',
            'details' => "Badge $badge->name has been soft-deleted on $badge->deleted_at.",
            'attributes' => $badge->toJson()
        ]);
    }

    /**
     * Handle the Badge "restored" event.
     *
     * @param Badge $badge
     * @return void
     */
    public function restored(Badge $badge)
    {
        Activity::create([
            'issuer_type' => 4, // 4 => Permissions
            'issuer_id' => $badge->id,
            'short' => 'Badge has been restored.',
            'details' => "Badge $badge->name has been restored on $badge->updated_at.",
            'attributes' => $badge->toJson()
        ]);
    }

    /**
     * Handle the Badge "force deleted" event.
     *
     * @param Badge $badge
     * @return void
     */
    public function forceDeleted(Badge $badge)
    {
        Activity::create([
            'issuer_type' => 4, // 4 => Permissions
            'issuer_id' => $badge->id,
            'short' => 'Badge has been force-deleted.',
            'details' => "Badge $badge->name has been force-deleted on $badge->updated_at.",
            'attributes' => $badge->toJson()
        ]);
    }
}
