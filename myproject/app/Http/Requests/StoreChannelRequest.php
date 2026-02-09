<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreChannelRequest extends FormRequest
{
    public function authorize() { return true; }
public function rules() {
    return [
        'workspace_id' => 'required|exists:workspaces,_id',
        'name' => 'required|string|alpha_dash', 
        'type' => 'in:public,private'
    ];
}
}
