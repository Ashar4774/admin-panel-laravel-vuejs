<?php

namespace App\Http\Requests\API\v1\Invoice;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'invoice_year' => 'required|regex:/^\d{2}-\d{2}$/',
            'clients_id' => 'required|exists:clients,id',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'rcd_amount' => 'nullable|required_with:rcd_due_date|numeric|min:0',
            'rcd_due_date' => 'nullable|required_with:rcd_amount|date',
            'notes' => 'nullable|string',
            'status' => 'nullable',
            'payment_type' => 'required_with:rcd_amount',
        ];
    }

    public function messages()
    {
        return [
            'clients_id.required' => 'The client ID is required.',
            'clients_id.exists' => 'The selected client does not exist.',
            'due_date.required' => 'The due date is required.',
            'due_date.date' => 'The due date must be a valid date.',
            'amount.required' => 'The amount is required.',
            'amount.numeric' => 'The amount must be a valid number.',
            'amount.min' => 'The amount must be at least 0.',
            'rcd_amount.required_with' => 'The received amount is required when a received due date is entered.',
            'rcd_due_date.required_with' => 'The received due date is required when a received amount is entered.',
            'rcd_due_date.date' => 'The received due date must be a valid date.',
            'rcd_amount.numeric' => 'The received amount must be a valid number.',
            'rcd_amount.min' => 'The received amount must be at least 0.',
            'invoice_year.required' => 'The invoice year is required.',
            'invoice_year.regex' => 'The invoice year must be in the format "XX-XX".',
            'time_gap.integer' => 'The time gap must be a whole number.',
            'time_gap.min' => 'The time gap must be at least 0.',
//            'status.required' => 'The status is required.',
//            'status.in' => 'The status must be either "bad debts" or "good".',
            'payment_type.required_with' => 'The payment type is required if received amount is entered.',
        ];
    }
}
