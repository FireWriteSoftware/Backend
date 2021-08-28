<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckBan
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user()) {
            return $next($request);
        }

        $active_bans = $request->user()->bans()->where(['type' => 0])->get()->filter(function ($b) {
            if ($b->is_active()) {
                return $b;
            }
        });

        if (sizeof($active_bans) > 0) {
            return response()->json([
                'success' => false,
                'data'    => [
                    'banned' => true,
                    'bans' => $active_bans
                ],
                'message' => "User has global ban",
            ], 403);
        }

        return $next($request);
    }
}
