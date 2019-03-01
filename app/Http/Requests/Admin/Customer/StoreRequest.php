<?php

namespace App\Http\Requests\Admin\Customer;

use App\Http\Requests\BaseRequest as FormRequest;

class StoreRequest extends FormRequest
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
            'name' => ['required', 'min:3', 'string'],
            'email' => 'required|email|unique:customers,email',
            'address' => 'required|string|min:5',
            'phone_number' => 'required|min:7'
        ];
    }
}
