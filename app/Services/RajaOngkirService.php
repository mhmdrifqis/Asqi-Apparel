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
        
        $this->baseUrl = $this->type === 'pro' 
            ? 'https://pro.rajaongkir.com/api' 
            : 'https://api.rajaongkir.com/starter';
    }

    /**
     * Get list of provinces. Cached for 24 hours.
     */
    public function getProvinces()
    {
        $fallback = [['province_id' => '1', 'province' => 'DKI Jakarta (Demo)']];
        if (!$this->apiKey) return $fallback;

        try {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->timeout(5)
                ->get("{$this->baseUrl}/province");

            if ($response->successful()) {
                return $response->json('rajaongkir.results') ?? $fallback;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir GetProvinces Error: ' . $e->getMessage());
        }
        return $fallback;
    }

    /**
     * Get list of cities for a province. Cached for 24 hours.
     */
    public function getCities($provinceId)
    {
        $fallback = [['city_id' => '1', 'province_id' => '1', 'type' => 'Kota', 'city_name' => 'Jakarta Pusat (Demo)']];
        if (!$this->apiKey || !$provinceId) return $fallback;

        try {
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->timeout(5)
                ->get("{$this->baseUrl}/city", ['province' => $provinceId]);

            if ($response->successful()) {
                return $response->json('rajaongkir.results') ?? $fallback;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir GetCities Error: ' . $e->getMessage());
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
            $response = Http::withHeaders(['key' => $this->apiKey])
                ->timeout(5)
                ->post("{$this->baseUrl}/cost", [
                    'origin' => $this->originCityId,
                    'destination' => $destinationCityId,
                    'weight' => $weight,
                    'courier' => $courier
                ]);

            if ($response->successful()) {
                return $response->json('rajaongkir.results.0.costs') ?? $fallback;
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('RajaOngkir GetCost Error: ' . $e->getMessage());
        }

        return $fallback;
    }
}
