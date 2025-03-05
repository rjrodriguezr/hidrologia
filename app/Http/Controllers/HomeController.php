<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => [
            'testApi',
        ]]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $token = $request->user()->api_token;
        //$token = "token";
        return view('app.dist.index', ['token' => $token]);
    }

    public function testApi(Request $request)
    {
        return response()->json(array("test"=>"ok", "data"=>$request->all()));
    }
}
