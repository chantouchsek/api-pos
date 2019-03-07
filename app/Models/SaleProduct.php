<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

/**
 * App\Models\SaleProduct
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $sale_id
 * @property int|null $product_id
 * @property float|null $qty
 * @property float|null $price
 * @property float|null $sub_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Sale|null $sale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SaleProduct whereUuid($value)
 * @mixin \Eloquent
 */
class SaleProduct extends Model
{
    protected $fillable = [
        'uuid', 'sale_id', 'product_id', 'qty', 'price', 'sub_total'
    ];


    /**
     *  Setup model event hooks
     */
    public static function boot()
    {
        parent::boot();
        self::creating(function (SaleProduct $model) {
            $model->uuid = (string)Uuid::generate(4);
        });
    }

    /**
     * @return BelongsTo
     */
    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
