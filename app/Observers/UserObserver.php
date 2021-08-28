<?php

namespace App\Observers;

use App\Models\Activity;
use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param User $user
     * @return void
     */
    public function created(User $user)
    {
        Activity::create([
            'issuer_type' => 1, // 1 => User
            'issuer_id' => $user->id,
            'short' => 'User Account has been created.',
            'details' => "User $user->name has been created on $user->created_at.",
            'attributes' => $user->toJson()
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param User $user
     * @return void
     */
    public function updated(User $user)
    {
        Activity::create([
            'issuer_type' => 1, // 1 => User
            'issuer_id' => $user->id,
            'short' => 'User Account has been updated.',
            'details' => "User $user->name has been updated on $user->updated_at.",
            'attributes' => $user->toJson()
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function deleted(User $user)
    {
        Activity::create([
            'issuer_type' => 1, // 1 => User
            'issuer_id' => $user->id,
            'short' => 'User Account has been soft-deleted.',
            'details' => "User $user->name has been soft-deleted on $user->deleted_at.",
            'attributes' => $user->toJson()
        ]);
    }

    /**
     * Handle the User "restored" event.
     *
     * @param User $user
     * @return void
     */
    public function restored(User $user)
    {
        Activity::create([
            'issuer_type' => 1, // 1 => User
            'issuer_id' => $user->id,
            'short' => 'User Account has been restored.',
            'details' => "User $user->name has been restored on $user->updated_at.",
            'attributes' => $user->toJson()
        ]);
    }

    /**
     * Handle the User "force deleted" event.
     *
     * @param User $user
     * @return void
     */
    public function forceDeleted(User $user)
    {
        Activity::create([
            'issuer_type' => 1, // 1 => User
            'issuer_id' => $user->id,
            'short' => 'User Account has been force-deleted.',
            'details' => "User $user->name has been force-deleted on $user->updated_at.",
            'attributes' => $user->toJson()
        ]);
    }
}
