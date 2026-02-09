<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
{
    public function authorize() { return true; }
public function rules() {
    return [
        'name' => 'required|string|min:3',
        'members' => 'array', 
        'members.*' => 'exists:users,_id'
    ];
}
}
