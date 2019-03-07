<?php

namespace App\Transformers;

class SaleTransformer extends BaseTransformer
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
            'sale_number' => (string)$item->sale_number,
            'customer_id' => (int)$item->customer_id,
            'date' => isset($item->date) ? $item->date->toDateTimeString() : '',
            'total' => (float)$item->total,
            'grand_total' => (float)$item->grand_total,
            'paid' => (float)$item->paid,
            'tax' => (float)$item->tax,
            'discount' => (float)$item->discount,
            'status' => (int)$item->status,
            'notes' => (string)$item->notes
        ];
    }
}
