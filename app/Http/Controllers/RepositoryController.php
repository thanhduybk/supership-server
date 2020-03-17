<?php

namespace App\Http\Controllers;

use App\District;
use App\Http\Responses\RepositoryResponse;
use App\Province;
use App\Repository;
use App\Ward;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class RepositoryController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        Log::alert(Auth::user());

        $repositories = Repository::where('owner_id', Auth::id())->get();

        $response = array();

        foreach ($repositories as $repository) {
            array_push($response, [
                'id' => $repository->id,
                'name' => $repository->name,
                'phone' => $repository->phone,
                'address' => $this->fullAddress($repository),
                'owner' => Auth::user()->name
            ]);
        }

        return $this->sendResponse('Get repositories successfully', $response, 200);
    }

    private function fullAddress($repository) {
        $ward = $repository->ward;
        $district = $ward->district;
        $province = $district->province;

        return sprintf("%s, %s, %s, %s", $repository->address, $ward->name, $district->name, $province->name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        Log::alert($request);

        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string',
            'phone' => 'bail|required|numeric',
            'contact' => 'bail|required|string',
            'address' => 'bail|required|string',
            'ward' => 'bail|required|string',
            'district' => 'bail|required|string',
            'province' => 'bail|required|string',
            'main_repo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        } else {
            $province = Province::where('name', $request->province)->firstOrFail();

            $district = District::where('province_id', $province->id)
                ->where('name', $request->district)->firstOrFail();

            $ward = Ward::where('district_id', $district->id)
                ->where('name', $request->ward)->firstOrFail();

            $repository = Repository::create([
                'name' => $request->get('name'),
                'phone' => $request->get('phone'),
                'contact' => $request->get('contact'),
                'address' => $request->get('address'),
                'ward_id' => $ward->id,
                'owner_id' => Auth::id(),
                'main_repo' => $request->has('main_repo') ? $request->get('main_repo') : false
            ]);

            return $this->sendResponse('Create repository successfully', $repository, 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Repository $repository
     * @return JsonResponse
     */
    public function show(Repository $repository)
    {
        Log::alert($repository);
        return $this->sendResponse('Get repository successfully', $repository, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Repository $repository
     * @return JsonResponse
     */
    public function update(Request $request, Repository $repository)
    {
        Log::alert($request);
        Log::alert($repository);

        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string',
            'phone' => 'bail|required|numeric',
            'contact' => 'bail|required|string',
            'address' => 'bail|required|string',
            'ward' => 'bail|required|string',
            'district' => 'bail|required|string',
            'province' => 'bail|required|string',
            'main_repo' => 'boolean',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        } else {
            $repository->update($request->all());
            return $this->sendResponse(
                "Update repository success",
                Repository::find($request->get('id')),
                200);
        }
    }


    /**
     * @param Repository $repository
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Repository $repository)
    {
        Log::alert($repository);

        if (!empty($repository)) {
            $repository->delete();
            return $this->sendResponse('Delete repository successfully', null, 200);
        } else {
            return $this->sendError('Repository not found', null, 404);
        }
    }
}
