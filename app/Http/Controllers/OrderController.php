<?php

namespace App\Http\Controllers;

use App\Order;
use App\Ward;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $orders = Order::where('sender_id', Auth::id())->orderBy('created_at', 'desc')->get();

        $response = array();

        foreach ($orders as $order) {
            array_push($response, $this->makeReadable($order));
        }

        return $this->sendResponse('Get all orders succesfully', $response, 200);
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
            'product' => 'bail|required|string',
            'receiver' => 'bail|required|string',
            'address' => 'bail|required|string',
            'repository_id' => 'bail|required|numeric',
            'ward_id' => 'bail|required|numeric',
            'money_taking' => 'numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        } else {
            // Check if the specified ward is existing
            Ward::find($request->get('ward_id'));

            $newOrder = Order::create([
                'product' => $request->get('product'),
                'receiver' => $request->get('receiver'),
                'address' => $request->get('address'),
                'repository_id' => $request->get('repository_id'),
                'ward_id' => $request->get('ward_id'),
                'sender_id' => Auth::id(),
                'money_taking' => $request->has('money_taking') ? (int)$request->get('money_taking') : 0
            ]);

            return $this->sendResponse('Create order successfully', $this->makeReadable($newOrder), 201);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Order $order
     * @return JsonResponse
     */
    public function show(Order $order)
    {
        return $this->sendResponse('Get order successfully', $this->makeReadable($order), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function update(Request $request, Order $order)
    {
        Log::alert($request);
        Log::alert($order);

        $validator = Validator::make($request->all(), [
            'product' => 'bail|required|string',
            'receiver' => 'bail|required|string',
            'address' => 'bail|required|string',
            'repository_id' => 'bail|required|numeric',
            'ward_id' => 'bail|required|numeric',
            'money_taking' => 'numeric'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        } else {
            $order->update($request->all());
            return $this->sendResponse(
                'Update order successfully',
                $this->makeReadable(Order::where('id', $order->id)->first()),
                200
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Order $order
     * @return JsonResponse
     * @throws Exception
     */
    public function destroy(Order $order)
    {
        Log::alert($order);

        if (!empty($order)) {
            $id = $order->id;
            $order->delete();
            return $this->sendResponse('Delete order successfully', $id, 200);
        } else {
            return $this->sendError('Order not found', null, 404);
        }
    }

    private function fullAddress($order) {
        $ward = $order->ward;
        $district = $ward->district;
        $province = $district->province;

        return sprintf("%s, %s, %s, %s", $order->address, $ward->name, $district->name, $province->name);
    }

    private function makeReadable($order) {
        return [
            'id' => $order->id,
            'product' => $order->product,
            'receiver' => $order->receiver,
            'address' => $this->fullAddress($order),
            'repository' => $order->repository->name,
        ];
    }
}
