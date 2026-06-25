<?php

namespace App\Services;

use App\Models\Setting;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration from database
        Config::$serverKey = Setting::get('midtrans_server_key');
        Config::$isProduction = (bool) Setting::get('midtrans_is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Get Snap Payment Token
     */
    public function getSnapToken($order, $customerDetails, $items)
    {
        if (!Config::$serverKey) {
            throw new \Exception("Midtrans Server Key is not configured in Settings.");
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_number,
                'gross_amount' => (int) $order->total,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $items,
        ];

        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            \Log::error('Midtrans Error: ' . $e->getMessage());
            throw $e;
        }
    }
}
