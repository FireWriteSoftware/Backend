<?php

namespace App\Http\Middleware;

use App\Models\Activity;
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

        foreach ($permissions_exploded as $permission) {
            if (!$request->user()->hasPermission($permission)) {
                $response = [
                    'success' => false,
                    'data'    => $permission,
                    'message' => "Missing permission to access.",
                ];

                Activity::create([
                    'issuer_type' => 0, // 0 => Unknown/Undefined
                    'issuer_id' => 1,
                    'short' => 'Unauthenticated access',
                    'details' => $request->user()->name . " [" . $request->user()->id . "] tried to access on " . url()->full() . " without permissions.",
                    'attributes' => '{}'
                ]);

                return response()->json($response, 401);
            }
        }

        return $next($request);
    }
}
