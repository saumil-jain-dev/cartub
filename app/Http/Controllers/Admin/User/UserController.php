<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    protected $data;

    public function index(Request $request){
        if(! hasPermission('users.index')){
            abort(403);
        }
        $this->data['pageTitle'] = 'User List';
        return view("admin.users.index", $this->data);
    }
}
