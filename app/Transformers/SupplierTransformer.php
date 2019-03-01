<?php

namespace App\Transformers;

class SupplierTransformer extends BaseTransformer
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
            'name' => (string)$item->name,
            'email' => (string)$item->email,
            'phone_number' => (string)$item->phone_number,
            'address' => (string)$item->address
        ];
    }
}
