<?php

namespace App\Transformers;

class PurchaseTransformer extends BaseTransformer
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
            'date' => (string)$item->date,
            'reference' => (string)$item->reference,
            'supplier_id' => (string)$item->supplier_id,
            'supplier' => $item->supplier,
            'notes' => (string)$item->notes,
            'user' => $item->user,
            'products' => collect($item->products)->map(function ($item) {
                return [
                    'cost' => $item->cost,
                    'created_at' => isset($item->created_at) ? $item->created_at->toDateTimeString() : '',
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'purchase_id' => $item->purchase_id,
                    'qty' => $item->qty,
                    'sub_total' => $item->sub_total,
                    'updated_at' => isset($item->updated_at) ? $item->updated_at->toDateTimeString() : '',
                    'name' => isset($item->product) ? $item->product->name : '',
                    'file' => isset($item->product) ? (string)$item->product->hasMedia('feature-image') ? config('app.url') . $item->product->getMedia('feature-image')->first()->getUrl() : 'http://i.pravatar.cc/500?img=' . $item->id : '',
                    'is_new' => false
                ];
            }),
            'received' => (int)$item->received,
            'sub_total' => (float)$item->sub_total,
            'cost' => (float)$item->cost
        ];
    }
}
