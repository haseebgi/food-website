<?php

namespace App\Services;

use App\Models\Order;

// ✅ Sirf rider ka location save/fetch karne ka kaam yahan hoga.
// Kal agar location history bhi save karni ho (jaise route replay
// dikhana), sirf isi class ko edit karo. (OCP)
class LocationTrackingService
{
    public function updateLocation(Order $order, float $lat, float $lng): void
    {
        $order->update([
            'rider_lat'            => $lat,
            'rider_lng'            => $lng,
            'location_updated_at'  => now(),
        ]);
    }

    public function getLocation(Order $order): ?array
    {
        if (is_null($order->rider_lat) || is_null($order->rider_lng)) {
            return null;
        }

        return [
            'lat'        => (float) $order->rider_lat,
            'lng'        => (float) $order->rider_lng,
            'updated_at' => $order->location_updated_at?->diffForHumans(),
        ];
    }

    // Agar last update 5 minute se purana ho to maan lo rider ne
    // location sharing band kar di hai (offline)
    public function isLocationStale(Order $order, int $minutes = 5): bool
    {
        if (!$order->location_updated_at) {
            return true;
        }

        return $order->location_updated_at->lt(now()->subMinutes($minutes));
    }

    // ✅ Naya method - rider ka tracking link banata hai (QR code ke liye use hoga)
    public function getRiderTrackingUrl(Order $order): string
    {
        return route('rider.track', $order->order_number);
    }
}
