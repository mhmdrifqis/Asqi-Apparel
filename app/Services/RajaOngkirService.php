<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    protected $apiKey;
    protected $type;
    protected $baseUrl;
    protected $originCityId;

    public function __construct()
    {
        $this->apiKey = Setting::get('rajaongkir_api_key');
        $this->type = Setting::get('rajaongkir_type', 'starter'); // starter, basic, pro
        $this->originCityId = Setting::get('store_city_id', '152'); // Default to Jakarta Pusat
        
        // Use Komerce API for RajaOngkir V2
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1';
    }

    /**
     * Get list of provinces.
     */
    public function getProvinces()
    {
        $fallback = [['province_id' => '1', 'province' => 'DKI Jakarta (Demo)']];
        if (!$this->apiKey) return $fallback;

        try {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->timeout(15)
                ->get("{$this->baseUrl}/destination/province");

            if ($response->successful() && $response->json('meta.status') === 'success') {
                $data = $response->json('data') ?? [];
                
                // Normalize Komerce format to RajaOngkir format
                $formatted = array_map(function($item) {
                    return [
                        'province_id' => $item['id'],
                        'province' => $item['name']
                    ];
                }, $data);
                
                return $formatted;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Komerce GetProvinces Error: ' . $e->getMessage());
        }
        return $fallback;
    }

    /**
     * Get list of cities for a province.
     */
    public function getCities($provinceId)
    {
        $fallback = [['city_id' => '1', 'province_id' => '1', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat (Demo)']];
        if (!$this->apiKey || !$provinceId) return $fallback;

        try {
            // Komerce uses path parameter for province_id
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->timeout(15)
                ->get("{$this->baseUrl}/destination/city/{$provinceId}");

            if ($response->successful() && $response->json('meta.status') === 'success') {
                $data = $response->json('data') ?? [];
                
                // Normalize Komerce format to RajaOngkir format
                $formatted = array_map(function($item) use ($provinceId) {
                    return [
                        'city_id' => $item['id'],
                        'province_id' => $provinceId,
                        'type' => '',
                        'city_name' => $item['name']
                    ];
                }, $data);
                
                return $formatted;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Komerce GetCities Error: ' . $e->getMessage());
        }
        return $fallback;
    }

    /**
     * Calculate shipping cost
     */
    public function getCost($destinationCityId, $weight, $courier = 'jne')
    {
        $fallback = [['service' => 'Standard (Demo)', 'cost' => [['value' => 25000, 'etd' => '2-3', 'note' => '']]]];
        
        if (!$this->apiKey) {
            return $fallback;
        }

        try {
            // Komerce requires application/x-www-form-urlencoded
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->asForm()
                ->timeout(15)
                ->post("{$this->baseUrl}/calculate/domestic-cost", [
                    'origin' => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weight,
                    'courier' => $courier
                ]);

            if ($response->successful() && $response->json('meta.status') === 'success') {
                $data = $response->json('data') ?? [];
                
                // Normalize Komerce format to RajaOngkir format
                $formatted = array_map(function($item) {
                    return [
                        'service' => $item['service'] ?? $item['name'],
                        'cost' => [
                            [
                                'value' => $item['cost'],
                                'etd' => str_replace(' day', '', $item['etd']), // Optional: '8 day' -> '8'
                                'note' => $item['description'] ?? ''
                            ]
                        ]
                    ];
                }, $data);
                
                return $formatted ?: $fallback;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Komerce GetCost Error: ' . $e->getMessage());
        }

        return $fallback;
    }
}
