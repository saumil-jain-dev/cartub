<?php

namespace App\Http\Controllers\Admin\Coupons;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CouponController extends Controller
{
    //
    protected $data;
    public function index(Request $request){

        if(! hasPermission('coupons.index')){
            abort(403);
        }
        $this->data['pageTitle'] = 'All Coupons';
        
        $couponData = Coupon::orderBy('id','desc')->get();
        $userData = User::where('role','customer')->get();

        $this->data['couponData'] = $couponData;
        $this->data['userData'] = $userData;
        
        return view('admin.coupons.index',$this->data);
    }

    public function checkCode(Request $request)
    {
        $query = Coupon::where('code', $request->code);

        if ($request->id) {
            $query->where('id', '!=', $request->id);
        }

        $exists = $query->exists();

        return response()->json(!$exists);
    }

    public function store(Request $request){
        
        DB::beginTransaction();
        try{
            $user_ids = $zipcodes = null;
            if($request->applicable_to == "users"){
                $user_ids = $request->users ? json_encode($request->users) : null;
            }
            if($request->applicable_to == "area"){
                $zipcodes = $request->zipcodes ?  json_encode(explode(',', $request->zipcodes)) : null;
            }

            $coupon = Coupon::updateOrCreate(
                ['id' => $request->id], // update if id present, else insert
                [
                    'code'           => strtoupper($request->code),   // force uppercase
                    'valid_from'     => $request->start_date,
                    'valid_until'       => $request->end_date,
                    'discount_value' => $request->discount_value,
                    'discount_type'  => $request->discount_type,
                    'is_active'      => $request->is_active ?? 1,
                    'user_ids'       => $user_ids,
                    'zipcodes'       => $zipcodes,
                ]
            );
            DB::commit();
            $message = "Coupon created successfully";
            if($request->id){
                $message = "Coupon updated successfully";
            }

            Session::flash('success', $message);
            return redirect()->route('coupons.index');
            
        } catch(\Exception $e){
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit( $id){
        $coupon = Coupon::findOrFail($id);
        if ($coupon->user_ids && !$coupon->zipcodes) {
        $applicable_to = 'users';
        } elseif (!$coupon->user_ids && $coupon->zipcodes) {
            $applicable_to = 'area';
        } else {
            $applicable_to = 'none';
        }
        return response()->json([
            'id'             => $coupon->id,
            'code'           => $coupon->code,
            'start_date'     => $coupon->valid_from,
            'end_date'       => $coupon->valid_until,
            'discount_type'  => $coupon->discount_type,
            'discount_value' => $coupon->discount_value,
            'is_active'      => $coupon->is_active,
            'applicable_to'  => $applicable_to,
            'user_ids'       => $coupon->user_ids ,
            'zipcodes'       => $coupon->zipcodes,
        ]);
    }
    public function destroy($id) {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            return response()->json(['success' => true, 'message' => 'Coupon deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
