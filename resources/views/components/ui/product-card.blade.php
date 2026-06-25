@props(['product'])

<article class="group relative bg-surface hover:border-black transition-all duration-300 flex flex-col h-full border border-transparent">
    <!-- Image Container -->
    <div class="relative overflow-hidden aspect-[3/4] bg-surface-secondary">
        <!-- Badge (Sale/New) -->
        @if($product->sale_price)
            <div class="absolute top-0 left-0 z-10 bg-white text-primary text-xs font-bold uppercase tracking-widest px-3 py-1.5 border-b border-r border-black shadow-sm">
                Sale
            </div>
        @elseif($product->is_featured)
            <div class="absolute top-0 left-0 z-10 bg-primary text-white text-xs font-bold uppercase tracking-widest px-3 py-1.5">
                New
            </div>
        @endif

        <!-- Wishlist Button -->
        <div x-data="wishlistToggle({{ $product->id }}, {{ auth()->check() ? 'true' : 'false' }}, {{ auth()->check() && \App\Models\Wishlist::where('user_id', auth()->id())->where('product_id', $product->id)->exists() ? 'true' : 'false' }})" class="absolute top-2 right-2 z-10">
            <button @click.prevent="toggle" 
                    class="p-2 transition-colors focus:outline-none"
                    :class="inWishlist ? 'text-red-500 hover:text-red-600' : 'text-text-secondary hover:text-accent'">
                <svg class="w-6 h-6" :fill="inWishlist ? 'currentColor' : 'none'" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            </button>
        </div>

        <!-- Product Image -->
        <a href="{{ route('products.show', $product->slug) }}" class="block w-full h-full">
            <img src="{{ $product->primary_image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover object-center group-hover:scale-[1.02] transition-transform duration-500">
        </a>

        <!-- Quick Add Button (Bottom slide up on hover) -->
        <div class="absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out z-20">
            <button class="w-full py-3 bg-primary text-white text-sm font-bold uppercase tracking-widest hover:bg-primary-light transition-colors">
                Quick Add
            </button>
        </div>
    </div>

    <!-- Product Info -->
    <a href="{{ route('products.show', $product->slug) }}" class="flex flex-col flex-1 p-3">
        <p class="text-xs text-text-secondary mb-1 capitalize">{{ $product->category->name ?? 'Category' }}</p>
        <h3 class="text-base font-medium text-text-primary leading-tight mb-2 group-hover:underline">{{ $product->name }}</h3>
        
        <div class="mt-auto">
            @if($product->sale_price)
                <div class="flex items-center gap-2">
                    <span class="text-base font-bold text-danger">Rp{{ number_format($product->sale_price, 0, ',', '.') }}</span>
                    <span class="text-sm text-text-muted line-through">Rp{{ number_format($product->base_price, 0, ',', '.') }}</span>
                </div>
            @else
                <span class="text-base font-bold text-primary">Rp{{ number_format($product->base_price, 0, ',', '.') }}</span>
            @endif
        </div>
        
        <!-- Swatches (if variants exist) -->
        @if($product->variants && $product->variants->count() > 0)
            <div class="flex items-center gap-1 mt-3">
                @foreach($product->variants->unique('color_hex')->take(4) as $variant)
                    @if($variant->color_hex)
                        <div class="w-4 h-4 border border-border" style="background-color: {{ $variant->color_hex }}" title="{{ $variant->color }}"></div>
                    @endif
                @endforeach
                @if($product->variants->unique('color_hex')->count() > 4)
                    <span class="text-xs text-text-secondary ml-1 font-medium">+{{ $product->variants->unique('color_hex')->count() - 4 }}</span>
                @endif
            </div>
        @endif
    </a>
</article>
