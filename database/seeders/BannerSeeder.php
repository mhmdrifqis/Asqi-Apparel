<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        Banner::create([
            'title' => 'ENGINEERED FOR GREATNESS',
            'subtitle' => 'The new Elite Performance Collection is here.',
            'cta_text' => 'SHOP NOW',
            'image_path' => 'https://via.placeholder.com/1920x800.png/1a1a2e/e94560?text=NEW+COLLECTION',
            'link_url' => '/shop/new-arrivals',
            'sort_order' => 1,
            'is_active' => true,
            'starts_at' => Carbon::now()->subDays(1),
            'expires_at' => Carbon::now()->addMonths(1),
        ]);

        Banner::create([
            'title' => 'MID-SEASON SALE',
            'subtitle' => 'Up to 40% off on selected sportswear.',
            'cta_text' => 'EXPLORE SALE',
            'image_path' => 'https://via.placeholder.com/1920x800.png/e94560/ffffff?text=SUMMER+SALE',
            'link_url' => '/shop/sale',
            'sort_order' => 2,
            'is_active' => true,
            'starts_at' => Carbon::now()->subDays(1),
            'expires_at' => Carbon::now()->addWeeks(2),
        ]);
    }
}
