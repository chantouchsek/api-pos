<?php

namespace App\Http\Requests\Admin\Purchase;

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
            'date' => 'required|date',
            'reference' => 'required|string',
            'supplier_id' => [
                Rule::exists('suppliers', 'id'), 'required'
            ],
            'notes' => 'nullable|min:5',
            'received' => 'required|in:1,0',
            'products' => 'required|array'
        ];
    }
}
