<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLetterRequest extends FormRequest
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
        // Retrieve the ID from the route parameter to use it in the unique validation rule
        $id = $this->route('letter');

        return [
            'title' => 'required|string|max:255',
            'about' => 'required|string',
            'purpose' => 'required|string',
            'description' => 'nullable|string',
            // 'letter_code' => 'nullable|string|unique:letter_files,letter_code,' . $id,
            // 'validators' => 'required|array',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:5000',
        ];
    }
}