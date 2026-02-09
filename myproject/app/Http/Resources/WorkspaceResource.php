<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkspaceResource extends JsonResource
{
    public function toArray(Request $request): array
{
    return [
        'id' => $this->_id,
        'name' => $this->name,
        'description' => $this->description,
        'owner_id' => $this->owner_id,
        'members' => \App\Http\Resources\UserResource::collection(
            \App\Models\User::whereIn('_id', $this->members ?? [])->get()
        ),
        'created_at' => $this->created_at,
    ];
}
}