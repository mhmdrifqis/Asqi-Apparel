<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Football',
                'description' => 'Football jerseys and apparel',
                'sort_order' => 1,
            ],
            [
                'name' => 'Futsal',
                'description' => 'Futsal jerseys and indoor clothing',
                'sort_order' => 2,
            ],
            [
                'name' => 'Basketball',
                'description' => 'Basketball jerseys and apparel',
                'sort_order' => 3,
            ],
            [
                'name' => 'Running',
                'description' => 'Running singlets and activewear',
                'sort_order' => 4,
            ],
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'description' => $cat['description'],
                'sort_order' => $cat['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
