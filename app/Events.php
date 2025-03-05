<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;


use Illuminate\Database\Eloquent\SoftDeletes;

class Events extends Eloquent
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emp_hidro_eventos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hiev_fecha', 
        'hiev_trabajos_realizados', 
        'hiev_eventos_importantes', 
        'hiev_atencion_camaras', 
        'hiev_inspecciones',
        'hiev_ubicacion'
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
    protected $primaryKey = 'hiev_evento_id';

    public  $sequence = 'SEQ_PARAMETROS_SOAP';

}
