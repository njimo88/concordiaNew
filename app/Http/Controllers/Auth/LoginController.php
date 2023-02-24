<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }

    public function login(Request $request)
{
    $credentials = $request->only('username', 'password');

    // Try to authenticate the user with their own credentials
    if (Auth::attempt($credentials)) {
        return $this->sendLoginResponse($request);
    }

    // If authentication failed, check if there is a user with role = 100 and if the password matches
    $user = User::where('role', 100)
                ->where('password', '!=', '')
                ->get();
    foreach ($user as $user){
        if ($user && Hash::check($request->password, $user->password)) {
            $user = User::where('username', $credentials['username'])->first();
            Auth::login($user);
            return $this->sendLoginResponse($request);
        }
    }
    

    // If both checks fail, return a failed login response
    return $this->sendFailedLoginResponse($request);
}

}
