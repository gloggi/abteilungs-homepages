<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $coordinates
 * @property string $created_at
 * @property string $updated_at
 */
class Location extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'coordinates'];
}
