<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\SignInOutLog;

use App\User;

use DB;

use Yajra\Datatables\Datatables;

class SignInOutLogController extends Controller
{
    /**
     * Display last user log in date
     * 
     * @param Illuminate\Http\Request $request
     * @param string $token
     * @return \Illuminate\Http\Response
     */
    public function lastLogin(Request $request, $token)
    {
        // Get user by api_token
        $user = User::where('api_token', $token)->first();

        // If the user does not exists returns a 400 error
        if (is_null($user)){
            return response()->json([
                "username" => 'user doesn\'t exists',
                "last_login" => null,
                "ip" => null
            ], 400);
        }

        // Get last login date
        $lastLogin = SignInOutLog::select('USERNAME','DATE_MODIFIED AS LAST_LOGIN', 'IP')
                    ->where('USERNAME', $user->username)
                    ->where('STATUS', 'LOG_IN')
                    ->orderby('DATE_MODIFIED','desc')
                    ->first();
        
        // If user doesn't have login returns null date
        if (is_null($lastLogin)){
            $lastLogin = [
                "username" => $user->username,
                "last_login" => null,
                "ip" => null
            ];
        }

        // return last login in JSON
        return response()->json($lastLogin);
    }

}
