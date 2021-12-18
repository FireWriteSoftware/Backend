<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use App\Models\Role;
use Closure;
use Illuminate\Http\Request;

class RolesAuth
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  Closure  $next
     * @return mixed
     */

    public function handle(Request $request, Closure $next, string $permissions)
    {
        $permissions_exploded = explode('|', $permissions);
        $target = Role::where('is_guest', true)->first();

        if (!$target) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Guest role not found.'
            ]);
        }

        if ($request->user()) {
            $target = $request->user();
        }

        foreach ($permissions_exploded as $permission) {
            if (!$target->hasPermission($permission)) {
                $response = [
                    'success' => false,
                    'data'    => $permission,
                    'message' => __('permission.no_permission', [
                        'permission' => $permission
                    ]),
                ];

                Activity::create([
                    'issuer_type' => 0, // 0 => Unknown/Undefined
                    'issuer_id' => 1,
                    'short' => 'Missing permission',
                    'details' => $target->name . " [" . $target->id . "] tried to access on " . url()->full() . " without permissions.",
                    'attributes' => '{}'
                ]);

                return response()->json($response, 401);
            }
        }

        return $next($request);
    }
}
