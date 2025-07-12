<?php

namespace App\Http\Controllers\Admin\Rolepermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RolePermissionController extends Controller
{
    //
    public function index(){
        return view('admin.rolepermission.index');
    }
}
