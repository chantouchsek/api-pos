<?php

namespace App\Http\Requests\Admin\Sale;

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
            'customer_id' => ['required', Rule::exists('customers', 'id')],
            'payment.amount' => 'required|min:1|numeric',
            'payment.paid_by' => 'required|integer',
            'notes' => 'required|min:5|string',
            'products' => 'required|array'
        ];
    }
}
