<?php

namespace App\Http\Controllers\Admin\Cleaner;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $users = $query->get()->map(function ($user) {
            $user->profile_image_url = getImageAdmin($user->profile_picture);
            return $user;
        });
        $this->data['pageTitle'] = 'Cleaners List';
        $this->data['cleaners'] = $users;
        return view("admin.cleaners.index", $this->data);
    }
}
