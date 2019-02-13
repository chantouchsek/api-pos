<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;


/**
 * App\Models\UserDevice
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $user_id
 * @property string|null $player_id
 * @property string|null $push_token
 * @property int $subscribed
 * @property string|null $device_name
 * @property string|null $country
 * @property string|null $lang_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereCountry($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereDeviceName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereLangCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice wherePushToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereSubscribed($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\UserDevice whereUuid($value)
 * @mixin \Eloquent
 */
class UserDevice extends Model
{

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'player_id',
        'push_token',
        'subscribed'
    ];


    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }


    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'player_id';
    }
}
