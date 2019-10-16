<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property int $sort_order
 * @property string $plural_name
 * @property string $description
 * @property string $created_at
 * @property string $updated_at
 * @property Event[] $events
 */
class SpecialEventType extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'sort_order', 'plural_name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'event_special_event_types');
    }
}
