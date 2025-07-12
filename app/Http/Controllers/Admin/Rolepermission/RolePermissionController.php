<?php

namespace App\Http\Controllers\Admin\Rolepermission;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;

class RolePermissionController extends Controller
{
    protected $data;
    //
    public function index(){

        $this->data['pageTitle'] = 'Role Permission';
        $roleData = ModelsRole::with('permissions')->whereNotIn('name', ['super_admin'])->get();
        $this->data['roleData'] = $roleData;
        return view('admin.rolepermission.index',$this->data);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'role_name' => [
                'required',
                Rule::unique('roles', 'name')->ignore($request->id),
            ],
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }
        DB::beginTransaction();

        try {
            if ($request->id) {
                // 📝 Update
                $role = ModelsRole::findOrFail($request->id);
                $role->name = $request->role_name;
                $role->status = $request->status;
                $role->save();

                $message = 'Role updated successfully!';
            } else {
                // 📝 Create
                $role = ModelsRole::create([
                    'name' => $request->role_name,
                    'guard_name' => 'web',
                    'status' => $request->status,
                ]);

                $message = 'Role created successfully!';
            }

            // 📝 Sync permissions
            $role->syncPermissions($request->permissions);

            DB::commit();

            Session::flash('success', $message);
            return redirect()->route('roles-permission.index')
                            ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
        
    }
}
