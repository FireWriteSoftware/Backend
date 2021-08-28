<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Permission;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function created(Permission $permission)
    {
        Activity::create([
            'issuer_type' => 3, // 3 => Permissions
            'issuer_id' => $permission->id,
            'short' => 'Permission has been created.',
            'details' => "Permission $permission->name has been created on $permission->created_at.",
            'attributes' => $permission->toJson()
        ]);
    }

    /**
     * Handle the Permission "updated" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function updated(Permission $permission)
    {
        Activity::create([
            'issuer_type' => 3, // 3 => Permissions
            'issuer_id' => $permission->id,
            'short' => 'Permission has been updated.',
            'details' => "Permission $permission->name has been updated on $permission->updated_at.",
            'attributes' => $permission->toJson()
        ]);
    }

    /**
     * Handle the Permission "deleted" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function deleted(Permission $permission)
    {
        Activity::create([
            'issuer_type' => 3, // 3 => Permissions
            'issuer_id' => $permission->id,
            'short' => 'Permission has been soft-deleted.',
            'details' => "Permission $permission->name has been soft-deleted on $permission->deleted_at.",
            'attributes' => $permission->toJson()
        ]);
    }

    /**
     * Handle the Permission "restored" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function restored(Permission $permission)
    {
        Activity::create([
            'issuer_type' => 3, // 3 => Permissions
            'issuer_id' => $permission->id,
            'short' => 'Permission has been restored.',
            'details' => "Permission $permission->name has been restored on $permission->updated_at.",
            'attributes' => $permission->toJson()
        ]);
    }

    /**
     * Handle the Permission "force deleted" event.
     *
     * @param Permission $permission
     * @return void
     */
    public function forceDeleted(Permission $permission)
    {
        Activity::create([
            'issuer_type' => 3, // 3 => Permissions
            'issuer_id' => $permission->id,
            'short' => 'Permission has been force-deleted.',
            'details' => "Permission $permission->name has been force-deleted on $permission->updated_at.",
            'attributes' => $permission->toJson()
        ]);
    }
}
