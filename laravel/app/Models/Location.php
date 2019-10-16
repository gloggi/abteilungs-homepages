<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
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
    use CrudTrait;

    /**
     * @var array
     */
    protected $fillable = ['name', 'coordinates'];
}
