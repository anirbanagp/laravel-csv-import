<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class BillingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(Request $request)
    {
        return [
            'created_at'    => 'required|date_format:d-m-Y',
            'ph_number'     => 'nullable|phone',
            'patient_name'  => 'required',
            'patient_code'  => 'required|unique:patients,code,'.$this->input('patient_id', 0),
            'doctor_name'   => 'nullable',
            'collector_id'  => 'nullable|integer',
            'patient_id'    => 'nullable|integer',
            'test_id'       => 'required',
            'total_amount'  => 'required|numeric|min:1',
            'discount_or_commission'    => 'nullable|numeric',
            'paid_amount'   => 'nullable|numeric|lte:total_amount',
        ];
    }
    public function messages()
    {
        return [
            'test_id.required'       => 'Test field is required.',
            'ph_number.required'     => 'phone number field is required.',
            'ph_number.phone'        => 'phone number is not valid.',
            'paid_amount.lte'        => 'paid amount should be less than or equal to total amount.',
        ];
    }
}
