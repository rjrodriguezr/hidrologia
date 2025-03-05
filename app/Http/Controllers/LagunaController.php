<?php

namespace App\Http\Controllers;

use App\Laguna;


class LagunaController extends BaseController
{
	/**
     * Set the model for de controller
     *
     * @return void
     */
    function setModel()
    {
        $this->model = Laguna::class;
    }
}
