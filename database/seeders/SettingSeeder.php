<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Store Settings
            ['group' => 'store', 'key' => 'store_name', 'value' => 'Asqi Apparel', 'type' => 'text', 'label' => 'Store Name'],
            ['group' => 'store', 'key' => 'store_email', 'value' => 'support@asqiapparel.com', 'type' => 'text', 'label' => 'Store Email'],
            ['group' => 'store', 'key' => 'store_phone', 'value' => '081234567890', 'type' => 'text', 'label' => 'Store Phone'],
            ['group' => 'store', 'key' => 'store_address', 'value' => 'Jl. Jendral Sudirman No. 1, Jakarta Pusat', 'type' => 'text', 'label' => 'Store Address'],
            ['group' => 'store', 'key' => 'store_city_id', 'value' => '152', 'type' => 'text', 'label' => 'Store Origin City ID (for RajaOngkir)'],

            // RajaOngkir Settings
            ['group' => 'rajaongkir', 'key' => 'rajaongkir_api_key', 'value' => 'your_rajaongkir_api_key_here', 'type' => 'encrypted', 'label' => 'RajaOngkir API Key'],
            ['group' => 'rajaongkir', 'key' => 'rajaongkir_type', 'value' => 'starter', 'type' => 'text', 'label' => 'Account Type (starter/basic/pro)'],
            
            // Midtrans Settings
            ['group' => 'midtrans', 'key' => 'midtrans_server_key', 'value' => 'SB-Mid-server-YOUR_SERVER_KEY', 'type' => 'encrypted', 'label' => 'Server Key'],
            ['group' => 'midtrans', 'key' => 'midtrans_client_key', 'value' => 'SB-Mid-client-YOUR_CLIENT_KEY', 'type' => 'text', 'label' => 'Client Key'],
            ['group' => 'midtrans', 'key' => 'midtrans_merchant_id', 'value' => 'G123456789', 'type' => 'text', 'label' => 'Merchant ID'],
            ['group' => 'midtrans', 'key' => 'midtrans_is_production', 'value' => '0', 'type' => 'boolean', 'label' => 'Is Production Environment?'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['group' => $setting['group'], 'key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'type' => $setting['type'],
                    'label' => $setting['label'],
                ]
            );
        }
    }
}
