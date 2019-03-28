<?php

namespace App\Modules\Basic\Navigation\Models;

use App\Http\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Navigation extends Model {

    use SoftDeletes,
        EloquentGetTableName;

    /**
     * The connection associated with the model.
     *
     * @var string
     */
    protected $connection = 'mysql';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'system_navigation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lp',
        'title',
        'parent_id',
        'route_name',
        'param',
        'special_param',
        'required_param',
        'is_active',
        'destiny'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        //
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        //
    ];

    /**
     * The global namespaces.
     *
     * @var array
     */
    protected $namespaces = "App\Modules\Basic";


    // Beginning -------------------------------------------------------------------------------------------------------

    /**
     * buildTree
     *
     * This is QueryBuilder for make a navigation tree.
     *
     * @param int $level
     * @param string $relation
     * @param string $column
     * @param int $parent_id
     * @return mixed
     */
    public static function buildTree($level = 2, $relation = 'children', $column = 'parent_id', $parent_id = 0) {
        $query = static::with(
            implode(
                '.',
                array_fill(
                    0,
                    $level,
                    $relation
                )
            ),
            'navigationRole'
        )->withTrashed()
         ->where($column, $parent_id);

        return $query;
    }


    /**
     * parent
     *
     * This is parent relation for navigation.
     *
     * @return mixed
     */
    public function parent()
    {
        return $this->hasOne(
            $this->namespaces . '\Navigation\Models\Navigation',
            'id',
            'parent_id'
        )->withTrashed()
         ->with('navigationRole')
         ->orderBy('lp');
    }


    /**
     * children
     *
     * This is children relation for navigation.
     *
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(
            $this->namespaces . '\Navigation\Models\Navigation',
            'parent_id',
            'id'
        )->withTrashed()
         ->with('navigationRole')
         ->orderBy('lp');
    }


    /**
     * navigationRole
     *
     * This is roles relation for navigation.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function navigationRole()
    {
        return $this->hasMany(
            $this->namespaces . '\Navigation\Models\NavigationRole',
            'navigation_id',
            'id'
        );
    }


    /**
     * countChildrenNests
     *
     * Count children nest.
     *
     * @return int
     */
    public function countChildrenNests() {
        $count = 0;
        if ($child = $this->children()->first()) {
            $count += $child->countChildrenNests() + 1;
        }
        return $count;
    }


    /**
     * countChildren
     *
     * Count all children in tree.
     *
     * @return int
     */
    public function countChildren() {
        $query = $this->children();
        $count = 0;
        foreach ($query->get() as $child) {
            $count += $child->countChildren() + 1;
        }
        return $count;
    }

}
