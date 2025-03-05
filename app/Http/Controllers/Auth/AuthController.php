<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use App\SignInOutLog;
use Carbon\Carbon;
use DB;
use Log;

class AuthController extends Controller
{
    protected $maxLoginAttempts = 2;
    protected $redirectTo = 'home/';
    protected $username = 'username';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('username', 'password');
        $credentials['username'] = strtoupper($credentials['username']);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $this->recordLogin($credentials['username']);
            return $this->authenticated($request, Auth::user());
        }

        $adServer = config('auth.active_directory_server');
        $domain = config('auth.active_directory_domain');
        $username = $domain . $credentials['username'];
        $password = $credentials['password'];

        if ($this->connectActiveDirectory($adServer, $username, $password, $credentials['username'])) {
            if (Auth::attempt($credentials, $request->boolean('remember'))) {
                $request->session()->regenerate();
                $this->recordLogin($credentials['username']);
                return redirect()->intended($this->redirectTo);
            }
        }

        return back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => __('auth.failed'),
            ]);
    }

    public function logout(Request $request)
    {
        $username = $request->user() ? $request->user()->username : ($request->has('username') ? $request->input('username') : 'not_identified');
        Log::info($username);
        
        $this->recordLogout($username);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    protected function authenticated(Request $request, $user)
    {
        $request->session()->forget('password_expired_id');

        $password_updated_at = $user->password_changed_at;
        if ($password_updated_at == null) {
            $password_updated_at = Carbon::now()->subDays(config('auth.password_expires_days'));
        }
        
        $password_expiry_days = config('auth.password_expires_days');
        $password_expiry_at = Carbon::parse($password_updated_at)->addDays($password_expiry_days);
        
        if ($password_expiry_at->lt(Carbon::now())) {
            $request->session()->put('password_expired_id', $user->id);
            Auth::logout();
            return redirect('/passwordExpiration')->with('message', "Su contraseña ha expirado, necesita cambiarla.");
        }

        return redirect()->intended($this->redirectTo);
    }

    public function connectActiveDirectory($adServer, $username, $password, $id_username)
    {
        $ldapconn = ldap_connect($adServer);
        $ldapbind = @ldap_bind($ldapconn, $username, $password);
        
        if ($ldapbind) {
            $data = DB::select("SELECT EMP_USERS.id FROM EMP_USERS WHERE EMP_USERS.username = ?", [$id_username]);
            $resultArray = json_decode(json_encode($data), true);
            
            if (empty($resultArray)) {
                return false;
            }

            $id = $resultArray[0]['id'];
            $user = User::find($id);

            if (!$user) {
                return false;
            }

            if (!Hash::check($password, $user->password)) {
                $user->password = Hash::make($password);
                $user->password_changed_at = Carbon::now();
                $user->save();
            }
            
            return true;
        }
        return false;
    }

    protected function recordLogin($username) {
        SignInOutLog::create([
            'USERNAME' => $username,
            'IP' => request()->ip(),
            'STATUS' => 'LOG_IN'
        ]);
    }

    protected function recordLogout($username) {
        SignInOutLog::create([
            'USERNAME' => $username,
            'IP' => request()->ip(),
            'STATUS' => 'LOG_OUT'
        ]);
    }
}