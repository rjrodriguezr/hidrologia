<?php

namespace App\Http\Controllers;

use App\Events;
use App\TomaDatos;
use Yajra\Datatables\Datatables;
use Illuminate\Http\Request;
use App\Http\Requests;
use DB;


class EventsController extends Controller
{
	/**
     * Display a listing of the resource for datatable jquery plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request){
        $data = Events::select(
        	"hiev_evento_id",
            DB::raw("TO_CHAR(hiev_fecha, 'YYYY-MM-DD') AS hiev_fecha"),
            "hiev_trabajos_realizados", 
            "hiev_eventos_importantes", 
            "hiev_atencion_camaras", 
            "hiev_inspecciones",
            "hiev_ubicacion");
        return Datatables::of($data)->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $data = $request->all();
        Events::create($data);
        $response = ['message' => 'Resgitro creado satistactoriamente', 'error' => false];
        return response()->json($response, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $obj = Events::findOrFail($id);
        $obj->save();
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
        $obj = Events::findOrFail($id);
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
        $obj = Events::find($id);
        Events::destroy($id);
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
