<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Webpatser\Uuid\Uuid;

/**
 * App\Models\SalePayment
 *
 * @property int $id
 * @property string|null $uuid
 * @property int|null $sale_id
 * @property int|null $user_id
 * @property string|null $date
 * @property string|null $reference
 * @property float|null $amount
 * @property int $paid_by
 * @property string|null $notes
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Spatie\MediaLibrary\Models\Media[] $media
 * @property-read \App\Models\Sale|null $sale
 * @property-read \App\Models\User|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment wherePaidBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\SalePayment whereUuid($value)
 * @mixin \Eloquent
 */
class SalePayment extends Model implements HasMedia
{
    use HasMediaTrait;

    protected $fillable = [
        'uuid', 'sale_id', 'user_id', 'date', 'reference', 'notes', 'amount', 'paid_by'
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
        self::creating(function (SalePayment $model) {
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
