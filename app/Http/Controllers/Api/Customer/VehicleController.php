<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Customer\ValidateVehicleRequest;
use App\Http\Requests\Api\Customer\VehicleRequest;
use App\Http\Requests\Api\Customer\VehicleSearchRequest;
use App\Http\Resources\Api\Customer\VehicleListResource;
use App\Http\Resources\Api\Customer\VehicleResource;
use Illuminate\Http\Request;
use App\Services\Api\VehicleService;
use Exception;

class VehicleController extends Controller
{
    //
    protected $vehicleService;

    public function __construct(VehicleService $vehicleService)
    {
        $this->vehicleService = $vehicleService;
    }

    public function vehicleSearch(VehicleSearchRequest $request){
        try{
            $vehicleData = $this->vehicleService->vehicleSearch($request);
            if($vehicleData){

                return success(
                    $vehicleData,
                    trans('messages.view', ['attribute' => 'Vehicle']),
                    config('code.SUCCESS_CODE')
                );
            } else{
                return fail([], 'Something went wrong while fetching the details.', config('code.EXCEPTION_ERROR_CODE'));
            }
        } catch(Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }
    public function addVehicle(VehicleRequest $request){
        try{
            $storeVehicle = $this->vehicleService->addVehicle($request);
            if($storeVehicle){
                return success(
                    (new VehicleResource($storeVehicle)),
                    trans('messages.create', ['attribute' => 'Vehicle']),
                    config('code.SUCCESS_CODE')
                );
            }
        } catch(Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }
    public function listVehicles(Request $request)
    {
        try {
            $listVehicles = $this->vehicleService->getVehicleList($request);
            if($listVehicles){
                return success(
                    pagination(VehicleListResource::class, $listVehicles),
                    trans('messages.list', ['attribute' => 'Vehicle']),
                    config('code.SUCCESS_CODE')
                );
            }
        } catch(Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }

    public function deleteVehicle(ValidateVehicleRequest $request)
    {
        try {
            $vehicle = $this->vehicleService->deleteVehicle($request);
            if($vehicle){
                return success(
                    $request->all(),
                    trans('messages.deleted', ['attribute' => 'Vehicle']),
                    config('code.SUCCESS_CODE')
                );
            }else{
                return fail([], trans('messages.not_found', ['attribute' => 'Vehicle']), config('code.NO_RECORD_CODE'));
            }

        }
        catch (Exception $e) {
            return fail([], $e->getMessage(), config('code.EXCEPTION_ERROR_CODE'));
        }
    }
}
