<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;


use Illuminate\Database\Eloquent\SoftDeletes;

class Umbral extends Eloquent
{
    use SoftDeletes;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_HIDRO_UMBRALES';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['HIUM_FORMULARIO', 'HIUM_CANAL_LAGUNA', 'HIUM_VALUMEN', 'HIUM_MINIMO', 'HIUM_MAXIMO'];

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
    protected $primaryKey = 'HICA_CANAL_ID';

    public  $sequence = 'SEQ_EMP_HIDRO_UMBRALES';

}
