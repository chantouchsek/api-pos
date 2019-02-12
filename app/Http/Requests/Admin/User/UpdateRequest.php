<?php

namespace App\Http\Requests\Admin\User;

use App\Http\Requests\BaseRequest as FormRequest;

class UpdateRequest extends FormRequest
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
            'first_name' => 'required|min:3|string',
            'last_name' => 'required|min:3|string',
            'email' => 'required|email|unique:users,email,' . $this->id,
            'password' => 'same:password_confirmation|min:6',
            'password_confirmation' => 'same:password|min:6',
            'roles' => 'required'
        ];
    }
}
