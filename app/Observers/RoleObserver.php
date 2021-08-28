<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\Role;

class RoleObserver
{
    /**
     * Handle the Role "created" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function created(Role $role)
    {
        Activity::create([
            'issuer_type' => 2, // 2 => Role
            'issuer_id' => $role->id,
            'short' => 'User Role has been created.',
            'details' => "Role $role->name has been created on $role->created_at.",
            'attributes' => $role->toJson()
        ]);
    }

    /**
     * Handle the Role "updated" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function updated(Role $role)
    {
        Activity::create([
            'issuer_type' => 2, // 2 => Role
            'issuer_id' => $role->id,
            'short' => 'User Role has been updated.',
            'details' => "Role $role->name has been updated on $role->updated_at.",
            'attributes' => $role->toJson()
        ]);
    }

    /**
     * Handle the Role "deleted" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function deleted(Role $role)
    {
        Activity::create([
            'issuer_type' => 2, // 2 => Role
            'issuer_id' => $role->id,
            'short' => 'User Role has been soft-deleted.',
            'details' => "Role $role->name has been soft-deleted on $role->deleted_at.",
            'attributes' => $role->toJson()
        ]);
    }

    /**
     * Handle the Role "restored" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function restored(Role $role)
    {
        Activity::create([
            'issuer_type' => 2, // 2 => Role
            'issuer_id' => $role->id,
            'short' => 'User Role has been restored.',
            'details' => "Role $role->name has been restored on $role->updated_at.",
            'attributes' => $role->toJson()
        ]);
    }

    /**
     * Handle the Role "force deleted" event.
     *
     * @param  \App\Models\Role  $role
     * @return void
     */
    public function forceDeleted(Role $role)
    {
        Activity::create([
            'issuer_type' => 2, // 2 => Role
            'issuer_id' => $role->id,
            'short' => 'User Role has been force-deleted.',
            'details' => "Role $role->name has been force-deleted on $role->created_at.",
            'attributes' => $role->toJson()
        ]);
    }
}
