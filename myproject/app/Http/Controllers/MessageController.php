<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreMessageRequest;
use App\Http\Resources\MessageResource;
use App\Http\Requests\UpdateMessageRequest;
use Illuminate\Support\Facades\Storage; 

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('message.owner')->only(['update', 'destroy']);
    }


public function store(Request $request) 
{
    // 1. Validation
    // We remove 'required' from content because a message might be JUST an image
    $request->validate([
        'channel_id' => 'required|exists:channels,_id',
        'content' => 'nullable|string',
        'attachment' => 'nullable|file|max:10240' // Max 10MB file
    ]);

    if (!$request->content && !$request->hasFile('attachment')) {
        return response()->json(['message' => 'Message must contain text or a file.'], 422);
    }

    $attachmentPath = null;
    $attachmentName = null;

    // 2. Handle File Upload (GridFS)
    if ($request->hasFile('attachment')) {
        $file = $request->file('attachment');
        $attachmentName = $file->getClientOriginalName();
        
        // This stores the file in MongoDB 'fs' bucket and returns the ID/Path
        $attachmentPath = $file->storeAs(
            'attachments', 
            time() . '_' . $attachmentName, 
            'gridfs' 
        );
    }

    // 3. Save Message to DB
    $message = Message::create([
        'user_id' => Auth::id(),
        'channel_id' => $request->channel_id,
        'content' => $request->content,
        'attachment_path' => $attachmentPath, // Store reference ID
        'attachment_name' => $attachmentName
    ]);

    return new MessageResource($message);
}
        public function index(Request $request) {
        $request->validate(['channel_id' => 'required']);

        $messages = Message::where('channel_id', $request->channel_id)->get();
        
        return MessageResource::collection($messages);
    }

    public function update(UpdateMessageRequest $request, $id) {
        $message = Message::find($id);
        
        $message->update($request->validated());
        
        return new MessageResource($message);
    }

    public function destroy($id) {
        $message = Message::find($id);
        $message->delete();
        
        return response()->json(['message' => 'Message deleted successfully']);
    }
}