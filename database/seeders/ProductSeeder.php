<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // FOOTBALL JERSEYS
            ['name' => 'Real Madrid Home Jersey 24/25', 'category' => 'football', 'gender' => 'men', 'sport_type' => 'Football', 'base_price' => 1200000, 'sale_price' => null, 'colors' => ['White' => '#FFFFFF'], 'is_featured' => true],
            ['name' => 'Manchester United Away Jersey', 'category' => 'football', 'gender' => 'men', 'sport_type' => 'Football', 'base_price' => 1200000, 'sale_price' => null, 'colors' => ['Blue' => '#0000FF'], 'is_featured' => false],

            // FUTSAL JERSEYS
            ['name' => 'Squadra 21 Futsal Jersey', 'category' => 'futsal', 'gender' => 'men', 'sport_type' => 'Futsal', 'base_price' => 350000, 'sale_price' => null, 'colors' => ['Red' => '#FF0000', 'Navy' => '#000080'], 'is_featured' => false],
            ['name' => 'Tiro 23 Competition Futsal Jersey', 'category' => 'futsal', 'gender' => 'unisex', 'sport_type' => 'Futsal', 'base_price' => 450000, 'sale_price' => 350000, 'colors' => ['Yellow' => '#FFFF00', 'Black' => '#000000'], 'is_featured' => true],

            // BASKETBALL JERSEYS
            ['name' => 'Bulls Statement Edition Jersey', 'category' => 'basketball', 'gender' => 'men', 'sport_type' => 'Basketball', 'base_price' => 1500000, 'sale_price' => null, 'colors' => ['Black' => '#000000'], 'is_featured' => true],
            ['name' => 'Lakers Icon Edition Jersey', 'category' => 'basketball', 'gender' => 'men', 'sport_type' => 'Basketball', 'base_price' => 1500000, 'sale_price' => null, 'colors' => ['Yellow' => '#FFFF00'], 'is_featured' => true],

            // RUNNING JERSEYS (SINGLETS/TOPS)
            ['name' => 'Own The Run Singlet', 'category' => 'running', 'gender' => 'men', 'sport_type' => 'Running', 'base_price' => 350000, 'sale_price' => null, 'colors' => ['Black' => '#000000', 'Yellow' => '#FFFF00'], 'is_featured' => false],
            ['name' => 'Adizero Race Singlet', 'category' => 'running', 'gender' => 'unisex', 'sport_type' => 'Running', 'base_price' => 850000, 'sale_price' => 700000, 'colors' => ['Solar Red' => '#FF0000', 'White' => '#FFFFFF'], 'is_featured' => true],
        ];

        $clothingSizes = ['S', 'M', 'L', 'XL', 'XXL'];

        foreach ($products as $p) {
            $category = Category::where('slug', $p['category'])->first();
            
            if (!$category) continue;

            $product = Product::create([
                'category_id' => $category->id,
                'name' => $p['name'],
                'slug' => Str::slug($p['name']),
                'description' => 'Premium sports jersey designed for optimal performance and maximum breathability. Engineered with moisture-wicking technology.',
                'short_description' => 'High-performance authentic jersey.',
                'material' => '100% Recycled Polyester Mesh',
                'base_price' => $p['base_price'],
                'sale_price' => $p['sale_price'],
                'weight_grams' => 250, // Jerseys are light
                'gender' => $p['gender'],
                'sport_type' => $p['sport_type'],
                'is_active' => true,
                'is_featured' => $p['is_featured'],
                'total_sold' => rand(10, 500),
            ]);

            // Add placeholder image
            $product->images()->create([
                'image_path' => 'https://placehold.co/600x800/eeeeee/000000?text=' . urlencode(str_replace(' ', '+', $p['name'])),
                'alt_text' => $p['name'],
                'is_primary' => true,
                'sort_order' => 1
            ]);

            // Create Variants
            foreach ($p['colors'] as $colorName => $colorHex) {
                foreach ($clothingSizes as $size) {
                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $size,
                        'color' => $colorName,
                        'color_hex' => $colorHex,
                        'sku' => strtoupper(Str::random(3)) . '-' . strtoupper(substr($colorName, 0, 3)) . '-' . $size,
                        'price_adjustment' => 0,
                        'stock' => rand(5, 50),
                        'is_active' => true,
                    ]);
                }
            }
        }
    }
}
