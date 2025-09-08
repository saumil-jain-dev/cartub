<?php

namespace App\Http\Controllers\Admin\Cleaner;

use App\Http\Controllers\Controller;
use App\Jobs\Customer\SendMailJob;
use App\Models\CleanerEarning;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CleanerController extends Controller
{
    //
    protected $data;
    public function index(Request $request){
        if(! hasPermission('cleaners.index')){
            abort(403);
        }
        $query = User::where('role','cleaner')->withCount(['ratings as average_rating' => function ($q) {
        $q->select(DB::raw('coalesce(avg(rating),0)'));
        }])->withCount('completed_job');

        if ($request->filled('availability')) {
            $query->where('is_available', $request->availability);
        }
        if ($request->filled('ratting')) {
            $query->withCount('average_rating')
            ->having('average_rating_count', '=', $request->ratting);
        }

        if ($request->filled('jobs_completed')) {
            if ($request->jobs_completed == 1) {
                $query->having('completed_job_count', '>=', 1)
                    ->having('completed_job_count', '<=', 50);
            } elseif ($request->jobs_completed == 2) {
                $query->having('completed_job_count', '>=', 51)
                    ->having('completed_job_count', '<=', 100);
            } elseif ($request->jobs_completed == 3) {
                $query->having('completed_job_count', '>=', 101)
                    ->having('completed_job_count', '<=', 200);
            }
        }

        $users = $query->get()->map(function ($user) {
            $user->profile_image_url = getImageAdmin($user->profile_picture);
            return $user;
        });
        $this->data['pageTitle'] = 'Cleaners List';
        $this->data['cleaners'] = $users;
        return view("admin.cleaners.index", $this->data);
    }

    public function create(){
        if(! hasPermission('cleaners.create')){
            abort(403);
        }
        $this->data['pageTitle'] = 'Add Cleaner';
        return view("admin.cleaners.create", $this->data);
    }

    public function store(Request $request){

        $validator = Validator::make($request->all(), [
            'email'      => [
                'required',
                'email',
                Rule::unique('users', 'email')
                    ->where('role', 'cleaner')
                    ->whereNull('deleted_at') // Ignore soft-deleted users
            ],
            'phone'      => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users', 'phone')
                    ->where('role', 'cleaner')
                    ->whereNull('deleted_at') // Ignore soft-deleted users
            ],
            'password' => 'required|min:6|confirmed',
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|in:1,2,3',
            'dob' => 'required|date',
            'address' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|digits_between:4,10',
            'country' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->input('fname').' '.$request->input('lname');
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->city = $request->city;
            $user->zipcode = $request->zipcode;
            $user->country = $request->country;
            $user->dob = $request->dob;
            $user->role = 'cleaner';
            $user->save();

            $user->assignRole('cleaner');
            DB::commit();
            //Send register mail
            $userData = [
                'customer_name' => $user->name,
                'to_email' => $user->email,
                'password' => $request->password,
                'userData' => $user,
                '_blade' => 'account-create',
                'subject' => 'Your Cleaner Account Created Successfully',
            ];
            SendMailJob::dispatch($userData);
            Session::flash('success', 'Cleaner Added Successfully');
            return redirect()->route('cleaners.index');


        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function edit($id){
        if(! hasPermission('cleaners.edit')){
            abort(403);
        }
        $user = User::findOrFail($id);
        $this->data['pageTitle'] = 'Edit Cleaner';
        $this->data['cleaner'] = $user;
        return view("admin.cleaners.edit", $this->data);
    }

    public function update(Request $request){
        if(! hasPermission('cleaners.edit')){
            abort(403);
        }
        $user = User::findOrFail($request->id);
        $validator = Validator::make($request->all(), [
            'email'      => [
                'required',
                'email',
                Rule::unique('users','email')->where(function ($query) use ($user){
                    $query->where('role', 'cleaner') ->where('id', '!=', $user->id) ->whereNull('deleted_at'); // Ignore soft-deleted users
                }),
            ],
            'phone'      => [
                'required',
                'numeric',
                'digits:10',
                Rule::unique('users','phone')->where(function ($query) use ($user){
                    $query->where('role', 'cleaner') ->where('id', '!=', $user->id) ->whereNull('deleted_at'); // Ignore soft-deleted users
                }),
            ],
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'gender' => 'required|in:1,2,3',
            'dob' => 'required|date',
            'address' => 'required|string',
            'city' => 'required|string',
            'zipcode' => 'required|digits_between:4,10',
            'country' => 'required|string',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        $user->name = $request->input('fname').' '.$request->input('lname');
        $user->email = $request->email;
        $user->phone = $request->phone;
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->gender = $request->gender;
        $user->address = $request->address;
        $user->city = $request->city;
        $user->zipcode = $request->zipcode;
        $user->country = $request->country;
        $user->dob = $request->dob;
        $user->save();

        Session::flash('success', 'Cleaner Updated Successfully');
        return redirect()->route('cleaners.index');
    }

    public function destroy($id){
        if(! hasPermission('cleaners.destroy')){
            abort(403);
        }
        try {
            $user = User::findOrFail($id);
            $user->delete();
            Session::flash('success', 'Cleaner deleted successfully.');
            return response()->json(['success' => true, 'message' => 'Cleaner deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function performanceReports(Request $request){
        if(! hasPermission('cleaners.index')){
            abort(403);
        }
        $query = User::where('role','cleaner')->withCount(['ratings as average_rating' => function ($q) {
        $q->select(DB::raw('coalesce(avg(rating),0)'));
        }])->withCount('completed_job');

        $users = $query->get()->map(function ($user) {
            $user->profile_image_url = getImageAdmin($user->profile_picture);
            return $user;
        });
        $this->data['pageTitle'] = 'Performance Reports';
        $this->data['cleaners'] = $users;
        return view("admin.cleaners.performance-reports", $this->data);
    }

    public function earningsDetails($id){
        if(! hasPermission('cleaners.index')){
            abort(403);
        }

        $cleaner = User::where('role', 'cleaner')->findOrFail($id);

        // Get all completed bookings with earnings for this cleaner
        $earnings = CleanerEarning::with([
        'booking.service' // eager load booking and service
        ])
        ->where('cleaner_id', $id)
        ->whereNull('deleted_at')
        ->whereHas('booking', function ($q) {
            $q->where('status', 'completed');
        })
        ->orderBy('earned_on', 'desc')
        ->get();
        // dd($earnings);
        // Calculate totals
        $totalEarnings = $earnings->sum('amount');
        $totalTips = $earnings->sum('tip');
        $totalAmount = $totalEarnings + $totalTips;

        $this->data['pageTitle'] = 'Cleaner Earnings Details';
        $this->data['cleaner'] = $cleaner;
        $this->data['earnings'] = $earnings;
        $this->data['totalEarnings'] = $totalEarnings;
        $this->data['totalTips'] = $totalTips;
        $this->data['totalAmount'] = $totalAmount;

        return view("admin.cleaners.earnings-details", $this->data);
    }
}
