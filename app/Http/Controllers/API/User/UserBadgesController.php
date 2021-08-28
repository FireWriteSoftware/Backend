<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\RelationController;
use App\Http\Resources\UserBadgeCollection;
use \App\Http\Resources\UserBadge as UserBadgeResource;
use App\Models\Badge;
use App\Models\User;

class UserBadgesController extends RelationController
{
    protected $parentModel = User::class;
    protected $childModel = Badge::class;

    protected $resource = UserBadgeResource::class;

    protected $collection = UserBadgeCollection::class;
}
