<?php

namespace App\Http\Requests\API\v1\Client;

use Illuminate\Foundation\Http\FormRequest;

class ClientRequest extends FormRequest
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
            'ref_no'       => 'required|unique:clients,ref_no|max:255',
            'client_name'  => 'required',
        ];
    }

    public function messages()
    {
        return [
            'ref_no.required' => 'Ref. # is required',
            'ref_no.unique' => 'Ref. # should be unique',
            'client_name.required' => 'Name is required',
        ];
    }
}
