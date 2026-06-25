<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method', 'store_logo']);

        foreach ($data as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        // Handle File Uploads
        if ($request->hasFile('store_logo')) {
            $path = $request->file('store_logo')->store('settings', 'public');
            $logoUrl = \Illuminate\Support\Facades\Storage::url($path);
            
            $setting = Setting::firstOrCreate(
                ['key' => 'store_logo'],
                ['group' => 'general', 'label' => 'Store Logo', 'type' => 'text']
            );
            $setting->update(['value' => $logoUrl]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
