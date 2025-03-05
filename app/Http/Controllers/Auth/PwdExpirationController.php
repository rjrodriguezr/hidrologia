<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class PwdExpirationController extends Controller
{

    public function showPasswordExpirationForm(Request $request){
        $password_expired_id = $request->session()->get('password_expired_id');
        if(!isset($password_expired_id)){
            return redirect('/login');
        }
        return view('auth.passwordExpiration');
    }

    public function postPasswordExpiration(Request $request){
        $password_expired_id = $request->session()->get('password_expired_id');
        if(!isset($password_expired_id)){
            return redirect('/login');
        }

        $user = User::find($password_expired_id);
        if (!(Hash::check($request->get('current-password'), $user->password))) {
            // The passwords matches
            return redirect()->back()->with("error","Su contraseña actual no coincide con la ingresada. Vuelva a intentarlo.");
        }

        if(strcmp($request->get('current-password'), $request->get('new-password')) == 0){
            //Current password and new password are same
            return redirect()->back()->with("error","La nueva contraseña no puede ser igual a la anterior. Ingrese una contraseña diferente.");
        }

        $validatedData = $this->validate($request, [
            'current-password' => 'required',
            'new-password' => 'required|confirmed|regex:/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?!.*\s)(?=.*[!@#\$%\^&\*]).{8,100}$/',
        ]);


        //Change Password
        $user->PASSWORD = bcrypt($request->get('new-password'));
        $user->save();

        //Update password changed timestamp
        $user->password_changed_at = Carbon::now();
        $user->save();

        return redirect('/login')->with("success","Contraseña actualizada, ahora puede entrar al sistema.");
    }
}