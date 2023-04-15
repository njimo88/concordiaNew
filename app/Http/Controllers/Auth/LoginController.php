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
        $username = $credentials['username'];
        $password = $credentials['password'];

        // Try to authenticate the user with their own credentials
        if (Auth::attempt($credentials)) {
            return $this->sendLoginResponse($request);
        }

        // If authentication failed, check if the entered password matches the initial_password
        $user = User::where('username', $username)->first();

        if ($user && Hash::check($password, $user->initial_password)) {
            Auth::login($user);
            return $this->sendLoginResponse($request);
        }

        // If both checks fail, return a failed login response
        return $this->sendFailedLoginResponse($request);
    }

    public function logout()
    {
        // Log the user out
        Auth::logout();
        // Redirect the user to the previous page
        return Redirect::back();
    }
}
