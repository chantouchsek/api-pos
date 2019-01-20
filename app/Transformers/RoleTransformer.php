<?php

namespace App\Transformers;

use function PHPSTORM_META\map;

class RoleTransformer extends BaseTransformer
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
            'name' => (string)$item->name,
            'permissions' => collect($item->permissions)->map(function ($row) {
                return $row->name;
            })
        ];
    }
}
