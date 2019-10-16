<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
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
    use CrudTrait;

    /**
     * @var array
     */
    protected $fillable = ['name', 'sort_order', 'plural_name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_special_event_types');
    }
}
