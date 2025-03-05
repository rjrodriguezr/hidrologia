<?php

namespace App\Http\Controllers;

use App\Meteorologia;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;
use DateTime;
use DateInterval;


class MeteorologiaController extends Controller
{


    /**
     * Display a listing of the resource for datatable jquery plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        $today = date("yy.m.d 23:59:59");
        $past_seven = date('yy.m.d 23:59:59', strtotime("-1 week"));
        if ($request->date_fin) {
            $format = 'Y.m.d H:i:s';
            $date = DateTime::createFromFormat($format, $request->date_fin);
            $date_past_seven = $date->sub(new DateInterval('P7D'))->format('Y.m.d H:i:s');
        }

        $query = "SELECT EMP_HIDRO_METEOROLOGIA.HIME_METEO_ID, 
                            TO_CHAR(EMP_HIDRO_METEOROLOGIA.HIME_FECHA, 'YYYY-MM-DD HH24:MI:SS') AS HIME_FECHA,
                            EMP_HIDRO_METEOROLOGIA.HIME_PLUVIOMETRIA AS PLUVIOMETRIA_1,
                            EMP_HIDRO_METEOROLOGIA.HIME_HUMEDAD_RELA AS HUMEDAD_RELA_1,
                            EMP_HIDRO_METEOROLOGIA.HIME_TEMP_MAX AS TEMP_MAX_1,
                            EMP_HIDRO_METEOROLOGIA.HIME_TEMP_MIN AS TEMP_MIN_1,
                            EMP_HIDRO_METEOROLOGIA.HIME_EVAPORACION AS EVAPORACION_1,
                            T2.HIME_PLUVIOMETRIA AS PLUVIOMETRIA_2,
                            T2.HIME_HUMEDAD_RELA AS HUMEDAD_RELA_2,
                            T2.HIME_TEMP_MAX AS TEMP_MAX_2,
                            T2.HIME_TEMP_MIN AS TEMP_MIN_2,
                            T2.HIME_EVAPORACION AS EVAPORACION_2,
                            T3.HIME_PLUVIOMETRIA AS PLUVIOMETRIA_3,
                            T3.HIME_HUMEDAD_RELA AS HUMEDAD_RELA_3,
                            T3.HIME_TEMP_MAX AS TEMP_MAX_3,
                            T3.HIME_TEMP_MIN AS TEMP_MIN_3,
                            T3.HIME_EVAPORACION AS EVAPORACION_3
                    FROM EMP_HIDRO_METEOROLOGIA 
                    INNER JOIN EMP_HIDRO_METEOROLOGIA T2 
                    ON EMP_HIDRO_METEOROLOGIA.HIME_FECHA = T2.HIME_FECHA
                    INNER JOIN EMP_HIDRO_METEOROLOGIA T3 
                    ON EMP_HIDRO_METEOROLOGIA.HIME_FECHA = T3.HIME_FECHA
                    WHERE T2.HIME_ESTACION_FK = 2
                    AND T3.HIME_ESTACION_FK = 3
                    AND EMP_HIDRO_METEOROLOGIA.HIME_ESTACION_FK = 1";

        $whereDos = "";

        if ((empty($request->date_ini) == false) && (empty($request->date_fin) == false)) {
            $whereDos .= " AND EMP_HIDRO_METEOROLOGIA.HIME_FECHA BETWEEN '$request->date_ini' AND '$request->date_fin'";
        } elseif ((empty($request->date_ini) == false) && (empty($request->date_fin) == true)) {
            $whereDos .= " AND EMP_HIDRO_METEOROLOGIA.HIME_FECHA >= '$request->date_ini'";
        } elseif ((empty($request->date_ini) == true) && (empty($request->date_fin) == false)) {
            $whereDos .= " AND EMP_HIDRO_METEOROLOGIA.HIME_FECHA BETWEEN '$date_past_seven' AND '$request->date_fin'";
        } else {
            $whereDos .= " AND EMP_HIDRO_METEOROLOGIA.HIME_FECHA BETWEEN '$past_seven' AND '$today'";
        }

        $select = $query . $whereDos;
        $datos = DB::select($select);
        $select_tiempo = DB::select("SELECT EMP_PROFILES.T_EDICION from EMP_PROFILES join EMP_USERS on EMP_PROFILES.ID = EMP_USERS.PROFILE_ID WHERE EMP_USERS.API_TOKEN = '$request->api_token'");
        $tiempo_edicion = $select_tiempo[0]->t_edicion;

        foreach($datos as $i => $i_value) {
            $i_value->t_edicion = $tiempo_edicion;
        }
        $data = collect($datos);

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

        $m1 = Meteorologia::select()
            ->where('hime_fecha', '=', $data['hime_fecha'])
            ->where('hime_estacion_fk', '=', 1)
            ->first();

        if (is_null($m1)) {
            $m1 = Meteorologia::create(
                [
                    'hime_fecha' => $data['hime_fecha'],
                    'hime_pluviometria' => $data['pluviometria_1'],
                    'hime_humedad_rela' => $data['humedad_rela_1'],
                    'hime_temp_max' => $data['temp_max_1'],
                    'hime_temp_min' => $data['temp_min_1'],
                    'hime_evaporacion' => $data['evaporacion_1'],
                    'hime_estacion_fk' => 1
                ]
            );

            $m2 = Meteorologia::create(
                [
                    'hime_fecha' => $data['hime_fecha'],
                    'hime_pluviometria' => $data['pluviometria_2'],
                    'hime_humedad_rela' => $data['humedad_rela_2'],
                    'hime_temp_max' => $data['temp_max_2'],
                    'hime_temp_min' => $data['temp_min_2'],
                    'hime_evaporacion' => $data['evaporacion_2'],
                    'hime_estacion_fk' => 2
                ]
            );

            $m3 = Meteorologia::create(
                [
                    'hime_fecha' => $data['hime_fecha'],
                    'hime_pluviometria' => $data['pluviometria_3'],
                    'hime_humedad_rela' => $data['humedad_rela_3'],
                    'hime_temp_max' => $data['temp_max_3'],
                    'hime_temp_min' => $data['temp_min_3'],
                    'hime_evaporacion' => $data['evaporacion_3'],
                    'hime_estacion_fk' => 3
                ]
            );


            $response = ['message' => 'Resource created successfully', 'error' => false, 'data' => ["m1" => $m1, "m2" => $m2, "m3" => $m3]];
        } else {
            $response = ['message' => 'Ya existe registro para esta fecha ' . $data['hime_fecha'], 'error' => true];
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
        $data = $request->all();

        //DB::enableQueryLog();
        $m1 = Meteorologia::findOrFail($id);
        $m1->hime_pluviometria = $data['pluviometria_1'];
        $m1->hime_humedad_rela = $data['humedad_rela_1'];
        $m1->hime_temp_max = $data['temp_max_1'];
        $m1->hime_temp_min = $data['temp_min_1'];
        $m1->hime_evaporacion = $data['evaporacion_1'];
        $m1->save();
        //var_dump(DB::getQueryLog());

        $m2 = Meteorologia::select()
            ->where('hime_fecha', '=', $m1->hime_fecha)
            ->where('hime_estacion_fk', '=', 2)
            ->first();

        $m2->hime_pluviometria = $data['pluviometria_2'];
        $m2->hime_humedad_rela = $data['humedad_rela_2'];
        $m2->hime_temp_max = $data['temp_max_2'];
        $m2->hime_temp_min = $data['temp_min_2'];
        $m2->hime_evaporacion = $data['evaporacion_2'];
        $m2->save();

        $m3 = Meteorologia::select()
            ->where('hime_fecha', '=', $m1->hime_fecha)
            ->where('hime_estacion_fk', '=', 3)
            ->first();

        $m3->hime_pluviometria = $data['pluviometria_3'];
        $m3->hime_humedad_rela = $data['humedad_rela_3'];
        $m3->hime_temp_max = $data['temp_max_3'];
        $m3->hime_temp_min = $data['temp_min_3'];
        $m3->hime_evaporacion = $data['evaporacion_3'];
        $m3->save();

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
        $m1 = Meteorologia::findOrFail($id);
        $m2 = Meteorologia::select()
            ->where('hime_fecha', '=', $m1->hime_fecha)
            ->where('hime_estacion_fk', '=', 2)
            ->first();
        $m3 = Meteorologia::select()
            ->where('hime_fecha', '=', $m1->hime_fecha)
            ->where('hime_estacion_fk', '=', 3)
            ->first();

        $obj = array();
        $obj["hime_meteo_id"] = $m1->hime_meteo_id;
        $obj["hime_fecha"] = $m1->hime_fecha;
        $obj["pluviometria_1"] = $m1->hime_pluviometria;
        $obj["humedad_rela_1"] = $m1->hime_humedad_rela;
        $obj["temp_max_1"] = $m1->hime_temp_max;
        $obj["temp_min_1"] = $m1->hime_temp_min;
        $obj["evaporacion_1"] = $m1->hime_evaporacion;

        $obj["pluviometria_2"] = $m2->hime_pluviometria;
        $obj["humedad_rela_2"] = $m2->hime_humedad_rela;
        $obj["temp_max_2"] = $m2->hime_temp_max;
        $obj["temp_min_2"] = $m2->hime_temp_min;
        $obj["evaporacion_2"] = $m2->hime_evaporacion;

        $obj["pluviometria_3"] = $m3->hime_pluviometria;
        $obj["humedad_rela_3"] = $m3->hime_humedad_rela;
        $obj["temp_max_3"] = $m3->hime_temp_max;
        $obj["temp_min_3"] = $m3->hime_temp_min;
        $obj["evaporacion_3"] = $m3->hime_evaporacion;

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
        $m1 = Meteorologia::findOrFail($id);

        Meteorologia::destroy($id);

        $m2 = Meteorologia::select()
            ->where('hime_fecha', '=', $m1->hime_fecha)
            ->where('hime_estacion_fk', '=', 2)
            ->first()
            ->delete();

        $m3 = Meteorologia::select()
            ->where('hime_fecha', '=', $m1->hime_fecha)
            ->where('hime_estacion_fk', '=', 3)
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
