<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BadgeCollection;
use App\Models\Badge;
use App\Http\Resources\Badge as BadgeResource;

class BadgeController extends BaseController
{
    protected $model = Badge::class;
    protected $resource = BadgeResource::class;
    protected $collection = BadgeCollection::class;

    protected $validations_create = [
        'title' => 'required|max:255',
        'description' => '',
        'icon' => 'required',
        'color' => 'required|regex:^(?:[0-9a-fA-F]{3}){1,2}$^',
        'is_role_badge' => 'boolean',
        'role_id' => 'required_if:is_role_badge,true'
    ];

    protected $validations_update = [
        'title' => 'required|max:255',
        'description' => '',
        'icon' => 'required',
        'color' => 'required|regex:^(?:[0-9a-fA-F]{3}){1,2}$^',
        'is_role_badge' => 'boolean',
        'role_id' => 'required_if:is_role_badge,true'
    ];
}
