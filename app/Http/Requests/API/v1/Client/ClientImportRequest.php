<?php

namespace App\Http\Requests\API\v1\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientImportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_file' => 'required|file|mimes:xlsx,csv',
        ];
    }

    public function messages()
    {
        return [
            'client_file.required' => 'File is required',
            'client_file.mimes' => 'File type should be xlsx or csv',
        ];
    }
}
