<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Notification;

class NotificationObserver
{
    /**
     * Handle the Notification "created" event.
     *
     * @param Notification $notify
     * @return void
     */
    public function created(Notification $notify)
    {
        Activity::create([
            'issuer_type' => 13, // 13 => Notification
            'issuer_id' => $notify->id,
            'short' => 'Notification has been created.',
            'details' => "Notification $notify->name has been created on $notify->created_at.",
            'attributes' => $notify->toJson()
        ]);
    }

    /**
     * Handle the Notification "updated" event.
     *
     * @param Notification $notify
     * @return void
     */
    public function updated(Notification $notify)
    {
        Activity::create([
            'issuer_type' => 13, // 13 => Notification
            'issuer_id' => $notify->id,
            'short' => 'Notification has been updated.',
            'details' => "Notification $notify->name has been updated on $notify->updated_at.",
            'attributes' => $notify->toJson()
        ]);
    }

    /**
     * Handle the Notification "deleted" event.
     *
     * @param Notification $notify
     * @return void
     */
    public function deleted(Notification $notify)
    {
        Activity::create([
            'issuer_type' => 13, // 13 => Notification
            'issuer_id' => $notify->id,
            'short' => 'Notification has been soft-deleted.',
            'details' => "Notification $notify->name has been soft-deleted on $notify->deleted_at.",
            'attributes' => $notify->toJson()
        ]);
    }

    /**
     * Handle the Notification "restored" event.
     *
     * @param Notification $notify
     * @return void
     */
    public function restored(Notification $notify)
    {
        Activity::create([
            'issuer_type' => 13, // 13 => Notification
            'issuer_id' => $notify->id,
            'short' => 'Notification has been restored.',
            'details' => "Notification $notify->name has been restored on $notify->updated_at.",
            'attributes' => $notify->toJson()
        ]);
    }

    /**
     * Handle the Notification "force deleted" event.
     *
     * @param Notification $notify
     * @return void
     */
    public function forceDeleted(Notification $notify)
    {
        Activity::create([
            'issuer_type' => 13, // 13 => Notification
            'issuer_id' => $notify->id,
            'short' => 'Notification has been force-deleted.',
            'details' => "Notification $notify->name has been force-deleted on $notify->created_at.",
            'attributes' => $notify->toJson()
        ]);
    }
}
