<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/bbb';
    public function showLoginForm()
{
    // Store the previous URL in the session
    session(['url.intended' => url()->previous()]);

    return view('auth.login');
}

protected function sendLoginResponse(Request $request)
{
    $request->session()->regenerate();

    // Get the intended URL, or the URL to redirect to if there is no intended URL
    $redirectTo = session('url.intended', $this->redirectPath());

    return redirect()->intended($redirectTo);
}



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
public function logout()
    {
        // log the user out
        Auth::logout();
        // redirect the user to the previous page
        return Redirect::back();
    }
}
