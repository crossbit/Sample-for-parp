<?php

namespace App\Modules\Basic\Navigation\Models;

use App\Http\Traits\EloquentGetTableName;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NavigationRole extends Model {

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
    protected $table = 'system_navigation_role';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'navigation_id',
        'role_id'
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
     * translateRole
     *
     * This is translation relation for roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function translateRole()
    {
        return $this->HasOne(
            $this->namespaces . '\Acl\Models\Role',
            'id',
            'role_id'
        );
    }

}
