<?php

namespace App\Http\Controllers;

use App\Umbral;


class UmbralController extends BaseController
{
	/**
     * Set the model for de controller
     *
     * @return void
     */
    function setModel()
    {
        $this->model = Umbral::class;
    }
}
