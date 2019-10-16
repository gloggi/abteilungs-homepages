<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $contact_id
 * @property integer $section_id
 * @property integer $parent_id
 * @property string $name
 * @property string $banner
 * @property int $sort_order
 * @property string $gender
 * @property string $logo
 * @property string $color
 * @property string $geographic_area
 * @property string $description
 * @property string $contact_email
 * @property string $contact_name
 * @property string $annual_plan
 * @property int $lft
 * @property int $rgt
 * @property int $depth
 * @property string $created_at
 * @property string $updated_at
 * @property User $contact
 * @property Group $parent
 * @property Section $section
 * @property Event[] $events
 * @property Group[] $predecessorGroups
 * @property Group[] $successorGroups
 * @property HighlightImage[] $highlightImages
 * @property User[] $leaders
 */
class Group extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['contact_id', 'section_id', 'parent_id', 'name', 'banner', 'sort_order', 'gender', 'logo', 'color', 'geographic_area', 'description', 'contact_email', 'contact_name', 'annual_plan', 'lft', 'rgt', 'depth'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contact()
    {
        return $this->belongsTo('App\Models\User', 'contact_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\Group', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function section()
    {
        return $this->belongsTo('App\Models\Section');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function events()
    {
        return $this->belongsToMany('App\Models\Event', 'event_groups');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function predecessorGroups()
    {
        return $this->belongsToMany('App\Models\Group', 'group_transitions', 'to_group_id', 'from_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function successorGroups()
    {
        return $this->belongsToMany('App\Models\Group', 'group_transitions', 'from_group_id', 'to_group_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function highlightImages()
    {
        return $this->hasMany('App\Models\HighlightImage');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function leaders()
    {
        return $this->belongsToMany('App\Models\User', 'leaders');
    }
}
