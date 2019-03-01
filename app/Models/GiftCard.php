<?php

namespace App\Models;

use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Webpatser\Uuid\Uuid;

/**
 * App\Models\GiftCard
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $card_number
 * @property float|null $value
 * @property float|null $balance
 * @property \Illuminate\Support\Carbon|null $expiry_date
 * @property int|null $user_id
 * @property int $active
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereBalance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereExpiryDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereUuid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard whereValue($value)
 * @mixin \Eloquent
 * @property-read \App\Models\User|null $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GiftCard onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GiftCard withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\GiftCard withoutTrashed()
 * @property float|null $price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\GiftCard wherePrice($value)
 */
class GiftCard extends Model
{
    use Sortable,
        Searchable,
        SoftDeletes;
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'uuid',
        'value',
        'balance',
        'expiry_date',
        'card_number',
        'active',
        'price'
    ];

    /**
     * @var array
     */
    protected $dates = ['expiry_date'];

    /**
     * Searchable rules.
     *
     * @var array
     */
    protected $searchable = [
        /**
         * Columns and their priority in search results.
         * Columns with higher values are more important.
         * Columns with equal values have equal importance.
         *
         * @var array
         */
        'columns' => [
            'card_number' => 10,
            'value' => 1,
            'balance' => 5,
            'expiry_date' => 5,
            'active' => 3
        ]
    ];

    /**
     * @var array
     */
    public $sortable = [
        'card_number', 'id', 'value', 'balance', 'expiry_date', 'active'
    ];

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
        return 'uuid';
    }

    /**
     * @param $value
     */
    public function setExpiryDateAttribute($value)
    {
        $this->attributes['expiry_date'] = new Carbon($value);
    }

    /**
     * @param $value
     */
    public function setBalanceAttribute($value)
    {
        $this->attributes['balance'] = $this->value;
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
