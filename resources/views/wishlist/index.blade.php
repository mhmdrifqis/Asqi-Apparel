@extends('layouts.app')

@section('title', 'My Wishlist | Asqi Apparel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="wishlistPage()">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 mt-6 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">My Wishlist</h1>
        <span class="bg-primary text-white border-2 border-primary text-sm font-bold px-4 py-2 uppercase tracking-widest mt-4 sm:mt-0">{{ $wishlists->count() }} Items</span>
    </div>

    @if($wishlists->count() > 0)
        <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 md:gap-6">
            @foreach($wishlists as $wishlist)
                <div class="relative group" id="wishlist-item-{{ $wishlist->product_id }}">
                    <!-- Remove Button overlay -->
                    <button @click="removeWishlist({{ $wishlist->product_id }})" class="absolute top-0 right-0 z-20 p-3 bg-white text-primary border-b-2 border-l-2 border-primary hover:bg-danger hover:text-white hover:border-danger transition-colors focus:outline-none" title="Remove from wishlist">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
                    </button>
                    
                    <x-ui.product-card :product="$wishlist->product" />
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty Wishlist -->
        <div class="text-center py-24 bg-surface border-2 border-primary rounded-none mt-8">
            <svg class="w-20 h-20 mx-auto text-text-muted mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
            <h2 class="text-2xl font-black uppercase tracking-tight mb-2">No favorites yet</h2>
            <p class="text-text-secondary mb-8">Save the items you love by clicking the heart icon on any product.</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline px-8 py-3">Discover Products</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('wishlistPage', () => ({
            async removeWishlist(productId) {
                try {
                    const response = await fetch(`{{ route('wishlist.toggle') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ product_id: productId })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok && data.status === 'removed') {
                        // Fade out and remove element
                        const el = document.getElementById(`wishlist-item-${productId}`);
                        el.style.transition = 'opacity 0.3s ease';
                        el.style.opacity = '0';
                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                        
                        this.$store.toast.success(data.message);
                    } else {
                        this.$store.toast.error('Failed to remove item');
                    }
                } catch (error) {
                    this.$store.toast.error('Network error occurred');
                }
            }
        }));
    });
</script>
@endpush
@endsection
