@extends('layouts.app')

@section('title', $product->name . ' | Asqi Apparel')
@section('meta_description', $product->short_description)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="productDetail({{ $product->toJson() }}, {{ json_encode($colors) }}, {{ json_encode($sizes) }}, {{ $inWishlist ? 'true' : 'false' }})">
    
    <x-ui.breadcrumb :items="[
        ['label' => 'Shop', 'url' => route('products.index')],
        ['label' => $product->category->name ?? 'Category', 'url' => route('products.index', ['category' => $product->category->slug ?? ''])],
        ['label' => $product->name]
    ]" />

    <div class="flex flex-col lg:flex-row gap-10 mt-6">
        
        <!-- Product Images Gallery -->
        <div class="w-full lg:w-3/5">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach($product->images as $image)
                    <div class="aspect-[3/4] bg-surface-secondary rounded-xl overflow-hidden {{ $loop->first ? 'md:col-span-2' : '' }}">
                        <img src="{{ $image->image_path }}" alt="{{ $image->alt_text }}" class="w-full h-full object-cover object-center cursor-zoom-in hover:scale-105 transition-transform duration-500">
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Product Info & Actions -->
        <div class="w-full lg:w-2/5 lg:pl-4 lg:sticky lg:top-24 self-start">
            <!-- Badge & Title -->
            <div class="mb-6">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-text-muted font-bold tracking-widest uppercase mb-2">{{ $product->category->name ?? 'Category' }}</p>
                        <h1 class="text-3xl sm:text-4xl font-black uppercase tracking-tighter text-primary mb-2">{{ $product->name }}</h1>
                        <p class="text-text-secondary text-sm">{{ $product->short_description }}</p>
                    </div>
                    <button @click="toggleWishlist" class="p-2 rounded-full border transition-colors focus:outline-none" 
                            :class="inWishlist ? 'border-accent text-accent bg-accent/5' : 'border-border text-text-secondary hover:text-accent hover:border-accent'">
                        <svg class="w-6 h-6" :class="inWishlist ? 'fill-current' : 'fill-none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Price -->
            <div class="mb-8 flex items-end gap-3">
                @if($product->sale_price)
                    <span class="text-3xl font-black text-accent">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    <span class="text-lg text-text-muted line-through mb-1">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                    <span class="bg-accent/10 text-accent text-xs font-bold px-2 py-1 rounded-md mb-1 ml-2">SALE</span>
                @else
                    <span class="text-3xl font-black text-primary">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                @endif
            </div>

            <!-- Selectors -->
            <form @submit.prevent="addToCart" class="mb-8 space-y-6">
                <!-- Color Selector -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-bold uppercase tracking-wider text-sm">Color: <span x-text="selectedColor" class="text-text-secondary font-medium normal-case"></span></span>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <template x-for="color in colors" :key="color.name">
                            <label class="cursor-pointer">
                                <input type="radio" name="color" :value="color.name" x-model="selectedColor" class="sr-only">
                                <div class="w-10 h-10 rounded-full border-2 p-0.5 transition-all" :class="selectedColor === color.name ? 'border-primary' : 'border-transparent'">
                                    <div class="w-full h-full rounded-full border border-black/10" :style="`background-color: ${color.hex}`"></div>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Size Selector -->
                <div>
                    <div class="flex justify-between items-center mb-3">
                        <span class="font-bold uppercase tracking-wider text-sm">Size</span>
                        @if($product->size_guide_path)
                            <button type="button" @click="showSizeGuide = true" class="text-xs text-text-muted hover:text-primary underline">Size Guide</button>
                        @endif
                    </div>
                    <div class="grid grid-cols-4 sm:grid-cols-5 gap-3">
                        <template x-for="size in sizes" :key="size">
                            <label class="cursor-pointer">
                                <input type="radio" name="size" :value="size" x-model="selectedSize" :disabled="!isSizeAvailable(size)" class="sr-only">
                                <div class="h-12 border rounded-lg flex items-center justify-center font-semibold transition-colors"
                                     :class="{
                                         'border-primary bg-primary text-white': selectedSize === size,
                                         'border-border bg-surface hover:border-text-secondary text-text-primary': selectedSize !== size && isSizeAvailable(size),
                                         'border-border bg-surface-secondary text-text-muted opacity-50 cursor-not-allowed': !isSizeAvailable(size)
                                     }">
                                    <span x-text="size"></span>
                                </div>
                            </label>
                        </template>
                    </div>
                </div>

                <!-- Stock Indicator -->
                <div x-show="selectedColor && selectedSize" x-collapse>
                    <p class="text-sm font-bold text-accent flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="getStock(selectedSize)"></span> in stock
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="pt-4 flex gap-4">
                    <div class="w-32 border border-border rounded-lg flex items-center justify-between px-4 font-semibold">
                        <button type="button" @click="if(qty > 1) qty--" class="text-text-secondary hover:text-primary text-xl">-</button>
                        <span x-text="qty"></span>
                        <button type="button" @click="qty++" class="text-text-secondary hover:text-primary text-xl">+</button>
                    </div>
                    <button type="submit" class="flex-1 btn btn-primary py-4 text-lg" :disabled="!selectedColor || !selectedSize || isAdding">
                        <span x-show="!isAdding" x-text="(!selectedColor || !selectedSize) ? 'Select Options' : 'Add to Cart'"></span>
                        <span x-show="isAdding" class="flex items-center justify-center gap-2">
                            <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            Adding...
                        </span>
                    </button>
                </div>
            </form>

            <!-- Accordions -->
            <div class="border-t border-border divide-y divide-border" x-data="{ active: 1 }">
                <!-- Details -->
                <div class="py-5">
                    <button @click="active = active === 1 ? null : 1" class="flex w-full items-center justify-between text-left font-bold uppercase tracking-wider focus:outline-none">
                        Product Details
                        <svg class="w-5 h-5 transform transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 1" x-collapse class="pt-4 prose prose-sm text-text-secondary">
                        <p>{{ $product->description }}</p>
                        <ul class="mt-4 list-disc pl-5">
                            @if($product->material)<li><strong>Material:</strong> {{ $product->material }}</li>@endif
                            @if($product->technology)<li><strong>Technology:</strong> {{ $product->technology }}</li>@endif
                            @if($product->sport_type)<li><strong>Designed for:</strong> {{ $product->sport_type }}</li>@endif
                        </ul>
                    </div>
                </div>

                <!-- Care Instructions -->
                @if($product->care_instructions)
                <div class="py-5">
                    <button @click="active = active === 2 ? null : 2" class="flex w-full items-center justify-between text-left font-bold uppercase tracking-wider focus:outline-none">
                        Care Instructions
                        <svg class="w-5 h-5 transform transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 2" x-collapse class="pt-4 text-sm text-text-secondary">
                        <p>{{ $product->care_instructions }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Shipping -->
                <div class="py-5">
                    <button @click="active = active === 3 ? null : 3" class="flex w-full items-center justify-between text-left font-bold uppercase tracking-wider focus:outline-none">
                        Shipping & Returns
                        <svg class="w-5 h-5 transform transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="active === 3" x-collapse class="pt-4 text-sm text-text-secondary">
                        <p class="mb-2"><strong>Free Standard Shipping</strong> on orders over Rp 500.000.</p>
                        <p>Returns accepted within 30 days of delivery. Items must be unworn and unwashed with original tags attached.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="mt-24">
        <h2 class="text-2xl font-black uppercase tracking-tight mb-8">Complete Your Look</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
            @foreach($relatedProducts as $related)
                <x-ui.product-card :product="$related" />
            @endforeach
        </div>
    </div>
    @endif

    <!-- Size Guide Modal -->
    @if($product->size_guide_path)
    <div x-show="showSizeGuide" 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
         x-cloak
         style="display: none;">
         
        <!-- Backdrop -->
        <div x-show="showSizeGuide" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @click="showSizeGuide = false"
             class="fixed inset-0 bg-black/70 backdrop-blur-sm"></div>

        <!-- Modal Panel -->
        <div x-show="showSizeGuide"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4"
             class="relative bg-white rounded-2xl shadow-2xl max-w-3xl w-full max-h-[90vh] flex flex-col overflow-hidden">
             
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-4 border-b border-border bg-surface">
                <h3 class="text-lg font-black uppercase tracking-tight">Size Guide: {{ $product->name }}</h3>
                <button @click="showSizeGuide = false" class="text-text-muted hover:text-primary bg-surface-secondary hover:bg-border rounded-full p-2 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto">
                <img src="{{ $product->size_guide_path }}" alt="Size Guide for {{ $product->name }}" class="w-full h-auto rounded-lg">
            </div>
        </div>
    </div>
    @endif
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js" defer></script>
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('productDetail', (product, colors, sizes, inWishlistInit = false) => ({
            product: product,
            colors: colors,
            sizes: sizes,
            inWishlist: inWishlistInit,
            selectedColor: colors.length > 0 ? colors[0].name : null,
            selectedSize: null,
            qty: 1,
            isAdding: false,
            showSizeGuide: false,
            
            isSizeAvailable(size) {
                if (!this.selectedColor) return false;
                const colorObj = this.colors.find(c => c.name === this.selectedColor);
                return colorObj && colorObj.sizes.includes(size) && colorObj.stocks && colorObj.stocks[size] > 0;
            },
            
            getStock(size) {
                if (!this.selectedColor) return 0;
                const colorObj = this.colors.find(c => c.name === this.selectedColor);
                return (colorObj && colorObj.stocks && colorObj.stocks[size]) ? colorObj.stocks[size] : 0;
            },
            
            init() {
                this.$watch('selectedColor', () => {
                    if (this.selectedSize && !this.isSizeAvailable(this.selectedSize)) {
                        this.selectedSize = null;
                    }
                });
            },
            
            addToCart() {
                if (!this.selectedColor || !this.selectedSize) return;
                
                this.isAdding = true;
                
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        product_id: this.product.id,
                        color: this.selectedColor,
                        size: this.selectedSize,
                        quantity: this.qty
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.message === 'Item added to cart.') {
                        this.$store.cart.setCount(data.cart_count);
                        this.$store.toast.success('Added to cart successfully!');
                    } else {
                        this.$store.toast.error(data.message || 'Error adding to cart');
                    }
                })
                .catch(err => {
                    this.$store.toast.error('An error occurred. Please try again.');
                })
                .finally(() => {
                    this.isAdding = false;
                });
            },
            
            toggleWishlist() {
                fetch('/wishlist/toggle', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ product_id: this.product.id })
                })
                .then(res => {
                    if (res.status === 401) {
                        this.$store.toast.warning('Please login to add to wishlist');
                        return null;
                    }
                    return res.json();
                })
                .then(data => {
                    if (data) {
                        this.inWishlist = data.status === 'added';
                        if (this.inWishlist) {
                            this.$store.toast.success('Added to wishlist');
                        } else {
                            this.$store.toast.info('Removed from wishlist');
                        }
                        
                        // Update global wishlist badge if exists
                        const badge = document.getElementById('wishlist-icon-badge');
                        if (badge) {
                            badge.innerText = data.count;
                            badge.style.display = data.count > 0 ? 'flex' : 'none';
                        }
                    }
                })
                .catch(err => console.error(err));
            }
        }));
    });
</script>
@endpush
@endsection
