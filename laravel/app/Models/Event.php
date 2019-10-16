<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $start_location_id
 * @property integer $end_location_id
 * @property string $name
 * @property string $description
 * @property string $start_time
 * @property string $end_time
 * @property string $to_bring
 * @property string $created_at
 * @property string $updated_at
 * @property Location $startLocation
 * @property Location $endLocation
 * @property Download[] $downloads
 * @property Group[] $groups
 * @property User[] $owners
 * @property SpecialEventType[] $specialEventTypes
 */
class Event extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['start_location_id', 'end_location_id', 'name', 'description', 'start_time', 'end_time', 'to_bring'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function endLocation()
    {
        return $this->belongsTo('App\Models\Location', 'end_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function startLocation()
    {
        return $this->belongsTo('App\Models\Location', 'start_location_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function downloads()
    {
        return $this->hasMany('App\Models\Download');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function groups()
    {
        return $this->belongsToMany('App\Models\Group', 'event_groups');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function owners()
    {
        return $this->belongsToMany('App\Models\User', 'event_owners');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function specialEventTypes()
    {
        return $this->belongsToMany('App\Models\SpecialEventType', 'event_special_event_types');
    }
}
