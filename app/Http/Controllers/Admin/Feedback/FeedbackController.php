<?php

namespace App\Http\Controllers\Admin\Feedback;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    //
    protected $data;

    public function index(Request $request){

        if(! hasPermission('payment.index')){
            abort(403);
        }
        $this->data['pageTitle'] = 'Customer feedback';

        $ratings = Rating::with(['booking','customer','cleaner'])->get();

        $this->data['ratings'] = $ratings;
        return view('admin.customer-feedback.index',$this->data);
    }
}
