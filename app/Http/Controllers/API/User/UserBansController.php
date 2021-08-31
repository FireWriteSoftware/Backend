<?php

namespace App\Http\Controllers\API\User;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Resources\BanCollection;
use App\Models\Ban;
use App\Http\Resources\UserBanCollection;
use App\Http\Resources\Ban as BanResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserBansController extends Controller
{
    public function index(Request $request, $user_id) {
        $per_page = $request->get('per_page', 15);
        $user = User::find($user_id);

        if (is_null($user)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['user_id' => $user_id]);
        }

        return (new UserBanCollection($user->bans->paginate($per_page)))->additional([
            'success' => true,
            'message' => __('base.base.get_all_success')
        ]);
    }

    public function store(Request $request, $user_id) {
        $user = User::find($user_id);

        if (is_null($user)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['user_id' => $user_id]);
        }

        $input = $request->all();

        $validator = Validator::make($input, [
            'reason' => 'required|max:255',
            'description' => 'required',
            'ban_until' => 'required|date_format:Y-m-d H:i:s',
            'type' => 'integer|nullable'
        ]);

        if ($validator->fails()) {
            return $this->sendError(__('validation.validation_error'), ['errors' => $validator->errors()], 400);
        }

        $input['target_id'] = $user_id;
        $input['staff_id'] = auth()->user()->id;
        $ban = Ban::create($input);

        $user->bans()->save($ban);

        return $this->sendResponse(new BanResource($ban), __('base.relation.attached_success'));
    }

    public function show($user_id, $ban_id) {
        $user = User::find($user_id);

        if (is_null($user)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['user_id' => $user_id]);
        }

        foreach ($user->bans as $ban) {
            if ($ban->id == $ban_id) {
                $found_ban = Ban::find($ban_id);

                if (!is_null($found_ban)) {
                    return $this->sendResponse(new BanResource($found_ban), __('base.base.get_success'));
                }
            }
        }

        return $this->sendError(__('base.relation.not_belongs'), ['user_id' => $user_id, 'ban_id' => $ban_id]);
    }

    public function destroy($user_id, $ban_id) {
        $user = User::find($user_id);

        if (is_null($user)) {
            return  $this->sendError(__('base.relation.invalid_parent'), ['user_id' => $user]);
        }

        foreach ($user->bans as $ban) {
            if ($ban->id == $ban_id) {
                $user->bans()->find($ban_id)->delete();
                return $this->sendResponse([], __('base.relation.detached_success'));
            }
        }

        return $this->sendError(__('base.relation.invalid_child'), ['user_id' => $user_id->id, 'ban_id' => $ban_id]);
    }

    public function count_bans($user_id) {
        # 0 => Global; 1 => Comments; 2 => Posts
        $bans = Ban::where('target_id', $user_id)->get();
        return $this->sendResponse([
            'global' => $bans->where('type', '=', 0)->count(),
            'comments' => $bans->where('type', '=', 1)->count(),
            'posts' => $bans->where('type', '=', 2)->count()
        ],
            __('user.ban_count'));
    }

    public function unban(Request $request, User $user) {
        foreach ($user->bans as $ban) {
            if ($ban->is_active()) {
                $ban->ban_until = now();
                $ban->save();
            }
        }

        return $this->sendResponse(['bans' => new BanCollection($user->bans)], __('user.unbanned'));
    }
}
