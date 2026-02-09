<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Workspace;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;

class TeamController extends Controller
{
    public function __construct()
    {
        $this->middleware('team.member')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        $query = Team::query();

        if ($request->has('workspace_id')) {
            $query->where('workspace_id', $request->workspace_id);
        }

        return TeamResource::collection($query->get());
    }

    public function store(StoreTeamRequest $request)
    {
        $team = Team::create([
            'name' => $request->name,
            'description' => $request->description,
            'workspace_id' => $request->workspace_id,
            'members' => [Auth::id()] 
        ]);

        return new TeamResource($team);
    }

    public function show($id)
    {
        $team = Team::find($id);
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }
        return new TeamResource($team);
    }

    public function update(UpdateTeamRequest $request, $id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->update($request->validated());

        return new TeamResource($team);
    }

    public function destroy($id)
    {
        $team = Team::find($id);
        
        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->delete();
        return response()->json(['message' => 'Team deleted successfully']);
    }
}