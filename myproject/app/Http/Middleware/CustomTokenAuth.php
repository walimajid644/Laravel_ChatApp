<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class CustomTokenAuth
{
    public function handle(Request $request, Closure $next)
    {
        $header = $request->header('Authorization');

        if (!$header) {
            return response()->json(['message' => 'Token missing'], 401);
        }

        $token = str_replace('Bearer ', '', $header);

        $user = User::where('api_token', $token)->first();

        if (!$user) {
            return response()->json(['message' => 'Invalid Token'], 401);
        }

        Auth::login($user);

        return $next($request);
    }
}