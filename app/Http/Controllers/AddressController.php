<?php

namespace App\Http\Controllers;

use App\District;
use App\Province;
use App\Ward;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AddressController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function allProvinces()
    {
        return $this->sendResponse("Get all provinces successfully", Province::all(), 200);
    }

    public function allDistricts(Request $request)
    {
        $province = Province::where('name', $request->province)->first();

        return $this->sendResponse(
            "Get all districts of the province successfully",
            District::where('province_id', $province->id)->get(),
            200
        );
    }

    public function allWards(Request $request)
    {
        Log::alert($request);
        $province = Province::where('name', $request->province)->first();
        $district = District::where('province_id', $province->id)
            ->where('name', $request->district)->first();

        return $this->sendResponse(
            "Get all wards of the district successfully",
            Ward::where('district_id', $district->id)->get(),
            200
        );
    }
}
