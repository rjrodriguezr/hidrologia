<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;


use Illuminate\Database\Eloquent\SoftDeletes;

class ShequeDatos extends Eloquent
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_HIDRO_SHEQUE_DATOS';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hisd_fecha', 
        'hisd_nivel', 
        'hisd_conce_solidos', 
        'hisd_volumen_cal', 
        'hisd_q_sacsa',
        'hisd_q_desvio_rio',
        'hisd_q_canchis',
        'hisd_q_presa_cal',
        'hisd_q_pillirhua',
        'hisd_q_total_cal'
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
    protected $primaryKey = 'hisd_sdato_id';

    public  $sequence = 'SEQ_EMP_HIDRO_SHEQUE_DATOS';

}
