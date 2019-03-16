<?php

namespace App\Models;

//use App\Traits\RevisionableUpgrade;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
//use Illuminate\Support\Facades\DB;
use Kyslik\ColumnSortable\Sortable;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\Models\Media;
//use Venturecraft\Revisionable\RevisionableTrait;
use Webpatser\Uuid\Uuid;

/**
 * App\Models\Product
 *
 * @property int $id
 * @property string $uuid
 * @property string|null $name
 * @property string|null $code
 * @property string|null $sku
 * @property string|null $description
 * @property float|null $cost
 * @property float|null $price
 * @property \Illuminate\Support\Carbon|null $imported_date
 * @property \Illuminate\Support\Carbon|null $expired_at
 * @property int|null $category_id
 * @property int|null $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property mixed $entity
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product hasAttribute($key, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product search($search, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product searchRestricted($search, $restriction, $threshold = null, $entireText = false, $entireTextOnly = false)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product sortable($defaultParameters = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereExpiredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereImportedDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereSku($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereUuid($value)
 * @mixin \Eloquent
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property int|null $tax_rate
 * @property string|null $tax_method
 * @property float|null $qty
 * @property int|null $qty_method
 * @property-read \App\Models\Category|null $category
 * @property-read \Illuminate\Database\Eloquent\Collection|\Venturecraft\Revisionable\Revision[] $revisionHistory
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereQtyMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTaxMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Product whereTaxRate($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Product[] $sales
 */
class Product extends Model implements HasMedia
{
    use Searchable,
        Sortable,
        HasMediaTrait;

    protected $fillable = [
        'name',
        'category_id',
        'user_id',
        'cost',
        'price',
        'sku',
        'description',
        'code',
        'imported_date',
        'expired_at',
        'tax_rate',
        'tax_method',
        'qty',
        'qty_method',
        'uuid'
    ];


    /**
     * @var bool
     */
    protected $revisionCreationsEnabled = true;
    protected $revisionEnabled = true;
    protected $revisionCleanup = true;
    protected $historyLimit = 1000;
    protected $revisionNullString = 'nothing';
    protected $revisionUnknownString = 'unknown';

    /**
     * @var array
     */
    protected $revisionFormattedFieldNames = [
        'imported_date' => 'Imported Date',
        'expired_at' => 'Expired At',
        'tax_rate' => 'Tax Rate',
        'tax_method' => 'Tax Method'
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
            'products.name' => 10,
            'code' => 1,
            'cost' => 5,
            'price' => 5,
            'imported_date' => 3,
            'expired_at' => 4
        ]
    ];

    /**
     * @var array
     */
    protected $appends = [];

    /**
     * @var array
     */
    public $sortable = [
        'name', 'id',
        'sku', 'code', 'sku', 'description', 'cost',
        'price', 'imported_date', 'expired_at',
    ];

    /**
     * @var array
     */
    protected $dates = ['imported_date', 'expired_at'];

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
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function sales(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'sale_products', 'product_id', 'sale_id')
            ->withPivot(['qty', 'price', 'sub_total'])->withTimestamps();
    }

    /**
     * @param Media|null $media
     * @throws \Spatie\Image\Exceptions\InvalidManipulation
     */
    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('feature-image')->crop('crop-center', 250, 250)
            ->quality(100)->nonQueued();
    }

    /**
     * @param $qty
     */
    public function qtyDecrement($qty)
    {
        static::decrement('qty', $qty);
    }

    /**
     * @param $qty
     */
    public function qtyIncrement($qty)
    {
        static::increment('qty', $qty);
    }
}
