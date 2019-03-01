<?php

namespace App\Http\Requests\Admin\GiftCard;

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
            'card_number' => 'required|min:16|max:24|unique:gift_cards,card_number,' . $this->id,
            'value' => [
                'min:1', 'numeric', 'required'
            ],
            'expiry_date' => [
                'date', 'required'
            ]
        ];
    }
}
