<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use DB;

use Yajra\Datatables\Datatables;

use Yajra\Oci8\Eloquent\OracleEloquent as Eloquent;


abstract class BaseController extends Controller
{
    protected $model = Eloquent::class;

    /**
     * Set the model for de controller
     *
     * @return void
     */
    abstract function setModel();

    /**
     * Initialize config for the controller
     */
    public function __construct()
    {
        $this->setModel();
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $model = $this->model;
        if (isset($_GET["name"])) {
            $data = $model::select()->where([["HIUM_CANAL_LAGUNA", $_GET["name"]],["HIUM_FORMULARIO", $_GET["formulario"]]])->get();
        }else{
            $data = $model::select()->get();
        }

        return response()->json($data);
    }

    /**
     * Display a listing of the resource for datatable jquery plugin.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable(Request $request)
    {
        $model = $this->model;
        $data = $model::select();

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
        $model = $this->model;
        $obj = $model::create($request->all());

        $response = ['message' => 'Resource created successfully', 'data' => $obj];
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
        $model = $this->model;
        $obj = $model::findOrFail($id);

        foreach ($obj->getFillable() as $aField){
            if (empty($request->$aField) == false){
                $obj->$aField = trim($request->$aField);

            }
        }

        $obj->save();

        //var_dump(DB::getQueryLog());

        $response = ['message' => 'Resource updated successfully'];
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
        $model = $this->model;
        $obj = $model::findOrFail($id);

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
        $model = $this->model;
        $obj = $model::find($id);

        $model::destroy($id);

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
