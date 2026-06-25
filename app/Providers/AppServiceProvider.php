<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('settings')) {
                config([
                    'services.google.client_id' => \App\Models\Setting::get('google_client_id', config('services.google.client_id')),
                    'services.google.client_secret' => \App\Models\Setting::get('google_client_secret', config('services.google.client_secret')),
                    'services.recaptcha.site_key' => \App\Models\Setting::get('recaptcha_site_key', config('services.recaptcha.site_key')),
                    'services.recaptcha.secret_key' => \App\Models\Setting::get('recaptcha_secret_key', config('services.recaptcha.secret_key')),
                ]);
            }
        } catch (\Exception $e) {
            // Ignore during setup/migrations
        }

        // View composer for navigation
        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('categories')) {
                View::composer(['layouts.partials.header', 'layouts.partials.mobile-menu', 'layouts.partials.footer'], function ($view) {
                    $navCategories = \App\Models\Category::whereNull('parent_id')
                        ->where('is_active', true)
                        ->whereHas('products', function($q) {
                            $q->where('is_active', true);
                        })
                        ->orderBy('sort_order')
                        ->get();
                    
                    $view->with('navCategories', $navCategories);
                });
            }
        } catch (\Exception $e) {
            // Ignore during setup/migrations
        }
    }
}
