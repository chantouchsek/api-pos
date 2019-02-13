<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Venturecraft\Revisionable\Revision as BaseRevision;

/**
 * App\Models\Revision
 *
 * @property int $id
 * @property string $revisionable_type
 * @property int $revisionable_id
 * @property int|null $user_id
 * @property string $key
 * @property string|null $old_value
 * @property string|null $new_value
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User|null $revisionUser
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $revisionable
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereNewValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereOldValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereRevisionableId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereRevisionableType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Revision whereUserId($value)
 * @mixin \Eloquent
 */
class Revision extends BaseRevision
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function revisionUser()
    {
        $relation = $this->belongsTo(User::class, 'user_id');
        if ($this->classUseTrait(User::class, SoftDeletes::class)) {
            $relation = $relation->withTrashed();
        }
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * @param $model
     * @param $trait
     * @return bool
     */
    private function classUseTrait($model, $trait)
    {
        $traits = class_uses($model);
        return isset($traits[$trait]) ? true : false;
    }
}
