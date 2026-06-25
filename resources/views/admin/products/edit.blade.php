@extends('layouts.admin')

@section('title', 'Edit Product')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Edit Product</h1>
        <p class="text-slate-500 mt-1 text-sm">Update details, variants, and images for {{ $product->name }}</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('products.show', $product->slug) }}" target="_blank" class="inline-flex items-center gap-2 bg-slate-800 hover:bg-slate-700 text-white px-4 py-2 rounded-lg font-bold text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            View in Store
        </a>
        <a href="{{ route('admin.products.index') }}" class="inline-flex items-center gap-2 bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 px-4 py-2 rounded-lg font-bold text-sm transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 sm:p-8" x-data="productForm()">
    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" @submit="updateOrder">
        @csrf
        @method('PUT')
        <input type="hidden" id="ordered_variants" name="ordered_variants">
        
        <!-- Basic Information -->
        <h2 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6">Basic Information</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Product Name <span class="text-danger">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                @error('name') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Category <span class="text-danger">*</span></label>
                <select name="category_id" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Gender <span class="text-danger">*</span></label>
                <select name="gender" required class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                    <option value="men" {{ old('gender', $product->gender) == 'men' ? 'selected' : '' }}>Men</option>
                    <option value="women" {{ old('gender', $product->gender) == 'women' ? 'selected' : '' }}>Women</option>
                    <option value="kids" {{ old('gender', $product->gender) == 'kids' ? 'selected' : '' }}>Kids</option>
                    <option value="unisex" {{ old('gender', $product->gender) == 'unisex' ? 'selected' : '' }}>Unisex</option>
                </select>
                @error('gender') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Base Price (Rp) <span class="text-danger">*</span></label>
                <input type="number" name="base_price" value="{{ old('base_price', $product->base_price) }}" required min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                @error('base_price') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sale Price (Rp)</label>
                <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                @error('sale_price') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Weight (Grams) <span class="text-danger">*</span></label>
                <input type="number" name="weight_grams" value="{{ old('weight_grams', $product->weight_grams) }}" required min="0" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
                @error('weight_grams') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Sport Type</label>
                <input type="text" name="sport_type" value="{{ old('sport_type', $product->sport_type) }}" placeholder="e.g. Running, Training" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
            </div>
            
            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Short Description</label>
                <input type="text" name="short_description" value="{{ old('short_description', $product->short_description) }}" maxlength="255" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">
            </div>

            <div class="md:col-span-2">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Full Description</label>
                <textarea name="description" rows="4" class="w-full bg-slate-50 border border-slate-200 text-slate-900 rounded-lg p-3 text-sm focus:ring-accent focus:border-accent">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        <!-- Images & Size Guide -->
        <h2 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6 mt-10">Images & Size Guide</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50 flex flex-col items-center">
                <label class="block w-full text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Primary Image</label>
                <div class="w-32 h-40 bg-white border border-slate-200 rounded overflow-hidden mb-3">
                    <img src="{{ $product->primaryImageUrl }}" class="w-full h-full object-cover">
                </div>
                <input type="file" name="primary_image" accept="image/jpeg,image/png,image/webp" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-1.5 text-xs focus:ring-accent focus:border-accent">
                <p class="text-[10px] text-slate-500 mt-1">Upload new to replace.</p>
                @error('primary_image') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
            
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50 flex flex-col items-center">
                <label class="block w-full text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Gallery Images</label>
                <div class="flex gap-2 flex-wrap mb-3 w-full max-h-40 overflow-y-auto">
                    @foreach($product->images->where('is_primary', false) as $img)
                        <div class="w-16 h-20 bg-white border border-slate-200 rounded overflow-hidden">
                            <img src="{{ $img->image_path }}" class="w-full h-full object-cover">
                        </div>
                    @endforeach
                </div>
                <input type="file" name="gallery_images[]" multiple accept="image/jpeg,image/png,image/webp" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-1.5 text-xs focus:ring-accent focus:border-accent">
                <p class="text-[10px] text-slate-500 mt-1">Upload new images to append to gallery.</p>
                @error('gallery_images.*') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
            
            <div class="p-4 border border-slate-200 rounded-lg bg-slate-50 flex flex-col items-center">
                <label class="block w-full text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Size Guide Image</label>
                <div class="w-32 h-40 bg-white border border-slate-200 rounded overflow-hidden mb-3 flex items-center justify-center">
                    @if($product->size_guide_path)
                        <img src="{{ $product->size_guide_path }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs text-slate-400 text-center px-2">No size guide uploaded</span>
                    @endif
                </div>
                <input type="file" name="size_guide" accept="image/jpeg,image/png,image/webp" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-1.5 text-xs focus:ring-accent focus:border-accent">
                <p class="text-[10px] text-slate-500 mt-1">Upload to add or replace.</p>
                @error('size_guide') <p class="text-danger text-xs mt-1 font-medium">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Product Variants -->
        <h2 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6 mt-10 flex justify-between items-center">
            <span>Product Variants (Sizes/Colors)</span>
            <button type="button" @click="addVariant" class="text-xs bg-slate-800 text-white px-3 py-1.5 rounded hover:bg-slate-700 transition-colors">
                + Add Variant
            </button>
        </h2>
        
        <div id="variants-list" class="space-y-4 mb-8">
            <template x-for="(variant, index) in variants" :key="variant.temp_id">
                <div class="flex flex-wrap md:flex-nowrap gap-4 items-start bg-slate-50 p-4 border border-slate-200 rounded-lg relative overflow-hidden">
                    
                    <!-- Drag Handle -->
                    <div class="pt-5 hidden md:block">
                        <button type="button" class="drag-handle cursor-grab text-slate-400 hover:text-slate-600 p-2 focus:outline-none" title="Drag to reorder">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
                        </button>
                    </div>
                    <!-- Subtle overlay if marked for deletion -->
                    <div x-show="variant.deleted" class="absolute inset-0 bg-red-50/80 z-10 flex items-center justify-center">
                        <span class="text-red-600 font-bold uppercase tracking-wider">Marked for Deletion</span>
                        <button type="button" @click="restoreVariant(index)" class="ml-4 text-xs bg-white border border-red-300 px-3 py-1 rounded shadow-sm text-slate-700">Restore</button>
                    </div>
                    
                    <input type="hidden" :name="`variants[${index}][id]`" x-model="variant.id" x-bind:disabled="variant.deleted">
                    <input type="hidden" :name="`variants[${index}][temp_id]`" :value="variant.temp_id" x-bind:disabled="variant.deleted">
                    <input type="hidden" class="variant-row-id" :value="variant.temp_id" x-bind:disabled="variant.deleted">
                    
                    <div class="w-full md:w-1/6">
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider">SKU <span class="text-danger">*</span></label>
                            <button type="button" @click="generateSku(index)" x-bind:disabled="variant.deleted" class="text-[9px] font-bold text-accent hover:underline flex items-center gap-0.5 disabled:opacity-50 disabled:cursor-not-allowed" title="Auto Generate SKU">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg> Auto
                            </button>
                        </div>
                        <input type="text" :name="`variants[${index}][sku]`" x-model="variant.sku" required x-bind:disabled="variant.deleted" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-2 text-sm">
                    </div>
                    <div class="w-full md:w-1/6">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Size <span class="text-danger">*</span></label>
                        <input type="text" :name="`variants[${index}][size]`" x-model="variant.size" placeholder="S, M, L" required x-bind:disabled="variant.deleted" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-2 text-sm">
                    </div>
                    <div class="w-full md:w-1/6">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Color Name <span class="text-danger">*</span></label>
                        <input type="text" :name="`variants[${index}][color]`" x-model="variant.color" placeholder="Black" required x-bind:disabled="variant.deleted" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-2 text-sm">
                    </div>
                    <div class="w-full md:w-1/6">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Color Hex</label>
                        <div class="flex gap-2">
                            <input type="color" :name="`variants[${index}][color_hex]`" x-model="variant.color_hex" x-bind:disabled="variant.deleted" class="h-9 w-9 rounded cursor-pointer border-0 p-0">
                            <input type="text" x-model="variant.color_hex" x-bind:disabled="variant.deleted" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-2 text-sm">
                        </div>
                    </div>
                    <div class="w-full md:w-1/6">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Stock <span class="text-danger">*</span></label>
                        <input type="number" :name="`variants[${index}][stock]`" x-model="variant.stock" required min="0" x-bind:disabled="variant.deleted" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-2 text-sm">
                    </div>
                    <div class="w-full md:w-1/6">
                        <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Price Adj (+/-)</label>
                        <input type="number" :name="`variants[${index}][price_adjustment]`" x-model="variant.price_adjustment" placeholder="0" x-bind:disabled="variant.deleted" class="w-full bg-white border border-slate-200 text-slate-900 rounded p-2 text-sm">
                    </div>
                    <div class="pt-5">
                        <button type="button" @click="removeVariant(index)" class="text-danger hover:text-red-700 p-2" title="Remove Variant">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                        </button>
                    </div>
                </div>
            </template>
            <div x-show="variants.filter(v => !v.deleted).length === 0" class="text-center py-6 border-2 border-dashed border-slate-300 rounded-lg text-slate-500 text-sm">
                No active variants. Click "Add Variant" to add sizes and colors.
            </div>
        </div>

        <!-- Status -->
        <h2 class="text-lg font-bold text-slate-800 border-b border-slate-200 pb-2 mb-6 mt-10">Status</h2>
        <div class="flex items-center gap-8 p-4 bg-slate-50 rounded-lg border border-slate-200 mb-8">
            <div class="flex items-center">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }} class="w-5 h-5 text-accent border-slate-300 rounded focus:ring-accent">
                <label for="is_active" class="ml-2 block text-sm font-bold text-slate-700">Active (Visible in Store)</label>
            </div>
            <div class="flex items-center">
                <input type="hidden" name="is_featured" value="0">
                <input type="checkbox" name="is_featured" id="is_featured" value="1" {{ old('is_featured', $product->is_featured) ? 'checked' : '' }} class="w-5 h-5 text-accent border-slate-300 rounded focus:ring-accent">
                <label for="is_featured" class="ml-2 block text-sm font-bold text-slate-700">Featured (Show on Homepage)</label>
            </div>
        </div>
        
        <div class="mt-8 pt-6 border-t border-slate-200 flex justify-end">
            <button type="submit" class="bg-accent hover:bg-accent-hover text-white px-8 py-3 rounded-lg font-bold text-sm transition-colors">
                Save Changes
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productForm', () => ({
            variants: {!! json_encode($product->variants->map(function($v) {
                return [
                    'id' => $v->id,
                    'temp_id' => 'v_' . $v->id,
                    'sku' => $v->sku,
                    'size' => $v->size,
                    'color' => $v->color,
                    'color_hex' => $v->color_hex,
                    'stock' => $v->stock,
                    'price_adjustment' => $v->price_adjustment,
                    'deleted' => false
                ];
            })->values()) !!},
            
            init() {
                this.$nextTick(() => {
                    let el = document.getElementById('variants-list');
                    if (el) {
                        Sortable.create(el, {
                            handle: '.drag-handle',
                            animation: 150,
                            onEnd: (evt) => {
                                let item = this.variants.splice(evt.oldIndex, 1)[0];
                                this.variants.splice(evt.newIndex, 0, item);
                            }
                        });
                    }
                });
            },
            
            addVariant() {
                this.variants.push({
                    id: '',
                    temp_id: 'v_' + Date.now(),
                    sku: '{{ Str::slug($product->name) }}-',
                    size: '',
                    color: '',
                    color_hex: '#000000',
                    stock: 0,
                    price_adjustment: 0,
                    deleted: false
                });
            },
            
            updateOrder() {
                let order = [];
                document.querySelectorAll('.variant-row-id:not([disabled])').forEach(el => {
                    order.push(el.value);
                });
                document.getElementById('ordered_variants').value = JSON.stringify(order);
            },
            
            removeVariant(index) {
                if (this.variants[index].id) {
                    this.variants[index].deleted = true;
                } else {
                    this.variants.splice(index, 1);
                }
            },
            
            restoreVariant(index) {
                this.variants[index].deleted = false;
            },
            generateSku(index) {
                let variant = this.variants[index];
                let productNameInput = document.querySelector('input[name="name"]');
                
                // Get Base (first 3 letters of product name)
                let base = productNameInput && productNameInput.value 
                           ? productNameInput.value.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase() 
                           : 'PRD';
                           
                // Get Color (first 3 letters)
                let color = variant.color 
                            ? variant.color.replace(/[^a-zA-Z0-9]/g, '').substring(0, 3).toUpperCase() 
                            : 'CLR';
                            
                // Get Size
                let size = variant.size 
                           ? variant.size.toUpperCase().replace(/\s+/g, '') 
                           : 'SZ';
                           
                let random = Math.random().toString(36).substring(2, 5).toUpperCase();
                           
                variant.sku = `${base}-${color}-${size}-${random}`;
            }
        }));
    });
</script>
@endpush
@endsection
