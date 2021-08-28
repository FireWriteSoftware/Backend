<?php

namespace App\Http\Middleware;

use App\Models\Activity;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        $response = [
            'success' => false,
            'data'    => [],
            'message' => "Not authenticated",
        ];

        Activity::create([
            'issuer_type' => 0, // 0 => Unknown/Undefined
            'issuer_id' => 1,
            'short' => 'Unauthenticated access',
            'details' => "IP " . $request->ip() . " tried to access on " . url()->full() . " without authentication.",
            'attributes' => $request->json() ?? '{}'
        ]);

        return response()->json($response, 401);
    }
}
