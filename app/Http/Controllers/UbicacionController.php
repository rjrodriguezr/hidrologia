<?php

namespace App\Http\Controllers;

use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;

class UbicacionController extends Controller
{
 	/**
	* Display a listing of the resource.
	*
	* @return \Illuminate\Http\Response
	*/
  public function index()
  {
      $data = DB::table("EMP_HIDRO_ZONAS")->select("hizo_zona_id AS id", "hizo_nombre AS name")->orderBy('hizo_nombre', 'asc')->get();
      return response()->json($data);
  }
}
