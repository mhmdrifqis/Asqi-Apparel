<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(15);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $this->validateProduct($request);

        DB::beginTransaction();
        try {
            // Handle Size Guide Upload
            $sizeGuidePath = null;
            if ($request->hasFile('size_guide')) {
                $file = $request->file('size_guide');
                $filename = 'sg_' . time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products/size-guides'), $filename);
                $sizeGuidePath = '/images/products/size-guides/' . $filename;
            }

            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']) . '-' . uniqid(),
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'material' => $validated['material'] ?? null,
                'care_instructions' => $validated['care_instructions'] ?? null,
                'technology' => $validated['technology'] ?? null,
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'weight_grams' => $validated['weight_grams'],
                'gender' => $validated['gender'],
                'sport_type' => $validated['sport_type'] ?? null,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'size_guide_path' => $sizeGuidePath,
            ]);

            // Handle Primary Image
            if ($request->hasFile('primary_image')) {
                $this->uploadProductImage($request->file('primary_image'), $product->id, true, 0);
            }

            // Handle Gallery Images
            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $file) {
                    $this->uploadProductImage($file, $product->id, false, $index + 1);
                }
            }

            // Handle Variants
            if (!empty($validated['variants'])) {
                $variantOrders = json_decode($request->input('ordered_variants', '[]'), true);
                foreach ($validated['variants'] as $index => $variantData) {
                    $tempId = $variantData['temp_id'] ?? '';
                    $sortOrder = array_search($tempId, $variantOrders);
                    if ($sortOrder === false) $sortOrder = $index;

                    ProductVariant::create([
                        'product_id' => $product->id,
                        'size' => $variantData['size'],
                        'color' => $variantData['color'],
                        'color_hex' => $variantData['color_hex'] ?? '#000000',
                        'sku' => $variantData['sku'],
                        'stock' => $variantData['stock'] ?? 0,
                        'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                        'sort_order' => $index,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1062) {
                preg_match("/Duplicate entry '(.*?)' for key/", $e->getMessage(), $matches);
                $duplicateValue = $matches[1] ?? 'unknown';
                return back()->withInput()->with('error', "Failed to create product: The SKU '$duplicateValue' is already in use by another variant. SKUs must be unique globally. Please change the SKU and try again.");
            }
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product)
    {
        $product->load(['variants', 'images']);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $this->validateProduct($request, $product->id);

        DB::beginTransaction();
        try {
            // Handle Size Guide
            $sizeGuidePath = $product->size_guide_path;
            if ($request->hasFile('size_guide')) {
                if ($sizeGuidePath && file_exists(public_path(ltrim($sizeGuidePath, '/')))) {
                    @unlink(public_path(ltrim($sizeGuidePath, '/')));
                }
                
                $file = $request->file('size_guide');
                $filename = 'sg_' . time() . '_' . Str::slug($validated['name']) . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/products/size-guides'), $filename);
                $sizeGuidePath = '/images/products/size-guides/' . $filename;
            }

            $product->update([
                'category_id' => $validated['category_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'short_description' => $validated['short_description'] ?? null,
                'material' => $validated['material'] ?? null,
                'care_instructions' => $validated['care_instructions'] ?? null,
                'technology' => $validated['technology'] ?? null,
                'base_price' => $validated['base_price'],
                'sale_price' => $validated['sale_price'] ?? null,
                'weight_grams' => $validated['weight_grams'],
                'gender' => $validated['gender'],
                'sport_type' => $validated['sport_type'] ?? null,
                'is_active' => $request->has('is_active'),
                'is_featured' => $request->has('is_featured'),
                'size_guide_path' => $sizeGuidePath,
            ]);

            // Handle Primary Image
            if ($request->hasFile('primary_image')) {
                $oldImages = ProductImage::where('product_id', $product->id)->where('is_primary', true)->get();
                foreach ($oldImages as $oldImg) {
                    if ($oldImg->image_path && !str_starts_with($oldImg->image_path, 'http') && file_exists(public_path(ltrim($oldImg->image_path, '/')))) {
                        @unlink(public_path(ltrim($oldImg->image_path, '/')));
                    }
                    $oldImg->delete();
                }
                $this->uploadProductImage($request->file('primary_image'), $product->id, true, 0);
            }

            // Handle Gallery Images
            if ($request->hasFile('gallery_images')) {
                $maxSort = ProductImage::where('product_id', $product->id)->max('sort_order') ?? 0;
                foreach ($request->file('gallery_images') as $index => $file) {
                    $this->uploadProductImage($file, $product->id, false, $maxSort + $index + 1);
                }
            }

            // Sync Variants
            if (!empty($validated['variants'])) {
                $existingVariantIds = [];
                $variantOrders = json_decode($request->input('ordered_variants', '[]'), true);
                foreach ($validated['variants'] as $index => $variantData) {
                    $tempId = $variantData['temp_id'] ?? '';
                    $sortOrder = array_search($tempId, $variantOrders);
                    if ($sortOrder === false) $sortOrder = $index;

                    if (isset($variantData['id'])) {
                        $variant = ProductVariant::find($variantData['id']);
                        if ($variant && $variant->product_id === $product->id) {
                            $variant->update([
                                'size' => $variantData['size'],
                                'color' => $variantData['color'],
                                'color_hex' => $variantData['color_hex'] ?? '#000000',
                                'sku' => $variantData['sku'],
                                'stock' => $variantData['stock'] ?? 0,
                                'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                                'sort_order' => $sortOrder,
                            ]);
                            $existingVariantIds[] = $variant->id;
                        }
                    } else {
                        $newVariant = ProductVariant::create([
                            'product_id' => $product->id,
                            'size' => $variantData['size'],
                            'color' => $variantData['color'],
                            'color_hex' => $variantData['color_hex'] ?? '#000000',
                            'sku' => $variantData['sku'],
                            'stock' => $variantData['stock'] ?? 0,
                            'price_adjustment' => $variantData['price_adjustment'] ?? 0,
                            'sort_order' => $sortOrder,
                        ]);
                        $existingVariantIds[] = $newVariant->id;
                    }
                }
                
                ProductVariant::where('product_id', $product->id)
                    ->whereNotIn('id', $existingVariantIds)
                    ->delete();
            } else {
                ProductVariant::where('product_id', $product->id)->delete();
            }

            DB::commit();
            return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1062) {
                preg_match("/Duplicate entry '(.*?)' for key/", $e->getMessage(), $matches);
                $duplicateValue = $matches[1] ?? 'unknown';
                return back()->withInput()->with('error', "Failed to update product: The SKU '$duplicateValue' is already in use by another variant. SKUs must be unique globally. Please change the SKU and try again.");
            }
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }

    private function validateProduct(Request $request, $productId = null)
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'short_description' => 'nullable|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'gender' => 'required|in:men,women,kids,unisex',
            'sport_type' => 'nullable|string|max:255',
            'material' => 'nullable|string|max:255',
            'technology' => 'nullable|string|max:255',
            'care_instructions' => 'nullable|string',
            'base_price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'weight_grams' => 'required|integer|min:0',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'primary_image' => ($productId ? 'nullable' : 'required') . '|image|mimes:jpeg,png,jpg,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'size_guide' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'variants' => 'nullable|array',
            'variants.*.id' => 'nullable|exists:product_variants,id',
            'variants.*.size' => 'required|string|max:50',
            'variants.*.color' => 'required|string|max:50',
            'variants.*.color_hex' => 'nullable|string|max:7',
            'variants.*.sku' => 'required|string|max:100',
            'variants.*.temp_id' => 'required|string',
            'variants.*.stock' => 'required|integer|min:0',
            'variants.*.price_adjustment' => 'nullable|numeric',
        ]);
    }

    private function uploadProductImage($file, $productId, $isPrimary, $sortOrder)
    {
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('images/products'), $filename);
        
        ProductImage::create([
            'product_id' => $productId,
            'image_path' => '/images/products/' . $filename,
            'is_primary' => $isPrimary,
            'sort_order' => $sortOrder
        ]);
    }
}
