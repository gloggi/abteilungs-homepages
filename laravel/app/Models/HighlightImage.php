<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $group_id
 * @property string $image
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Group $group
 */
class HighlightImage extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['group_id', 'image', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(Group::class);
    }
}
