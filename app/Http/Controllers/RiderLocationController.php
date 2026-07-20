<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\LocationTrackingService;
use App\Http\Requests\UpdateLocationRequest;

class RiderLocationController extends Controller
{
    protected $locationService;

    public function __construct(LocationTrackingService $locationService)
    {
        $this->locationService = $locationService;
    }

    // Rider ka page — yahan se wo "Start Sharing Location" dabayega
    public function show($order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        return view('rider.track', compact('order'));
    }

    // Rider ka browser har kuch second baad yahan location bhejta rahega (AJAX)
    public function update(UpdateLocationRequest $request, $order_number)
    {
        $order = Order::where('order_number', $order_number)->firstOrFail();

        $this->locationService->updateLocation(
            $order,
            $request->validated()['lat'],
            $request->validated()['lng']
        );

        return response()->json(['success' => true]);
    }
}
