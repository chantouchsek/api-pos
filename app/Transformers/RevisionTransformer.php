<?php

namespace App\Transformers;

class RevisionTransformer extends BaseTransformer
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
            'key' => $item->key,
            'old_value' => $item->oldValue(),
            'new_value' => $item->newValue(),
            'user' => $item->userResponsible(),
            'field_name' => $item->fieldName(),
            'date_updated' => isset($item->created_at) ? $item->created_at->toDateTimeString() : null
        ];
    }
}
