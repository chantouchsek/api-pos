<?php

namespace App\Transformers;

class ExpenseTransformer extends BaseTransformer
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
            'amount' => (float)$item->amount,
            'date' => isset($item->date) ? $item->date->toDateTimeString() : '',
            'user_id' => (int)$item->user_id,
            'user' => $item->user,
            'attachments' => collect($item->media),
            'created_at' => isset($item->created_at) ? $item->created_at->toDateTimeString() : '',
            'notes' => (string)$item->notes,
            'reference' => $item->reference
        ];
    }
}
