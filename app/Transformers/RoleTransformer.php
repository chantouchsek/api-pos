<?php

namespace App\Transformers;

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
            }),
            'users' => collect($item->users),
            'guard_name' => (string)$item->guard_name
        ];
    }
}
