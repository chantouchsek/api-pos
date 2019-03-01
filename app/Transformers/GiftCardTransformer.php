<?php

namespace App\Transformers;

class GiftCardTransformer extends BaseTransformer
{

    /**
     * Method used to transform an item.
     *
     * @param $item mixed The item to be transformed.
     *
     * @return array The transformed item.
     */
    public function transform($item): array
    {
        return [
            'id' => (int)$item->id,
            'uuid' => (string)$item->uuid,
            'value' => (float)$item->value,
            'balance' => (float)$item->balance,
            'expiry_date' => isset($item->expiry_date) ? $item->expiry_date->toDateTimeString() : '',
            'user_id' => (int)$item->user_id,
            'user' => $item->user,
            'card_number' => (string)$item->card_number,
            'created_at' => isset($item->created_at) ? $item->created_at->toDateTimeString() : '',
            'active' => (boolean)$item->active
        ];
    }
}
