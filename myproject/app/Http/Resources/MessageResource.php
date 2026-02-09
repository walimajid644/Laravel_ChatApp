<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->_id,
            'content' => $this->content,
            'sender' => new UserResource($this->user),
            'attachment' => $this->attachment_path ? [
                'name' => $this->attachment_name,
                // Create a URL to download the file
                'url' => url('/api/files/' . $this->attachment_path) 
            ] : null,
            'sent_at' => $this->created_at,
        ];
    }
}
