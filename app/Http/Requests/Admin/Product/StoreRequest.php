<?php

namespace App\Http\Requests\Admin\Product;

use App\Http\Requests\BaseRequest as FormRequest;
use Illuminate\Validation\Rule;

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
            'tax_method' => 'nullable|in:Inclusive,Exclusive',
            'file' => 'required',
            'cost' => 'required|numeric',
            'price' => 'required|numeric',
            'category_id' => [
                Rule::exists('categories', 'id')->where('active', true)
            ],
            'code' => 'required',
            'description' => 'nullable|min:5',
            'imported_date' => 'required|date',
            'expired_at' => 'required|date'
        ];
    }
}
