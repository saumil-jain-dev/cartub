<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    //
    /**
     * Show the login form.
     */
    public function showLoginForm()
    {
        return view('admin.auth.login'); // Ensure this view exists
    }

    /**
     * Handle the login request.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [  
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }
        if (Auth::attempt($request->only('email', 'password'))) {
            Session::flash('success', 'Login successful.');
            return redirect()->route('dashboard');
        }
        Session::flash('error', 'Invalid credentials.');
        return redirect()->back()->withInput($request->only('email'))
                                 ->withErrors(['email' => 'Invalid credentials.']);
    }

    /**
     * Handle the logout request.
     */
    public function logout(Request $request)
    {
        Auth::logout(); // Log out the user
        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token
        return redirect()->route('login')->with('success', 'Logged out successfully.'); 
    }
}
