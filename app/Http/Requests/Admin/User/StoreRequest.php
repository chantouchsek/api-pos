<?php

namespace App\Http\Requests\Admin\User;

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
            'name' => 'required|min:3|string',
            'email' => 'required|email|unique:users,email',
            // 'password' => 'required|same:confirm-password',
            'roles' => 'required|array',
            'avatar' => 'image|mimes:jpeg,png,jpg|max:4056',
            'phone_number' => 'required|min:7|max:12',
            'gender' => [
                'required',
                'in:1,2'
            ]
        ];
    }
}
