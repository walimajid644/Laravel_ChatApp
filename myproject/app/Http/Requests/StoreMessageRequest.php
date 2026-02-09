<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMessageRequest extends FormRequest
{
    public function authorize() { return true; }
    public function rules() {
        return [
            'channel_id' => 'required|exists:channels,_id',
            'content' => 'nullable|string|required_without:file', 
            'file' => 'nullable|file|max:10240' 
        ];
    }
}
