<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SignInOutLog extends Model
{

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'oracle';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'EMP_SIGN_IN_OUT_LOG';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['USERNAME', 'IP', 'STATUS'];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'DATE_MODIFIED';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    const UPDATED_AT = NULL;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['DATE_MODIFIED'];

    /**
     * The primary key.
     *
     * @var string
     */
    protected $primaryKey = 'ID';

}
