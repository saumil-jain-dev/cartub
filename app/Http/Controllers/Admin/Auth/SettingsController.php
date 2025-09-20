<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SettingsController extends Controller
{
    //
    protected $data;

    public function settings(){
        $this->data['pageTitle'] = "App Settings";
        $this->data['settingsData'] = Setting::all();
        return view("admin.settings", $this->data);
    }

    public function store(Request $request){
        $data = $request->except('_token');
        
        foreach($data as $key =>  $value){
            Setting::where('key',$key)->update(['value'=>$value]);
        }
        Session::flash("success","Setting Updated Successfully");
        return redirect()->route('settings');
    }

     public function getSettings(){
        $settings = Setting::where('key','is_testing')->first();
        $data = [
            'is_testing' => $settings ? (bool)$settings->value : false,
        ];
        return response()->json(['status'=>true,'data'=>$data]);
    }
}
