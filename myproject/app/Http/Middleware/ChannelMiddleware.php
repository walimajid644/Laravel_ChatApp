<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Channel;            
use App\Models\Workspace;          
use Illuminate\Support\Facades\Auth;

class ChannelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id'); 

        if ($id) {
            $channel = Channel::find($id);
            if (!$channel) return response()->json(['error' => 'Channel not found'], 404);

            if ($channel->type === 'private') {
                
                if (!in_array(Auth::id(), $channel->members ?? [])) {
                    return response()->json(['error' => 'Access Denied to Private Channel'], 403);
                }
            }
        }

        return $next($request);
    }
}
