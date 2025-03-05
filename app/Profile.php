<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Authenticatable
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_PROFILES';

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'oracle';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 't_edicion'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public  $sequence = 'SEQ_EMP_PROFILES';

    /**
     * Get the permissions.
     */
    public function permissions()
    {
        return $this->belongsToMany('App\Permission', 'emp_profiles_permissions', 'profile_id', 'permission_id');
    }

    /**
     * Get the repositories.
     */
    public function repositories()
    {
        return $this->belongsToMany('App\Repository', 'emp_profiles_repositories', 'profile_id', 'repository_id');
    }



}
