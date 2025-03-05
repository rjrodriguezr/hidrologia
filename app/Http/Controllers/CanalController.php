<?php

namespace App\Http\Controllers;

use App\Canal;


class CanalController extends BaseController
{
	/**
     * Set the model for de controller
     *
     * @return void
     */
    function setModel()
    {
        $this->model = Canal::class;
    }
}
