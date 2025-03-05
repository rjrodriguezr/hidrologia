<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;


use Illuminate\Database\Eloquent\SoftDeletes;

class CanalControl extends Eloquent
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_HIDRO_CANAL_CONTROLES';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['hcco_fecha', 'hcco_nivel', 'hcco_caudal', 'hcco_canal_fk'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = ['created_at', 'updated_at', 'deleted_at'];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The primary key.
     *
     * @var string
     */
    protected $primaryKey = 'hcco_ccontrol_id';

    public  $sequence = 'SEQ_EMP_HIDRO_CANAL_CONTROLES';

}
