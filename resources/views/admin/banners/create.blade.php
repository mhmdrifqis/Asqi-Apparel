@extends('layouts.admin')

@section('title', 'Add Banner')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Add Banner</h1>
        <p class="text-slate-500 mt-1 text-sm">Create a new sliding banner for the homepage.</p>
    </div>
    <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 px-4 py-2 rounded-lg font-bold text-sm transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Banners
    </a>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8">
    <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                    @error('title') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
                
                <!-- Subtitle -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Subtitle</label>
                    <input type="text" name="subtitle" value="{{ old('subtitle') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                    @error('subtitle') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- CTA Text -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Button Text</label>
                        <input type="text" name="cta_text" value="{{ old('cta_text', 'Shop Now') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                        @error('cta_text') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Link URL -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Link URL</label>
                        <input type="text" name="link_url" value="{{ old('link_url') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent" placeholder="/shop/men">
                        @error('link_url') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Starts At -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Starts At (Optional)</label>
                        <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                        @error('starts_at') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    
                    <!-- Expires At -->
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Expires At (Optional)</label>
                        <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                        @error('expires_at') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
            
            <div class="space-y-6">
                <!-- Image -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Desktop Image <span class="text-danger">*</span></label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-lg bg-slate-50">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-slate-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <div class="flex text-sm text-slate-600 justify-center">
                                <label for="image" class="relative cursor-pointer bg-transparent rounded-md font-medium text-accent hover:text-accent-hover focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-accent">
                                    <span>Upload a file</span>
                                    <input id="image" name="image" type="file" class="sr-only" required accept="image/*">
                                </label>
                            </div>
                            <p class="text-xs text-slate-500">PNG, JPG, WEBP up to 5MB</p>
                        </div>
                    </div>
                    @error('image') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
                
                <!-- Sort Order -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sort Order</label>
                    <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                    @error('sort_order') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
                </div>
                
                <!-- Status -->
                <div class="flex items-center">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-4 h-4 text-accent border-slate-300 rounded focus:ring-accent">
                    <label for="is_active" class="ml-2 block text-sm font-medium text-slate-700">Active (Visible)</label>
                </div>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
            <button type="submit" class="bg-accent hover:bg-accent-hover text-white px-6 py-2.5 rounded-lg font-bold text-sm transition-colors">
                Save Banner
            </button>
        </div>
    </form>
</div>
@endsection
