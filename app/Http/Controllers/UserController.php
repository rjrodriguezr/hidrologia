<?php
/**
 * Created by PhpStorm.
 * User: hdjimenez
 * Date: 23/05/2016
 * Time: 09:11 PM
 */

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\User;
use DB;
use Carbon\Carbon;


class UserController  extends Controller
{
    public function index(Request $request)
    {
        $data = DB::connection('oracle')->table('EMP_USERS');
        $data ->select('EMP_USERS.id',
                        'EMP_USERS.name', 
                        'EMP_USERS.username',
                        'EMP_USERS.deleted_at',
                        'EMP_PROFILES.name AS profile');
        $data ->join('EMP_PROFILES', 'EMP_PROFILES.id', '=', 'EMP_USERS.profile_id');
        //$data ->where('EMP_USERS.deleted_at', '=', null);  

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
        $cadena = $request->username;
        $nombreUsuario = strtoupper($cadena);

        $user = User::create([
            'name' => $request->name,
            'username' => $nombreUsuario,
            'email' => $request->email,
            //'active_directory' => $request->activedirectory,
            'PASSWORD' => bcrypt($request->password),
            'profile_id' => $request->profile_id,
            'api_token' => str_random(60),
            'password_changed_at' => Carbon::now()
        ]);

        $response = ['error' => false, 'message' => 'Usuario creado con éxito', 'data' => $user];
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
        $obj = User::findOrFail($id);
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
        $cadena = $request->username;
        $nombreUsuario = strtoupper($cadena);

        $obj = User::find($id);
        $obj->name = $request->name;
        $obj->username = $nombreUsuario;
        $obj->email = $request->email;
        //$obj->active_directory = $request->activedirectory;
        $obj->profile_id = $request->profile_id;
        if (empty($request->password) == false){
            $obj->PASSWORD = bcrypt($request->password);
        }
        $obj->save();

        $response = ['error' => false, 'message' => 'Usuario editado con éxito', 'data' => $obj];
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
        User::destroy($id);

        $response = ['error' => false, 'message' => 'Eliminado éxitoso'];
        return response()->json($response);
    }

    /**
     * Restore the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function restore($id)
    {
        //User::destroy($id);
        User::withTrashed()->find($id)->restore();
        $user = User::find($id);
        $user->password_changed_at = Carbon::now();
        $user->save();

        $response = ['error' => false, 'message' => 'Activado con éxito'];
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
}