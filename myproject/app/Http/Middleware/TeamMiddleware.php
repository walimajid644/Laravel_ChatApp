<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;

class TeamMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $teamId = $request->route('id') ?? $request->route('team');
        $workspaceId = $request->workspace_id; 

        if ($teamId) {
            $team = Team::find($teamId);
            if (!$team) {
                return response()->json(['message' => 'Team not found'], 404);
            }

            $workspace = Workspace::find($team->workspace_id);

            if (!$workspace || !in_array(Auth::id(), $workspace->members ?? [])) {
                return response()->json(['message' => 'You must be a member of the workspace to manage teams.'], 403);
            }
        }
        
        elseif ($workspaceId) {
            $workspace = Workspace::find($workspaceId);
            
            if (!$workspace || !in_array(Auth::id(), $workspace->members ?? [])) {
                return response()->json(['message' => 'You must be a member of the workspace to create a team.'], 403);
            }
        }

        return $next($request);
    }
}