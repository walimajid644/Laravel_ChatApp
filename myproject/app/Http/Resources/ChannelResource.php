<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChannelResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->_id,
            'name' => '#' . $this->name, 
            'type' => $this->type,
            'workspace_id' => $this->workspace_id,
            'members_count' => is_array($this->members) ? count($this->members) : 0
        ];
    }
}
