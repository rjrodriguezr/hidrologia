<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Authenticatable
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_PERMISSIONS';

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
        'parent', 'name', 'route'
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

    public  $sequence = 'SEQ_EMP_PERMISSIONS';

    /**
     * Get the childs.
     */
    public function childs()
    {
        return $this->hasMany('App\Permission', 'id', 'parent');
    }

    /**
     * Get the parent.
     */
    public function objParent()
    {
        return $this->belongsTo('App\Permission', 'parent', 'id');
    }

}
