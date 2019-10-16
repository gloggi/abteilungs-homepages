<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $event_id
 * @property string $name
 * @property string $file
 * @property string $created_at
 * @property string $updated_at
 * @property Event $event
 */
class Download extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['event_id', 'name', 'file'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
