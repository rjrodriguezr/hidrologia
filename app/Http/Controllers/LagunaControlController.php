<?php

namespace App\Http\Controllers;

use App\LagunaControl;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use DateTime;
use DateInterval;


class LagunaControlController extends BaseController
{
	/**
     * Set the model for de controller
     *
     * @return void
     */
    function setModel()
    {
        $this->model = LagunaControl::class;
    }

    /**
     * Display a listing of the resource for datatable jquery plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        $today = date("yy.m.d 23:59:59");
        $past_seven = date('yy.m.d 23:59:59', strtotime("-1 week"));
        if($request->date_fin){
            $format = 'Y.m.d H:i:s';
            $date = DateTime::createFromFormat($format, $request->date_fin);
            $date_past_seven = $date->sub(new DateInterval('P7D'))->format('Y.m.d H:i:s');            
        }
            
        $query = "SELECT EMP_HIDRO_LAGUNA_CONTROLES.*, EMP_HIDRO_LAGUNAS.HILA_NOMBRE as laguna
                    FROM EMP_HIDRO_LAGUNA_CONTROLES 
                    JOIN EMP_HIDRO_LAGUNAS 
                    ON EMP_HIDRO_LAGUNA_CONTROLES.HLCO_LAGUNA_FK = EMP_HIDRO_LAGUNAS.HILA_LAGUNA_ID";
        $where = " WHERE 1=1";            
        $whereDos = "";           
        if (empty($request->laguna) == false || $request->laguna > 0){
            $whereDos .= " AND EMP_HIDRO_LAGUNA_CONTROLES.HLCO_LAGUNA_FK = '$request->laguna'";
        } 

        if ((empty($request->date_ini) == false) && (empty($request->date_fin) == false)){
            $whereDos .= " AND EMP_HIDRO_LAGUNA_CONTROLES.HLCO_FECHA BETWEEN '$request->date_ini' AND '$request->date_fin'";
        }
        elseif ((empty($request->date_ini) == false) && (empty($request->date_fin) == true)){
            $whereDos .= " AND EMP_HIDRO_LAGUNA_CONTROLES.HLCO_FECHA >= '$request->date_ini'";
        }
        elseif ((empty($request->date_ini) == true) && (empty($request->date_fin) == false)){
            $whereDos .= " AND EMP_HIDRO_LAGUNA_CONTROLES.HLCO_FECHA BETWEEN '$date_past_seven' AND '$request->date_fin'";
        }
        else{
            $whereDos .= " AND EMP_HIDRO_LAGUNA_CONTROLES.HLCO_FECHA BETWEEN '$past_seven' AND '$today'";
        }

        $select = $query . $where . $whereDos;
        $datos = DB::select($select);
        $select_tiempo = DB::select("SELECT EMP_PROFILES.T_EDICION from EMP_PROFILES join EMP_USERS on EMP_PROFILES.ID = EMP_USERS.PROFILE_ID WHERE EMP_USERS.API_TOKEN = '$request->api_token'");
        $tiempo_edicion = $select_tiempo[0]->t_edicion;

        foreach($datos as $i => $i_value) {
            $i_value->t_edicion = $tiempo_edicion;
        }
        $data = collect($datos);

        return Datatables::of($data)->make(true);
    }
}
