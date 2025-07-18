<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    //
    protected $data;

    public function index(Request $request){
        if(! hasPermission('users.index')){
            abort(403);
        }
        $query = User::withCount('vehicles')->withCount('booking')->where('role','customer');

        if ($request->filled('vehicle_count')) {
            $query->withCount('vehicles')
                ->having('vehicles_count', '=', $request->vehicle_count);
        }

        $users = $query->get();
        $this->data['pageTitle'] = 'User List';
        $this->data['users'] = $users;
        return view("admin.users.index", $this->data);
    }

    public function getProfile($id) {
        $this->data['pageTitle'] = 'User Profile';
        $user = User::withCount('vehicles')->withCount('booking')->where('id', $id)->first();
        $recentBooking = Booking::with(['cleaner','customer','vehicle','service','payment','washType'])->where('customer_id', $id)->orderBy('id','desc')->get()->take(2);
        $bookings = Booking::with(['cleaner','customer','vehicle','service','payment','washType'])->where('customer_id', $id)->orderBy('id','desc')->get();
        $vehicles = Vehicle::where('customer_id',$id)->orderBy('id','desc')->get();

        $this->data['recentBooking'] = $recentBooking;
        $this->data['bookings'] = $bookings;
        $this->data['vehicles'] = $vehicles;
        $this->data['user'] = $user;
        return view("admin.users.profile", $this->data);
    }

    public function update(Request $request) {
        $user_id = $request->input("id");
        $user = User::findOrFail($user_id);
        $role = 'customer';
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
            
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }
        
        $user->name = $request->input("name");
        $user->email = $request->input("email");
        $user->phone = $request->input("phone");
        $user->is_active = $request->input("status");
        $user->save();

        Session::flash('success', "User updated successfully");
        return redirect()->route('users.index');
    }
    public function destroy($id){
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json(['success' => true, 'message' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
