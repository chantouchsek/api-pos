<?php

namespace App\Models;

use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;

class Comment extends \BrianFaust\Commentable\Models\Comment implements HasMedia
{
    use HasMediaTrait;

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array The event mapping.
     */
    protected $dispatchesEvents = [
//        'created' => Created::class
    ];

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('comment');
    }
}
