<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LoginController extends Controller
{
    //
    /**
     * Show the login form.
     */
    protected $data;
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

        $user = User::where('email', $request->email)->first();
        $role = null;
        if($user){
            $role = $user->roles()->first(); // assuming 1 role per user
        }

        if (! $role || ! $role->status) {
            Session::flash('error', 'Your role is disabled. Please contact administrator.');
            return redirect()->back()->withInput($request->only('email'))
                ->withErrors(['email' => 'Your role is disabled.']);
        }
        if (Auth::attempt($request->only('email', 'password'))) {
            Session::flash('success', 'Login successful.');
            return redirect()->route('dashboard.dashboard');
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

    public function getProfile(){
        $this->data['pageTitle'] = 'Admin User';
        $adminUser = Auth::user();
        $this->data['adminUser'] = $adminUser;
        return view('admin.profile',$this->data);
    }

    public function updateProfile(Request $request){
        $user = Auth::user();
        $role = 'super_admin';
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'regex:/^[A-Za-z\s]+$/',
                'max:255',
            ],
            'email'      => [
                'required',
                'email',
                Rule::unique('users','email')->where(function ($query) use ($user,$role){
                    $query->where('role', $role) ->where('id', '!=', $user->id) ->whereNull('deleted_at'); // Ignore soft-deleted users
                }),
            ],
            'phone'      => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users','phone')->where(function ($query) use ($user,$role){
                    $query->where('role', $role) ->where('id', '!=', $user->id) ->whereNull('deleted_at'); // Ignore soft-deleted users
                }),
            ],
            'profile_picture' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,svg,gif',
                'max:5120'
            ],
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }
        DB::beginTransaction();
        try{
            $profile_picture = $user->profile_picture;
            $data = $request->all();
            if($request->hasFile('profile_picture')){
                $profile_picture = uploadImage($request->file('profile_picture'),'profile_picture/'.$user->id);
            }
            $data['profile_picture'] = $profile_picture;
            $user->update($data);

            DB::commit();
            Session::flash('    ', "Profile updated successfully!");
            return redirect()->route('profile');
        } catch(\Exception $e){

            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function changePassword(Request $request){
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:8',
            'cpassword' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $user = Auth::user();
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Old password is incorrect.'])->withInput();
        }

        $user->password = Hash::make($request->new_password);
        $user->save();
        Session::flash('success', 'Password changed successfully.');
        return redirect()->route('profile');
    }
}
