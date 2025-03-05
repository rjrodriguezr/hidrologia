<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Profile;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Support\Facades\Storage; 
use Yajra\Datatables\Datatables;

class ProfileController extends Controller 
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Profile::select("id", "name")
            ->orderBy('name', 'asc')->get();

        return response()->json($data);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function datatable()
    {
        $data = Profile::select("id", "name")
            ->orderBy('name', 'asc')->get();

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
        $obj = Profile::create($request->all());

        $obj->permissions()->attach($request->permissions);

        $response = ['error' => false, 'message' => 'Perfil creado con éxito', 'data' => $obj];
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
        $obj = Profile::with("permissions")->findOrFail($id);
        return response()->json($obj);
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
        $obj = Profile::find($id);
        $obj->name = $request->name;
        $obj->save();

        $obj->permissions()->detach();
        $obj->permissions()->attach($request->permissions);

        $response = ['error' => false, 'message' => 'Perfil editado con éxito', 'data' => $obj];
        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Profile::destroy($id);

        $response = ['error' => false, 'message' => 'Eliminado éxitoso'];
        return response()->json($response);
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function permissions()
    {
        $data = Permission::select("EMP_PERMISSIONS.id", "EMP_PERMISSIONS.name", "p.name as parent")
            ->join("EMP_PERMISSIONS p", "EMP_PERMISSIONS.parent", "=", "p.id")
            ->whereNotNull("EMP_PERMISSIONS.parent")
            ->orderBy('p.name', 'asc')->get();

        return response()->json($data);
    }

    public function showByUser($token)
    {
        $usr = User::where('api_token', $token)->first();

        $obj = Profile::with(
            [
                "permissions" => function ($query){
                    $query->with("objParent");
                    $query->orderBy("orden");
                }
            ]
        )->findOrFail($usr->profile_id);
        return response()->json($obj);
    }
}
