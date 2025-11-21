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

    public function destroy($id){
        try {
            $user = Rating::findOrFail($id);
            $user->delete();

            return response()->json(['success' => true, 'message' => 'Customer ratting or feedback deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
