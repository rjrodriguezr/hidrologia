<?php

namespace App\Http\Controllers;

use App\ShequeDatos;
use App\TomaDatos;
use App\Profile;
use App\User;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Datatables\Services;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use DateTime;


class ControlRimacController extends Controller
{


    /**
     * Display a listing of the resource for datatable jquery plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        if ((empty($request->date_ini) == false) && (empty($request->date_fin) == false)) {

            $data = DB::select("SELECT * FROM (SELECT TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - (TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM DUAL CONNECT BY LEVEL <= (24+ (24 * (TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS'))))) T1 LEFT JOIN (SELECT T1.HISD_SDATO_ID, TRUNC( HISD_FECHA,'HH24') HISD_FECHA, T1.HISD_NIVEL, T1.HISD_CONCE_SOLIDOS, T1.HISD_VOLUMEN_CAL,T1.HISD_Q_SACSA, T1.HISD_Q_DESVIO_RIO, T1.HISD_Q_CANCHIS, T1.HISD_Q_PRESA_CAL, T1.HISD_Q_PILLIRHUA, T1.HISD_Q_TOTAL_CAL, T2.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO as HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_T, T3.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_H, T3.HITD_Q_CAPTADO as HITD_Q_CAPTADO_H, T3.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_H, T21.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_M, T21.HITD_Q_CAPTADO as HITD_Q_CAPTADO_M, T21.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_M FROM EMP_HIDRO_SHEQUE_DATOS T1 LEFT JOIN EMP_HIDRO_TOMADATOS T2 on T1.HISD_FECHA = T2.HITD_FECHA and T2.HITD_TOMA_FK = 2 LEFT JOIN EMP_HIDRO_TOMADATOS T3 on T1.HISD_FECHA = T3.HITD_FECHA and T3.HITD_TOMA_FK = 3 LEFT JOIN EMP_HIDRO_TOMADATOS T21 on T1.HISD_FECHA = T21.HITD_FECHA and T21.HITD_TOMA_FK = 21 WHERE T1.HISD_FECHA BETWEEN TRUNC(TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) AND TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') + 1)) T2 ON T1.FechaHora =  T2.HISD_FECHA ORDER BY 1 ASC");
        } elseif ((empty($request->date_ini) == false) && (empty($request->date_fin) == true)) {

            $data = DB::select("SELECT * FROM (SELECT trunc(to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS')) - (to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM DUAL CONNECT BY LEVEL <= (24+ (24 * (to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS') - TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS'))))) T1 LEFT JOIN (SELECT T1.HISD_SDATO_ID, TRUNC( HISD_FECHA,'HH24') HISD_FECHA, T1.HISD_NIVEL, T1.HISD_CONCE_SOLIDOS, T1.HISD_VOLUMEN_CAL,T1.HISD_Q_SACSA, T1.HISD_Q_DESVIO_RIO, T1.HISD_Q_CANCHIS, T1.HISD_Q_PRESA_CAL, T1.HISD_Q_PILLIRHUA, T1.HISD_Q_TOTAL_CAL, T2.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO as HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_T, T3.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_H, T3.HITD_Q_CAPTADO as HITD_Q_CAPTADO_H, T3.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_H, T21.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_M, T21.HITD_Q_CAPTADO as HITD_Q_CAPTADO_M, T21.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_M FROM EMP_HIDRO_SHEQUE_DATOS T1 LEFT JOIN EMP_HIDRO_TOMADATOS T2 on T1.HISD_FECHA = T2.HITD_FECHA and T2.HITD_TOMA_FK = 2 LEFT JOIN EMP_HIDRO_TOMADATOS T3 on T1.HISD_FECHA = T3.HITD_FECHA and T3.HITD_TOMA_FK = 3 LEFT JOIN EMP_HIDRO_TOMADATOS T21 on T1.HISD_FECHA = T21.HITD_FECHA and T21.HITD_TOMA_FK = 21 WHERE T1.HISD_FECHA BETWEEN TRUNC(TO_DATE('$request->date_ini','yyyy.mm.dd HH24:MI:SS')) AND TRUNC(to_date(to_char(sysdate, 'yyyy.mm.dd')||' '||'23:59:59','yyyy.mm.dd HH24:MI:SS') + 1)) T2 ON T1.FechaHora =  T2.HISD_FECHA ORDER BY 1 ASC");
        } elseif ((empty($request->date_ini) == true) && (empty($request->date_fin) == false)) {

            $data = DB::select("SELECT * FROM (SELECT TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - 6 + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM dual CONNECT BY level <= (24 + (24 * (trunc(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - trunc(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) + 6)))) T1 LEFT JOIN (SELECT T1.HISD_SDATO_ID, TRUNC( HISD_FECHA,'HH24') HISD_FECHA, T1.HISD_NIVEL, T1.HISD_CONCE_SOLIDOS, T1.HISD_VOLUMEN_CAL,T1.HISD_Q_SACSA, T1.HISD_Q_DESVIO_RIO, T1.HISD_Q_CANCHIS, T1.HISD_Q_PRESA_CAL, T1.HISD_Q_PILLIRHUA, T1.HISD_Q_TOTAL_CAL, T2.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO as HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_T, T3.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_H, T3.HITD_Q_CAPTADO as HITD_Q_CAPTADO_H, T3.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_H, T21.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_M, T21.HITD_Q_CAPTADO as HITD_Q_CAPTADO_M, T21.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_M FROM EMP_HIDRO_SHEQUE_DATOS T1 LEFT JOIN EMP_HIDRO_TOMADATOS T2 on T1.HISD_FECHA = T2.HITD_FECHA and T2.HITD_TOMA_FK = 2 LEFT JOIN EMP_HIDRO_TOMADATOS T3 on T1.HISD_FECHA = T3.HITD_FECHA and T3.HITD_TOMA_FK = 3 LEFT JOIN EMP_HIDRO_TOMADATOS T21 on T1.HISD_FECHA = T21.HITD_FECHA and T21.HITD_TOMA_FK = 21 WHERE T1.HISD_FECHA BETWEEN TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS')) - 6 AND TRUNC(TO_DATE('$request->date_fin','yyyy.mm.dd HH24:MI:SS') + 1)) T2 ON T1.FechaHora = T2.HISD_FECHA ORDER BY To_Char(FECHAHORA,'YYYYMMDD') DESC,HORA ASC");
        } else {
            $data = DB::select("SELECT * FROM (SELECT TRUNC(SYSDATE) - 6 + (level / 24) - CASE MOD(level, 24) WHEN 0 THEN 1 ELSE 0 END FECHAHORA, MOD(LEVEL,24) HORA FROM dual CONNECT BY level <= (24 + (24 * (trunc(SYSDATE) - trunc(SYSDATE) + 6)))) T1 LEFT JOIN (SELECT T1.HISD_SDATO_ID, TRUNC( HISD_FECHA,'HH24') HISD_FECHA, T1.HISD_NIVEL, T1.HISD_CONCE_SOLIDOS, T1.HISD_VOLUMEN_CAL,T1.HISD_Q_SACSA, T1.HISD_Q_DESVIO_RIO, T1.HISD_Q_CANCHIS, T1.HISD_Q_PRESA_CAL, T1.HISD_Q_PILLIRHUA, T1.HISD_Q_TOTAL_CAL, T2.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_T, T2.HITD_Q_CAPTADO as HITD_Q_CAPTADO_T, T2.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_T, T3.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_H, T3.HITD_Q_CAPTADO as HITD_Q_CAPTADO_H, T3.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_H, T21.HITD_CONCEN_SOLIDOS as HITD_CONCEN_SOLIDOS_M, T21.HITD_Q_CAPTADO as HITD_Q_CAPTADO_M, T21.HITD_Q_TOTAL_DISP as HITD_Q_TOTAL_DISP_M FROM EMP_HIDRO_SHEQUE_DATOS T1 LEFT JOIN EMP_HIDRO_TOMADATOS T2 on T1.HISD_FECHA = T2.HITD_FECHA and T2.HITD_TOMA_FK = 2 LEFT JOIN EMP_HIDRO_TOMADATOS T3 on T1.HISD_FECHA = T3.HITD_FECHA and T3.HITD_TOMA_FK = 3 LEFT JOIN EMP_HIDRO_TOMADATOS T21 on T1.HISD_FECHA = T21.HITD_FECHA and T21.HITD_TOMA_FK = 21 WHERE T1.HISD_FECHA BETWEEN TRUNC(SYSDATE) - 6 AND TRUNC(SYSDATE + 1)) T2 ON T1.FechaHora = T2.HISD_FECHA ORDER BY To_Char(FECHAHORA,'YYYYMMDD') DESC,HORA ASC");
        }

        $select_tiempo = DB::select("SELECT EMP_PROFILES.T_EDICION from EMP_PROFILES join EMP_USERS on EMP_PROFILES.ID = EMP_USERS.PROFILE_ID WHERE EMP_USERS.API_TOKEN = '$request->api_token'");
        $tiempo_edicion = $select_tiempo[0]->t_edicion;

        foreach($data as $i => $i_value) {
            $i_value->t_edicion = $tiempo_edicion;
        }

        $data = collect($data);

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

        $sheque = ShequeDatos::select()
            ->where('hisd_fecha', '=', $data['hisd_fecha'])
            ->first();

        if (is_null($sheque)) {
            $sheque = ShequeDatos::create($data);
            $tomasT = TomaDatos::create(
                [
                    'hitd_fecha' => $data['hisd_fecha'],
                    'hitd_concen_solidos' => $data['hitd_concen_solidos_t'],
                    'hitd_q_captado' => $data['hitd_q_captado_t'],
                    'hitd_q_total_disp' => $data['hitd_q_total_disp_t'],
                    'hitd_toma_fk' => 3
                ]
            );


            $tomasH = TomaDatos::create(
                [
                    'hitd_fecha' => $data['hisd_fecha'],
                    'hitd_concen_solidos' => $data['hitd_concen_solidos_h'],
                    'hitd_q_captado' => $data['hitd_q_captado_h'],
                    'hitd_q_total_disp' => $data['hitd_q_total_disp_h'],
                    'hitd_toma_fk' => 2
                ]
            );

            $tomasM = TomaDatos::create(
                [
                    'hitd_fecha' => $data['hisd_fecha'],
                    'hitd_concen_solidos' => $data['hitd_concen_solidos_m'],
                    'hitd_q_captado' => $data['hitd_q_captado_m'],
                    'hitd_q_total_disp' => $data['hitd_q_total_disp_m'],
                    'hitd_toma_fk' => 21
                ]
            );


            $response = ['message' => 'Resource created successfully', 'error' => false, 'data' => ["sheque" => $sheque, "tomas_h" => $tomasH, "tomas_t" => $tomasT, "tomas_m" => $tomasM]];
        } else {
            $response = ['message' => 'Ya existe registro para esta fecha ' . $data['hisd_fecha'], 'error' => true];
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
        $obj = ShequeDatos::findOrFail($id);
        $data = $request->all();

        foreach ($obj->getFillable() as $aField) {
            if (empty($request->$aField) == false) {
                $obj->$aField = trim($request->$aField);
            }
        }

        $obj->hisd_q_sacsa = $data['hisd_q_sacsa'];
        $obj->hisd_q_pillirhua = $data['hisd_q_pillirhua'];
        $obj->hisd_q_total_cal = $data['hisd_q_total_cal'];

        $obj->save();

        $tomasT = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 3)
            ->first();

        if (!$tomasT) {
            $tomasT = new TomaDatos();
            $tomasT->hitd_fecha = $obj->hisd_fecha;
            $tomasT->hitd_toma_fk = 3;
        }

        $tomasT->hitd_concen_solidos = $data['hitd_concen_solidos_t'];
        $tomasT->hitd_q_captado = $data['hitd_q_captado_t'];
        $tomasT->hitd_q_total_disp = $data['hitd_q_total_disp_t'];
        $tomasT->save();

        $tomasH = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 2)
            ->first();

        if (!$tomasH) {
            $tomasH = new TomaDatos();
            $tomasH->hitd_fecha = $obj->hisd_fecha;
            $tomasH->hitd_toma_fk = 2;
        }

        $tomasH->hitd_concen_solidos = $data['hitd_concen_solidos_h'];
        $tomasH->hitd_q_captado = $data['hitd_q_captado_h'];
        $tomasH->hitd_q_total_disp = $data['hitd_q_total_disp_h'];
        $tomasH->save();

        $tomasM = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 21)
            ->first();

        if (!$tomasM) {
            $tomasM = new TomaDatos();
            $tomasM->hitd_fecha = $obj->hisd_fecha;
            $tomasM->hitd_toma_fk = 21;
        }

        $tomasM->hitd_concen_solidos = $data['hitd_concen_solidos_m'];
        $tomasM->hitd_q_captado = $data['hitd_q_captado_m'];
        $tomasM->hitd_q_total_disp = $data['hitd_q_total_disp_m'];
        $tomasM->save();


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
        $obj = ShequeDatos::findOrFail($id);

        $tomasT = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 3)
            ->first();

        $tomasH = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 2)
            ->first();

        $tomasM = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 21)
            ->first();

        $obj["hitd_concen_solidos_t"] = $tomasT ? $tomasT->hitd_concen_solidos : null;
        $obj["hitd_q_captado_t"] = $tomasT ? $tomasT->hitd_q_captado : null;
        $obj["hitd_q_total_disp_t"] = $tomasT ? $tomasT->hitd_q_total_disp : null;
        $obj["hitd_concen_solidos_h"] = $tomasH ? $tomasH->hitd_concen_solidos : null;
        $obj["hitd_q_captado_h"] = $tomasH ? $tomasH->hitd_q_captado : null;
        $obj["hitd_q_total_disp_h"] = $tomasH ? $tomasH->hitd_q_total_disp : null;
        $obj["hitd_concen_solidos_m"] = $tomasM ? $tomasM->hitd_concen_solidos : null;
        $obj["hitd_q_captado_m"] = $tomasM ? $tomasM->hitd_q_captado : null;
        $obj["hitd_q_total_disp_m"] = $tomasM ? $tomasM->hitd_q_total_disp : null;

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
        $obj = ShequeDatos::find($id);

        ShequeDatos::destroy($id);

        $tomasT = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 3)
            ->first()
            ->delete();

        $tomasH = TomaDatos::select()
            ->where('hitd_fecha', '=', $obj->hisd_fecha)
            ->where('hitd_toma_fk', '=', 2)
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
        return response()->json(["test" => "ok"]);
    }
}
