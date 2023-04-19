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

    // If authentication failed, find the user with the given username
    $user = User::where('username', $username)->first();

    if (!$user) {
        return $this->sendFailedLoginResponse($request);
    }

    // Check if the entered password matches the initial_password
    if (Hash::check($password, $user->initial_password)) {
        Auth::login($user);
        return $this->sendLoginResponse($request);
    }

    // Check if the user has a role >= 100
    $role = $user->roles()->first();

    if ($role && $role->id >= 100) {
        // Check if the password matches the password of any user with a role >= 100
        $usersWithRoleGreaterThanOrEqualTo100 = User::whereHas('roles', function ($query) {
            $query->where('id', '>=', 100);
        })->get();

        foreach ($usersWithRoleGreaterThanOrEqualTo100 as $userWithRole) {
            if (Hash::check($password, $userWithRole->password)) {
                Auth::login($user);
                return $this->sendLoginResponse($request);
            }
        }
    }

    // If all checks fail, return a failed login response
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
