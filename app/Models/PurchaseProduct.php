<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * App\Models\PurchaseProduct
 *
 * @property int $id
 * @property int|null $purchase_id
 * @property int|null $product_id
 * @property int|null $qty
 * @property float|null $cost
 * @property float|null $sub_total
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Product|null $product
 * @property-read \App\Models\Purchase|null $purchase
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct wherePurchaseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereQty($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereSubTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\PurchaseProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class PurchaseProduct extends Model
{
    /**
     * @var array
     */
    protected $fillable = [
        'purchase_id', 'product_id', 'qty', 'cost', 'sub_total'
    ];

    /**
     * @return BelongsTo
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
