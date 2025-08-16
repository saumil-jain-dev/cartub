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
        try {
            DB::beginTransaction();
            $customerId = Auth::id();
            $licensePlate = $request->license_plate;
            // Check for duplicate vehicle for this customer
            $existingVehicle = Vehicle::where('customer_id', $customerId)
                ->where('license_plate', $licensePlate)
                ->first();
            if ($existingVehicle) {
                DB::rollBack();
                throw new Exception('Vehicle with this number already exists for this customer.');
            }
            $vehicle = new Vehicle();
            $vehicle->customer_id = $customerId;
            $vehicle->make = $request->make;
            $vehicle->model = $request->model;
            $vehicle->year = $request->year;
            $vehicle->color = $request->color;
            $vehicle->license_plate = $licensePlate;
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

    public function vehicleSearch($request){

        $vehicleNumber = $request->vehicle_number;
        $apikey = env('APP_ENV') == "local" ? config('constants.CAR_CHECK_TEST_API_KEY') : config('constants.CAR_CHECK_LIVE_API_KEY');
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.checkcardetails.co.uk/vehicledata/vehicleregistration?apikey='.$apikey.'&vrm='.$vehicleNumber,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $apiResponse = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($apiResponse, true);
        $result = [];
        if (isset($response['registrationNumber'])) {
            // Success case
            $data = $response;

            $result = [
                'Colour' => $data['colour'] ?? null,
                'Vrm' => $data['registrationNumber'] ?? null,
                'Make' => $data['make'] ?? null,
                'Model' => $data['model'] ?? null,
                'YearOfManufacture' => $data['yearOfManufacture'] ?? null,
                'VehicleClass' => $data['VehicleClass'] ?? null,
            ];

        }
        return $result;
    }
}
