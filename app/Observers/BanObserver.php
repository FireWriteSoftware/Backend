<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Ban;

class BanObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param Ban $ban
     */
    public function created(Ban $ban)
    {
        Activity::create([
            'issuer_type' => 5, // 5 => Ban
            'issuer_id' => $ban->id,
            'short' => 'Ban has been created.',
            'details' => "{$ban->target->name} [{$ban->target->id}] has been banned on $ban->created_at.",
            'attributes' => $ban->toJson()
        ]);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param Ban $ban
     * @return void
     */
    public function updated(Ban $ban)
    {
        Activity::create([
            'issuer_type' => 5, // 5 => Ban
            'issuer_id' => $ban->id,
            'short' => 'Ban has been updated.',
            'details' => "Ban $ban->id has been updated on $ban->updated_at.",
            'attributes' => $ban->toJson()
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param Ban $ban
     * @return void
     */
    public function deleted(Ban $ban)
    {
        Activity::create([
            'issuer_type' => 5, // 5 => Ban
            'issuer_id' => $ban->id,
            'short' => 'User Role has been soft-deleted.',
            'details' => "Ban $ban->name has been soft-deleted on $ban->deleted_at.",
            'attributes' => $ban->toJson()
        ]);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param Ban $ban
     * @return void
     */
    public function restored(Ban $ban)
    {
        Activity::create([
            'issuer_type' => 5, // 5 => Ban
            'issuer_id' => $ban->id,
            'short' => 'User Role has been restored.',
            'details' => "Ban $ban->name has been restored on $ban->updated_at.",
            'attributes' => $ban->toJson()
        ]);
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param Ban $ban
     * @return void
     */
    public function forceDeleted(Ban $ban)
    {
        Activity::create([
            'issuer_type' => 5, // 5 => Ban
            'issuer_id' => $ban->id,
            'short' => 'Ban has been force-deleted.',
            'details' => "Ban $ban->id has been force-deleted on $ban->updated_at.",
            'attributes' => $ban->toJson()
        ]);
    }
}
