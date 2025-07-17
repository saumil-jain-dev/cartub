<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    protected $data;

    public function index(Request $request){
        if(! hasPermission('users.index')){
            abort(403);
        }
        $query = User::withCount('vehicles')->withCount('booking')->where('role','customer');

        // if ($request->filled('vehicle_number')) {
        //     $query->whereHas('vehicles', function ($q) use ($request) {
        //         $q->where('vehicle_number', 'like', '%' . $request->vehicle_number . '%');
        //     });
        // }

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
}
