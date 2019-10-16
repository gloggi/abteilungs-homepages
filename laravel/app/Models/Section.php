<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $name
 * @property string $banner
 * @property int $sort_order
 * @property int $age_min
 * @property int $age_max
 * @property string $color
 * @property string $description
 * @property string $logo
 * @property string $annual_plan
 * @property string $created_at
 * @property string $updated_at
 * @property Group[] $groups
 */
class Section extends Model
{
    use CrudTrait;

    /**
     * @var array
     */
    protected $fillable = ['name', 'banner', 'sort_order', 'age_min', 'age_max', 'color', 'description', 'logo', 'annual_plan'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(Group::class);
    }
}
