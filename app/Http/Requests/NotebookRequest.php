<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotebookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'letter_id' => 'required|exists:letters,id',
            'date_sent' => 'required|date',
            'destination_name' => 'required|string|max:255',
            'destination_address' => 'required|string|max:255',
            'description' => 'nullable|string',
        ];
    }
}