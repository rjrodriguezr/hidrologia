<?php

namespace App\Http\Controllers;

use App\TulumayoDatos;
use App\TomaDatos;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;


class ControlChinangoController extends Controller
{
	

	/**
     * Display a listing of the resource for datatable jquery plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        if ((empty($request->date_ini) == false) && (empty($request->date_fin) == false)){

            $data = DB::select("SELECT * FROM (SELECT TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - (TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM DUAL CONNECT BY LEVEL <= (24+ (24 * (TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS'))))) T1 LEFT JOIN (SELECT T1.HIYD_TUDATO_ID, TRUNC( HIYD_FECHA,'HH24') HIYD_FECHA, T1.HIYD_NIVEL, T1.HIYD_CONCEN_SOLIDOS, T1.HIYD_VOLUMEN_CAL, T1.HIYD_Q_TOTAL_DISP, T1.HIYD_Q_CAPTADO, T1.HIYD_Q_DESVIO_RIO, T2.HITD_CONCEN_SOLIDOS AS HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO AS HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP AS HITD_Q_TOTAL_DISP_T FROM EMP_HIDRO_TULUMAYO_DATOS T1 INNER JOIN EMP_HIDRO_TOMADATOS T2 ON T1.HIYD_FECHA = T2.HITD_FECHA AND T2.HITD_TOMA_FK = 4 WHERE T1.HIYD_FECHA BETWEEN TRUNC(TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) AND TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') + 1)) T2 ON T1.FechaHora =  T2.HIYD_FECHA ORDER BY 1 ASC");
        }
        elseif ((empty($request->date_ini) == false) && (empty($request->date_fin) == true)){

            $data = DB::select("SELECT * FROM (SELECT trunc(to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS')) - (to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM DUAL CONNECT BY LEVEL <= (24+ (24 * (to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS'))))) T1 LEFT JOIN (SELECT T1.HIYD_TUDATO_ID, TRUNC( HIYD_FECHA,'HH24') HIYD_FECHA, T1.HIYD_NIVEL, T1.HIYD_CONCEN_SOLIDOS, T1.HIYD_VOLUMEN_CAL, T1.HIYD_Q_TOTAL_DISP, T1.HIYD_Q_CAPTADO, T1.HIYD_Q_DESVIO_RIO, T2.HITD_CONCEN_SOLIDOS AS HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO AS HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP AS HITD_Q_TOTAL_DISP_T FROM EMP_HIDRO_TULUMAYO_DATOS T1 INNER JOIN EMP_HIDRO_TOMADATOS T2 ON T1.HIYD_FECHA = T2.HITD_FECHA AND T2.HITD_TOMA_FK = 4 WHERE T1.HIYD_FECHA BETWEEN TRUNC(TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) AND TRUNC(to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS') + 1)) T2 ON T1.FechaHora =  T2.HIYD_FECHA ORDER BY 1 ASC");
        }
        elseif ((empty($request->date_ini) == true) && (empty($request->date_fin) == false)){

            $data = DB::select("SELECT * FROM (SELECT TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - 6 + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM dual CONNECT BY level <= (24 + (24 * (trunc(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - trunc(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) + 6)))) T1 LEFT JOIN (SELECT T1.HIYD_TUDATO_ID, TRUNC( HIYD_FECHA,'HH24') HIYD_FECHA, T1.HIYD_NIVEL, T1.HIYD_CONCEN_SOLIDOS, T1.HIYD_VOLUMEN_CAL, T1.HIYD_Q_TOTAL_DISP, T1.HIYD_Q_CAPTADO, T1.HIYD_Q_DESVIO_RIO, T2.HITD_CONCEN_SOLIDOS AS HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO AS HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP AS HITD_Q_TOTAL_DISP_T FROM EMP_HIDRO_TULUMAYO_DATOS T1 INNER JOIN EMP_HIDRO_TOMADATOS T2 ON T1.HIYD_FECHA = T2.HITD_FECHA AND T2.HITD_TOMA_FK = 4 WHERE T1.HIYD_FECHA BETWEEN TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - 6 AND TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') + 1)) T2 ON T1.FechaHora = T2.HIYD_FECHA ORDER BY To_Char(FECHAHORA,'YYYYMMDD') DESC,HORA ASC");
        }
        else{
            $data = DB::select("SELECT * FROM (SELECT TRUNC(SYSDATE) - 6 + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM dual CONNECT BY level <= (24 + (24 * (trunc(SYSDATE) - trunc(SYSDATE) + 6)))) T1 LEFT JOIN (SELECT T1.HIYD_TUDATO_ID, TRUNC( HIYD_FECHA,'HH24') HIYD_FECHA, T1.HIYD_NIVEL, T1.HIYD_CONCEN_SOLIDOS, T1.HIYD_VOLUMEN_CAL, T1.HIYD_Q_TOTAL_DISP, T1.HIYD_Q_CAPTADO, T1.HIYD_Q_DESVIO_RIO, T2.HITD_CONCEN_SOLIDOS AS HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO AS HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP AS HITD_Q_TOTAL_DISP_T FROM EMP_HIDRO_TULUMAYO_DATOS T1 INNER JOIN EMP_HIDRO_TOMADATOS T2 ON T1.HIYD_FECHA = T2.HITD_FECHA AND T2.HITD_TOMA_FK = 4 WHERE T1.HIYD_FECHA BETWEEN TRUNC(SYSDATE) - 6 AND TRUNC(SYSDATE + 1)) T2 ON T1.FechaHora = T2.HIYD_FECHA ORDER BY To_Char(FECHAHORA,'YYYYMMDD') DESC,HORA ASC");
        }

        $select_tiempo = DB::select("SELECT EMP_PROFILES.T_EDICION from EMP_PROFILES join EMP_USERS on EMP_PROFILES.ID = EMP_USERS.PROFILE_ID WHERE EMP_USERS.API_TOKEN = '$request->api_token'");
        $tiempo_edicion = $select_tiempo[0]->t_edicion;

        foreach($data as $i => $i_value) {
            $i_value->t_edicion = $tiempo_edicion;
        }
                
        $data= collect($data);

        return Datatables::of($data)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        $tulumayo = TulumayoDatos::select()
            ->where('hiyd_fecha', '=', $data['hiyd_fecha'])
            ->first();

        if (is_null($tulumayo)){

            $tulumayo = TulumayoDatos::create($data);
            $tomasT = TomaDatos::create(
            	[
            		'hitd_fecha' => $data['hiyd_fecha'],
            		'hitd_concen_solidos' => $data['hitd_concen_solidos_t'],
            		'hitd_q_captado' => $data['hitd_q_captado_t'],
            		'hitd_q_total_disp' => $data['hitd_q_total_disp_t'],
            		'hitd_toma_fk' => 4
            	]
            );


            $response = ['message' => 'Resource created successfully', 'error' => false, 'data' => [ "tulumayo" => $tulumayo, "tomas_t" => $tomasT]];
        }else{
            $response = ['message' => 'Ya existe registro para esta fecha ' . $data['hiyd_fecha'], 'error' => true];
        }


        return response()->json($response, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //DB::enableQueryLog();
        $obj = TulumayoDatos::findOrFail($id);
        $data = $request->all();
        foreach ($obj->getFillable() as $aField){
            if (empty($request->$aField) == false){
                $obj->$aField = trim($request->$aField);

            }
        }

        $obj->hiyd_q_total_disp = $data['hiyd_q_total_disp'];
        $obj->hiyd_q_captado = $data['hiyd_q_captado'];
        $obj->save();
        //var_dump(DB::getQueryLog());

       

        $tomasT = TomaDatos::select()
        	->where('hitd_fecha', '=', $obj->hiyd_fecha)
        	->where('hitd_toma_fk', '=', 4)
        	->first();

        $tomasT->hitd_concen_solidos = $data['hitd_concen_solidos_t'];
		$tomasT->hitd_q_captado = $data['hitd_q_captado_t'];
		$tomasT->hitd_q_total_disp = $data['hitd_q_total_disp_t'];
		$tomasT->save();

		$response = ['message' => 'Resource updated successfully', 'error' => false];
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $obj = TulumayoDatos::findOrFail($id);

        $tomasT = TomaDatos::select()
        	->where('hitd_fecha', '=', $obj->hiyd_fecha)
        	->where('hitd_toma_fk', '=', 4)
        	->first();

        $obj["hitd_concen_solidos_t"] = $tomasT->hitd_concen_solidos;
        $obj["hitd_q_captado_t"] = $tomasT->hitd_q_captado;
        $obj["hitd_q_total_disp_t"] = $tomasT->hitd_q_total_disp;
        
        return response()->json($obj);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $obj = TulumayoDatos::find($id);

        TulumayoDatos::destroy($id);

        $tomasT = TomaDatos::select()
        	->where('hitd_fecha', '=', $obj->hiyd_fecha)
        	->where('hitd_toma_fk', '=', 4)
        	->first()
        	->delete();

        $response = ['message' => 'Resource deleted successfully'];
        return response()->json($response, 204);
    }

    /**
     * Test de resourse by Restangular in method OPTIONS.
     *
     * @return \Illuminate\Http\Response
     */
    public function op()
    {
        return response()->json(["test"=>"ok"]);
    }
}
