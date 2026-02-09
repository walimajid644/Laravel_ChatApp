<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreChannelRequest;
use App\Http\Resources\ChannelResource;
use App\Http\Requests\UpdateChannelRequest;

class ChannelController extends Controller
{
    public function __construct()
    {
        $this->middleware('channel.access')->only(['show', 'update', 'destroy', 'addMember', 'removeMember']);
    }

    public function store(StoreChannelRequest $request) {
        
        $channel = Channel::create([
            'name' => $request->name,
            'workspace_id' => $request->workspace_id,
            'type' => $request->type,
            'members' => [Auth::id()] 
        ]);

        return new ChannelResource($channel);
    }

    public function index(Request $request) {
        $workspaceId = $request->query('workspace_id');
        
        $channels = Channel::where('workspace_id', $workspaceId)
            ->where(function($query) {
                $query->where('type', 'public')
                      ->orWhere('members', Auth::id());
            })
            ->get();

        return ChannelResource::collection($channels);
    }

    public function show($id) {
        $channel = Channel::find($id);
        if (!$channel) return response()->json(['error' => 'Channel not found'], 404);

        return new ChannelResource($channel);
    }

    public function update(UpdateChannelRequest $request, $id) {
        $channel = Channel::find($id);
        
        $channel->update($request->validated());
        
        return new ChannelResource($channel);
    }

    public function destroy($id) {
        $channel = Channel::find($id);
        $channel->delete();
        
        return response()->json(['message' => 'Channel deleted successfully']);
    }

    public function addMember(Request $request, $id) {
        $channel = Channel::find($id);
        if (!$channel) return response()->json(['error' => 'Not found'], 404);

        $newMemberId = $request->user_id ?? Auth::id(); 
        
        if (in_array($newMemberId, $channel->members ?? [])) {
             return response()->json(['message' => 'User already in channel']);
        }

        $channel->push('members', $newMemberId);
        
        return response()->json([
            'message' => 'Member added', 
            'channel' => new ChannelResource($channel)
        ]);
    }

    public function removeMember(Request $request, $id) {
        $channel = Channel::find($id);
        if (!$channel) return response()->json(['error' => 'Not found'], 404);

        $userIdToRemove = $request->user_id ?? Auth::id();

        $channel->pull('members', $userIdToRemove);
        
        return response()->json(['message' => 'User removed from channel']);
    }
}