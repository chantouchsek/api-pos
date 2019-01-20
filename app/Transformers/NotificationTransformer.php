<?php

namespace App\Transformers;

use Illuminate\Support\Collection;

class NotificationTransformer extends BaseTransformer
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
            'id' => $item->id,
            'notify_id' => isset($item->data['notify_id']) ? $item->data['notify_id'] : '',
            'body' => isset($item->data['body']) ? $item->data['body'] : '',
            'notify_type' => isset($item->data['notify_type']) ? $item->data['notify_type'] : '',
            'created_at' => isset($item->created_at) ? $item->created_at->toDateTimeString() : null,
            'type' => $item->type
        ];
    }
}
