<?php

namespace App\Services\Api;

use App\Models\User;
use App\Models\Vehicle;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

class VehicleService {


    public function addVehicle($request) {
        try{
            DB::beginTransaction();
            $vehicle = new Vehicle();
            $vehicle->customer_id = Auth::id();
            $vehicle->make = "Toyota";
            $vehicle->model = "Corolla";
            $vehicle->year = "2015";
            $vehicle->color = "White";
            $vehicle->license_plate = $request->license_plate;
            $vehicle->save();

            DB::commit();
            return $vehicle;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }

    public function getVehicleList($request)
    {
        try {
            $search = $request->input('search', '');
            $perPage = $request->input('per_page', 10);
            $vehicles = Vehicle::where('customer_id', Auth::id())
                ->when($search, function ($query) use ($search) {
                    return $query->where(function ($q) use ($search) {
                        $q->where('make', 'like', "%{$search}%")
                            ->orWhere('model', 'like', "%{$search}%")
                            ->orWhere('year', 'like', "%{$search}%")
                            ->orWhere('color', 'like', "%{$search}%")
                            ->orWhere('license_plate', 'like', "%{$search}%");
                    });
                })
                ->orderBy('id', 'desc') // Order by latest
                ->paginate($perPage)->withQueryString();

            return $vehicles;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function deleteVehicle($request)
    {
        try {
            DB::beginTransaction();
            $vehicle = Vehicle::where('customer_id', Auth::id())
                ->where('id', $request->vehicle_id)
                ->first();

            $vehicle->delete();
            DB::commit();
            return $vehicle;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception($e->getMessage());
        }
    }
}
