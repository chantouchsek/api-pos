<?php

namespace App\Http\Requests\Admin\GiftCard;

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
            'card_number' => 'required|unique:gift_cards,card_number|min:16|max:24',
            'value' => [
                'min:1', 'numeric', 'required'
            ],
            'expiry_date' => [
                'date', 'required'
            ]
        ];
    }
}
