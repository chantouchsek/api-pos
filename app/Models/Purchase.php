<?php

namespace App\Models;

use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Webpatser\Uuid\Uuid;

/**
 * App\Models\Purchase
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $date
 * @property string|null $reference
 * @property int|null $supplier_id
 * @property int $received
 * @property string|null $notes
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\User|null $user
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase newQuery()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Purchase onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase query()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereReceived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereSupplierId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Purchase whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Purchase withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Purchase withoutTrashed()
 * @mixin \Eloquent
 * @property-read \App\Models\Supplier|null $supplier
 */
class Purchase extends Model implements HasMedia
{
    use Searchable,
        Sortable,
        HasMediaTrait,
        SoftDeletes;


    /**
     * @var array
     */
    protected $fillable = [
        'uuid', 'user_id', 'date', 'received', 'notes', 'reference', 'supplier_id'
    ];

    /**
     * @var array
     */
    protected $dates = ['date'];

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
            'date' => 10,
            'reference' => 1,
            'received' => 5,
            'notes' => 5
        ]
    ];

    /**
     * @var array
     */
    public $sortable = [
        'date', 'id', 'reference', 'received', 'notes', 'uuid'
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
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('purchase-attachments')->nonQueued();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(PurchaseProduct::class);
    }


    /**
     * @param $value
     */
    public function setDateAttribute($value)
    {
        $this->attributes['date'] = new Carbon($value);
    }

    /**
     * @return BelongsTo
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
