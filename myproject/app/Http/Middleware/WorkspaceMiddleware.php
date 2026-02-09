<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth; 

class WorkspaceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->route('id') ?? $request->route('workspace');

        if ($id) {
            $workspace = Workspace::find($id);

            if (!$workspace) {
                return response()->json(['error' => 'Workspace not found'], 404);
            }

            if ($workspace->owner_id !== Auth::id()) {
                return response()->json(['error' => 'Unauthorized: You are not the owner'], 403);
            }
        }

        return $next($request);
    }
}