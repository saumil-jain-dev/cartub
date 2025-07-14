<?php

namespace App\Http\Controllers\Admin\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public $data;
    /**
     * Show the admin dashboard.
     */
    
    public function index(){
        $pageTitle = 'Dashboard';
        return view('admin.dashboard'); // Ensure this view exists
    }
}
