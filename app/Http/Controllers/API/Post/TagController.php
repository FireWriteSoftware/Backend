<?php

namespace App\Http\Controllers\API\Post;

use App\Http\Controllers\BaseController;
use App\Http\Resources\TagCollection;
use App\Models\Tag;
use App\Http\Resources\Tag as TagResource;

class TagController extends BaseController
{
    protected $model = Tag::class;
    protected $resource = TagResource::class;
    protected $collection = TagCollection::class;

    protected $validations_create = [
        'name' => 'required|max:255',
        'description' => '',
        'color' => 'required|regex:^(?:[0-9a-fA-F]{3}){1,2}$^',
        'icon' => 'required'
    ];

    protected $validations_update = [
        'name' => 'string|max:255',
        'description' => 'string',
        'color' => 'string|regex:^(?:[0-9a-fA-F]{3}){1,2}$^',
        'icon' => 'string'
    ];
}
