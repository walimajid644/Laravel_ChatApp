<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->_id,
            'name' => $this->name,
            'workspace_id' => $this->workspace_id,
            'members' => \App\Http\Resources\UserResource::collection(
                \App\Models\User::whereIn('_id', $this->members ?? [])->get()
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
