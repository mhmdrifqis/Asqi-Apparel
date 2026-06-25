<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::orderBy('sort_order')->get();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:255',
            'sort_order' => 'required|integer|default:0',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $bannerData = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'cta_text' => $validated['cta_text'],
            'link_url' => $validated['link_url'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => $request->has('is_active'),
            'starts_at' => $validated['starts_at'],
            'expires_at' => $validated['expires_at'],
        ];

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('banners', 'public');
            $bannerData['image_path'] = Storage::url($path);
        }

        Banner::create($bannerData);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created successfully.');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:255',
            'sort_order' => 'required|integer',
            'is_active' => 'boolean',
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after_or_equal:starts_at',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        $bannerData = [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'],
            'cta_text' => $validated['cta_text'],
            'link_url' => $validated['link_url'],
            'sort_order' => $validated['sort_order'],
            'is_active' => $request->has('is_active'),
            'starts_at' => $validated['starts_at'],
            'expires_at' => $validated['expires_at'],
        ];

        if ($request->hasFile('image')) {
            // Delete old image
            if ($banner->image_path) {
                $oldPath = str_replace('/storage/', '', $banner->image_path);
                Storage::disk('public')->delete($oldPath);
            }
            
            $path = $request->file('image')->store('banners', 'public');
            $bannerData['image_path'] = Storage::url($path);
        }

        $banner->update($bannerData);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated successfully.');
    }

    public function destroy(Banner $banner)
    {
        if ($banner->image_path) {
            $oldPath = str_replace('/storage/', '', $banner->image_path);
            Storage::disk('public')->delete($oldPath);
        }
        
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', 'Banner deleted successfully.');
    }
}
