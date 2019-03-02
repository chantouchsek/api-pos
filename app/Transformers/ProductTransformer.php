<?php

namespace App\Transformers;

class ProductTransformer extends BaseTransformer
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
            'code' => (string)$item->code,
            'description' => (string)$item->description,
            'sku' => (string)$item->sku,
            'imported_date' => isset($item->imported_date) ? $item->imported_date->toDateString() : '',
            'expired_at' => isset($item->expired_at) ? $item->expired_at->toDateString() : '',
            'category_id' => $item->category_id,
            'user_id' => (string)$item->user_id,
            'category' => $item->category,
            'user' => $item->user,
            'cost' => (float)$item->cost,
            'price' => (float)$item->price,
            'file' => (string)$item->hasMedia('feature-image') ? config('app.url') . $item->getMedia('feature-image')->first()->getUrl() : 'http://i.pravatar.cc/500?img=' . $item->id,
            'tax_rate' => $item->tax_rate,
            'tax_method' => $item->tax_method,
            'qty' => $item->qty,
            'qty_method' => $item->qty_method,
            'updated_at' => isset($item->updated_at) ? $item->updated_at->toDateTimeString() : ''
        ];
    }
}
