<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize()
    {
        return false;
    }
    
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => ['required','string','regex:/^\+?[1-9]\d{1,14}$/'], // E.164
            'email' => 'nullable|email|max:255',
            'subject' => 'required|string|max:255',
            'body' => 'required|string',
            'files.*' => 'nullable|file|max:10240',
        ];
    }
    
    public function messages()
    {
        return [
            'phone.regex' => 'Phone must be in E.164 format (e.g. +1234567890).',
        ];
    }
}