<?php

namespace App\Transformers;

class CommentTransformer extends BaseTransformer
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
            'body' => $item->body,
            'creator_id' => $item->creator_id,
            'created_at' => $item->created_at->toDateTimeString(),
            'creator' => $item->creator,
            'can_delete' => $item->can_delete,
            'video' => collect($item->media)->filter(function ($video) {
                if (strpos($video->mime_type, 'video') !== false) {
                    return $video;
                }
            })->values(),
            'image' => collect($item->media)->filter(function ($image) {
                if (strpos($image->mime_type, 'image') !== false) {
                    return $image;
                }
            })->values(),
            'file' => collect($item->media)->filter(function ($file) {
                if (strpos($file->mime_type, 'application') !== false || strpos($file->mime_type, 'text') !== false) {
                    return $file;
                }
            })->values()
        ];
    }
}
