<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            ['group' => 'google', 'key' => 'google_client_id', 'value' => '', 'type' => 'text', 'label' => 'Google Client ID', 'description' => 'OAuth Client ID from Google Cloud Console.'],
            ['group' => 'google', 'key' => 'google_client_secret', 'value' => '', 'type' => 'encrypted', 'label' => 'Google Client Secret', 'description' => 'OAuth Client Secret from Google Cloud Console.'],
            ['group' => 'recaptcha', 'key' => 'recaptcha_site_key', 'value' => '', 'type' => 'text', 'label' => 'reCAPTCHA Site Key', 'description' => 'Site Key from Google reCAPTCHA Admin Console.'],
            ['group' => 'recaptcha', 'key' => 'recaptcha_secret_key', 'value' => '', 'type' => 'encrypted', 'label' => 'reCAPTCHA Secret Key', 'description' => 'Secret Key from Google reCAPTCHA Admin Console.'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \App\Models\Setting::whereIn('key', [
            'google_client_id', 'google_client_secret',
            'recaptcha_site_key', 'recaptcha_secret_key'
        ])->delete();
    }
};
