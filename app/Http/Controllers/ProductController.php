<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Product Listing Page (PLP)
     */
    public function index(Request $request)
    {
        $query = Product::with(['category', 'primaryImage', 'variants'])->active();

        // Filter by category
        if ($request->filled('category')) {
            $categorySlug = $request->category;
            $query->whereHas('category', function ($q) use ($categorySlug) {
                $q->where('slug', $categorySlug)
                  ->orWhereHas('parent', function($q2) use ($categorySlug) {
                      $q2->where('slug', $categorySlug);
                  });
            });
        }

        // Filter by gender
        if ($request->filled('gender')) {
            $query->byGender($request->gender);
        }

        // Filter by sport type
        if ($request->filled('sport')) {
            $query->bySport($request->sport);
        }

        // Price range
        if ($request->filled('min_price') && $request->filled('max_price')) {
            $query->priceRange($request->min_price, $request->max_price);
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('sale_price', 'asc')->orderBy('base_price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('sale_price', 'desc')->orderBy('base_price', 'desc');
                break;
            case 'popular':
                $query->orderBy('total_sold', 'desc');
                break;
            case 'newest':
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(24)->withQueryString();
        // Get categories that have active products
        $categoriesQuery = Category::active()->whereHas('products', function($q) {
            $q->active();
        });

        $categories = $categoriesQuery
            ->get(['name', 'slug'])
            ->unique('name')
            ->values();
        $sports = Product::active()->whereNotNull('sport_type')->distinct()->pluck('sport_type');

        return view('products.index', compact('products', 'categories', 'sports'));
    }

    /**
     * Product Detail Page (PDP)
     */
    public function show($slug)
    {
        $product = Product::with(['category', 'images', 'variants' => function($q) {
                $q->active();
            }, 'reviews' => function($q) {
                $q->approved()->with('user')->latest()->take(5);
            }])
            ->active()
            ->where('slug', $slug)
            ->firstOrFail();

        // Group variants by color and size with stock
        $colors = $product->variants->unique('color')->map(function($variant) {
            $variantsOfColor = $variant->product->variants->where('color', $variant->color);
            return [
                'name' => $variant->color,
                'hex' => $variant->color_hex,
                'sizes' => $variantsOfColor->pluck('size')->unique()->values()->toArray(),
                'stocks' => $variantsOfColor->mapWithKeys(function($v) {
                    return [$v->size => $v->stock];
                })->toArray()
            ];
        })->values();

        $sizes = $product->variants->unique('size')->pluck('size')->values();

        $relatedProducts = Product::with(['category', 'primaryImage', 'variants'])
            ->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $inWishlist = false;
        if (auth()->check()) {
            $inWishlist = \App\Models\Wishlist::where('user_id', auth()->id())
                ->where('product_id', $product->id)
                ->exists();
        }

        return view('products.show', compact('product', 'colors', 'sizes', 'relatedProducts', 'inWishlist'));
    }

    /**
     * Live Search API
     */
    public function search(Request $request)
    {
        $term = $request->get('q');
        
        if (strlen($term) < 2) {
            return response()->json([]);
        }

        $products = Product::with('primaryImage')
            ->active()
            ->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('short_description', 'like', "%{$term}%")
                  ->orWhereHas('category', function($q) use ($term) {
                      $q->where('name', 'like', "%{$term}%");
                  });
            })
            ->take(5)
            ->get()
            ->map(function($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'price' => $product->currentPrice,
                    'image' => $product->primaryImageUrl,
                    'category' => $product->category->name ?? ''
                ];
            });

        return response()->json($products);
    }
}
