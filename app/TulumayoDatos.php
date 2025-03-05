<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;


use Illuminate\Database\Eloquent\SoftDeletes;

class TulumayoDatos extends Eloquent
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_HIDRO_TULUMAYO_DATOS';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hiyd_fecha', 
        'hiyd_nivel', 
        'hiyd_concen_solidos', 
        'hiyd_volumen_cal', 
        'hiyd_q_total_disp',
        'hiyd_q_captado',
        'hiyd_q_desvio_rio'
        ];

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
    protected $primaryKey = 'hiyd_tudato_id';

    public  $sequence = 'SEQ_EMP_HIDRO_TULUMAYO_DATOS';

}
