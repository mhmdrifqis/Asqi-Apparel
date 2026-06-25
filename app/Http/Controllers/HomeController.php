<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Get active banners that are currently running
        $banners = Banner::active()->current()->orderBy('sort_order')->get();

        // Get featured products
        $featuredProducts = Product::with(['category', 'variants', 'primaryImage'])
            ->active()
            ->featured()
            ->take(8)
            ->get();

        // Get top selling products
        $topSellingProducts = Product::with(['category', 'variants', 'primaryImage'])
            ->active()
            ->orderByDesc('total_sold')
            ->take(8)
            ->get();

        // Get root categories
        $categories = Category::active()->root()->orderBy('sort_order')->get();

        return view('home', compact('banners', 'featuredProducts', 'topSellingProducts', 'categories'));
    }
}
