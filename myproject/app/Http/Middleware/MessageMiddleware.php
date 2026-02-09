<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Message;          
use App\Models\Channel;            
use App\Models\Workspace;          
use Illuminate\Support\Facades\Auth;

class MessageMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id'); 

        if ($id) {
            $message = Message::find($id);
            if (!$message) return response()->json(['error' => 'Message not found'], 404);

            if ($message->user_id !== Auth::id()) {
                return response()->json(['error' => 'You cannot edit this message'], 403);
            }
        }

        return $next($request);
    }
}