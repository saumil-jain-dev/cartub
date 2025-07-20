<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PaymentController extends Controller
{
    //
    protected $data;

    public function index(Request $request){

        if(! hasPermission('payment.index')){
            abort(403);
        }
        $this->data['pageTitle'] = 'Payments history';

        $payments = Payment::with(['bookings','bookings.customer']);

        // Filters
        if ($request->filled('from_date')) {
            $fromDate = Carbon::parse($request->from_date)->format('Y-m-d');
            $payments->whereDate('payments.created_at', '>=', $fromDate);
        }

        if ($request->filled('to_date')) {
            $toDate = Carbon::parse($request->to_date)->format('Y-m-d');
            $payments->whereDate('payments.created_at', '<=', $toDate);
        }

        if ($request->filled('status')) {
            $payments->where('status',$request->status);
        }

        if ($request->filled('payment_method')) {
            
            $payments->where('payment_method', $request->payment_method);
        }
        
        $this->data['payments'] = $payments->orderBy('payments.id','desc')->get();
        return view('admin.payment.index',$this->data);
    }
}
