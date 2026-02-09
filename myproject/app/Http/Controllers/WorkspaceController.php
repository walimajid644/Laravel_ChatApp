<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use App\Models\Workspace;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest; 
use App\Http\Resources\WorkspaceResource;

class WorkspaceController extends Controller
{
    public function __construct()
    {
        $this->middleware('workspace.owner')->only(['update', 'destroy']);
    }

    public function store(StoreWorkspaceRequest $request) {
        $workspace = Workspace::create([
            'name' => $request->name,
            'description' => $request->description,
            'owner_id' => Auth::id(), 
            'members' => [Auth::id()]
        ]);

        return new WorkspaceResource($workspace);
    }

    public function index(Request $request) {
        // Show workspaces where the user is a member
        $workspaces = Workspace::whereIn('members', [Auth::id()])->get();
        return WorkspaceResource::collection($workspaces);
    }

    public function show($id) {
        $workspace = Workspace::find($id);
        if (!$workspace) return response()->json(['message' => 'Workspace not found'], 404);
        
        return new WorkspaceResource($workspace);
    }

    public function update(UpdateWorkspaceRequest $request, $id) {
        
        $workspace = Workspace::find($id);
        
        $workspace->update($request->validated());
        
        return new WorkspaceResource($workspace);
    }

    public function destroy($id) {
        $workspace = Workspace::find($id);
        $workspace->delete();
        return response()->json(['message' => 'Workspace deleted successfully']);
    }
}