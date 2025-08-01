<?php

namespace App\Http\Controllers\Admin\Vehicle;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class VehicleController extends Controller
{
    //
    protected $data;

    public function index(Request $request){
        if(! hasPermission('vehicle.index')){
            abort(403);
        }
        $query = Vehicle::with(['customer'])->whereHas('customer', function($query) {
            $query->where('role', 'customer');
        });

        $vehicles = $query->get();
        $this->data['pageTitle'] = 'Customer Vehicles list';
        $this->data['vehicles'] = $vehicles;
        return view("admin.vehicle.index", $this->data);
    }

    public function washType(Request $request) {
        if(! hasPermission('vehicle.wash-type')){
            abort(403);
        }
        $wash_types = Service::where('type','service')->get(); // Assuming you have a model for wash types
        $this->data['pageTitle'] = 'Wash Type';
        $this->data['wash_types'] = $wash_types;
        return view("admin.vehicle.wash-type", $this->data);
    }

    public function washTypeStore(Request $request  ) {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                Rule::unique('services', 'name'),
                'string',
                'max:255',
            ],
            'description' => 'nullable|string',
            'duration' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|in:1,0',
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }

        DB::beginTransaction();
        try{
            $washType = Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'duration_minutes' => $request->duration,
                'price' => $request->price,
                'type' => 'service',
                'is_active' => $request->status ?? 1, // Default to active if not provided
            ]);

            DB::commit();
            Session::flash('success', 'Wash Type created successfully!');
            return redirect()->route('vehicle.wash-type');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function washTypeUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:services,id',
            'name' => [
                'required',
                Rule::unique('services', 'name')->ignore($request->id),
                'string',
                'max:255',
            ],
            'description' => 'nullable|string',
            'duration' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|in:1,0',
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            $washType = Service::findOrFail($request->id);
            $washType->update([
                'name' => $request->name,
                'description' => $request->description,
                'duration_minutes' => $request->duration,
                'price' => $request->price,
                'is_active' => $request->status ?? 1, // Default to active if not provided
            ]);

            DB::commit();
            Session::flash('success', 'Wash Type updated successfully!');
            return redirect()->route('vehicle.wash-type');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function washTypeDestroy($id) {
        if(! hasPermission('vehicle.wash-types-destroy')){
            abort(403);
        }
        try {
            $washType = Service::findOrFail($id);
            $washType->delete();
            Session::flash('success','Wash Type deleted successfully');
            return response()->json(['success' => true, 'message' => 'Wash Type deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function washPackage(Request $request) {
        if(! hasPermission('vehicle.wash-packages')){
            abort(403);
        }
        $wash_types = Service::where('type','package')->get(); // Assuming you have a model for wash types
        $this->data['pageTitle'] = 'Wash Packages';
        $this->data['wash_types'] = $wash_types;
        return view("admin.vehicle.wash-packages", $this->data);
    }

    public function washPackageStore(Request $request  ) {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|in:1,0',
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }

        DB::beginTransaction();
        try{
            $washType = Service::create([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'type' => 'package',
                'is_active' => $request->status ?? 1, // Default to active if not provided
            ]);

            DB::commit();
            Session::flash('success', 'Wash Package created successfully!');
            return redirect()->route('vehicle.wash-packages');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function washPackageUpdate(Request $request) {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:services,id',
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'nullable|in:1,0',
        ]);
        
        if ($validator->fails()) { 
            return back()->withErrors($validator->errors())->withInput();
        }

        DB::beginTransaction();
        try {
            $washType = Service::findOrFail($request->id);
            $washType->update([
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'is_active' => $request->status ?? 1, // Default to active if not provided
            ]);

            DB::commit();
            Session::flash('success', 'Wash Package updated successfully!');
            return redirect()->route('vehicle.wash-packages');
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function washPackageDestroy($id) {
        if(! hasPermission('vehicle.wash-packages-destroy')){
            abort(403);
        }
        try {
            $washType = Service::findOrFail($id);
            $washType->delete();
            Session::flash('success','Wash Package deleted successfully');
            return response()->json(['success' => true, 'message' => 'Wash Package deleted successfully.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request) {
        $vehicleData = Vehicle::where('license_plate', $request->number)
            ->where('customer_id', $request->customer_id)
            ->first();
        if ($vehicleData) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle already exists for this customer.',
            ], 200);
        }
        DB::beginTransaction();
        try {
            $vehicle = Vehicle::create([
                'customer_id' => $request->customer_id,
                'license_plate' => $request->number,
                'make' => $request->make,
                'model' => $request->model,
                'year' => $request->year,
                'color' => $request->color,
            ]);

            DB::commit();
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $vehicle->id,
                    'number' => $vehicle->license_plate,
                    'model' => $vehicle->model,
                    'make' => $vehicle->make,
                    'color' => $vehicle->color,
                    'year' => $vehicle->year,
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
            ], 500);
        }
    }
}
