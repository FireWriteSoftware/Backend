<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\BaseController;
use App\Http\Resources\BanCollection;
use App\Http\Resources\Ban as BanResource;
use App\Models\Ban;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BanController extends BaseController
{
    protected $model = Ban::class;
    protected $resource = BanResource::class;
    protected $collection = BanCollection::class;

    protected $validations_create = [
        'target_id' => 'required|integer',
        'reason' => 'required|max:255',
        'description' => 'required',
        'ban_until' => 'nullable|date_format:Y-m-d H:i:s',
        'type' => 'integer|nullable'
    ];

    protected $validations_update = [
        'target_id' => 'integer',
        'reason' => 'string|max:255',
        'description' => 'string',
        'ban_until' => 'nullable|date_format:Y-m-d H:i:s',
        'type' => 'integer|nullable'
    ];

    public function count_bans() {
        # 0 => Global; 1 => Comments; 2 => Posts
        $bans = Ban::all();
        return $this->sendResponse([
            'global' => $bans->where('type', '=', 0)->count(),
            'comments' => $bans->where('type', '=', 1)->count(),
            'posts' => $bans->where('type', '=', 2)->count()
        ],
        'Ban count retrieved successfully');
    }
}
