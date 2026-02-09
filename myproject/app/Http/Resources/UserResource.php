<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray($request) {
        return [
            'id' => $this->_id, 
            'name' => $this->name,
            'email' => $this->email,
            'token' => $this->when($this->api_token, $this->api_token), 
            'joined' => $this->created_at->format('Y-m-d'),
        ];
    }
}
