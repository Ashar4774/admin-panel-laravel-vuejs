<?php

namespace App\Http\Requests\API\v1\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceImportRequest extends FormRequest
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
    public function rules()
    {
        return [
            'invoice_file' => 'required|file|mimes:xlsx,csv',
        ];
    }

    public function messages()
    {
        return [
            'invoice_file.required' => 'File is required',
            'invoice_file.mimes' => 'File type should be xlsx or csv',
        ];
    }
}
