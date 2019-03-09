<?php

namespace App\Models;

use App\Traits\Searchable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
use Webpatser\Uuid\Uuid;

/**
 * App\Models\Sale
 *
 * @property int $id
 * @property string|null $uuid
 * @property string|null $sale_number
 * @property int|null $customer_id
 * @property int|null $user_id Sale Person
 * @property string|null $date
 * @property float|null $total
 * @property float|null $grand_total
 * @property float|null $paid Amount of total customer paid to
 * @property float|null $tax
 * @property float|null $discount
 * @property int $status
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property-read \App\Models\Customer|null $customer
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $products
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereCustomerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereDiscount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereGrandTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale wherePaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereSaleNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereTax($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Sale whereUuid($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SalePayment[] $payments
 * @method static bool|null forceDelete()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Sale onlyTrashed()
 * @method static bool|null restore()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Sale withTrashed()
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Sale withoutTrashed()
 */
class Sale extends Model implements HasMedia
{
    use Searchable,
        Sortable,
        SoftDeletes,
        HasMediaTrait;

    protected $fillable = [
        'uuid', 'sale_number', 'customer_id', 'user_id', 'date', 'total',
        'grand_total', 'paid', 'tax', 'discount', 'status', 'notes'
    ];

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
            'sale_number' => 10,
            'date' => 1,
            'total' => 5,
            'paid' => 5
        ]
    ];

    /**
     * @var array
     */
    public $sortable = [
        'sale_number', 'id', 'date', 'total', 'paid'
    ];

    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function (Sale $model) {
            $model->uuid = (string)Uuid::generate(4);
            $lastRecord = Sale::orderBy('id', 'desc')->withTrashed()->first();
            $model->sale_number = str_pad($lastRecord ? $lastRecord->id + 1 : 1, 10, "0", STR_PAD_LEFT);
            $model->date = Carbon::now();
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
     * @return BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * @return BelongsToMany
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'sale_products', 'sale_id', 'product_id')
            ->withPivot(['qty', 'price', 'sub_total'])->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }

    /**
     * @param Media|null $media
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('sale-attachments')->nonQueued();
    }
}
