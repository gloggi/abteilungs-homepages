<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property int $sort_order
 * @property string $person_name
 * @property string $email
 * @property string $image
 * @property string $created_at
 * @property string $updated_at
 */
class StaffContact extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'sort_order', 'person_name', 'email', 'image'];

}
