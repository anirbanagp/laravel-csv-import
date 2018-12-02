<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectorRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'          => 'required',
            'code'          => 'required|unique:collectors,code,'.$this->input('id', 0),
            'phone_number'     => 'nullable|phone|unique:collectors,phone_number,'.$this->input('id', 0),
        ];
    }
}
