<?php

namespace App\Transformers;

class UserTransformer extends BaseTransformer
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
            'email_verified_at' => $item->email_verified_at,
            'registered' => $item->created_at->toDateString(),
            'active' => (boolean)$item->active,
            'phone_number' => (string)$item->phone_number,
            'username' => (string)$item->username,
            'gender' => $item->gender,
            'date_of_birth' => $item->date_of_birth,
            'birth_place' => $item->birth_place,
            'address' => $item->address,
            'locale' => $item->locale,
            'staff_id' => (string)$item->staff_id
        ];
    }
}
