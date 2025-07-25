<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    //
    protected $data;

    public function showLinkRequestForm()
    {
        return view('admin.forgot-password');
    }
}
