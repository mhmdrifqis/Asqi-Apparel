@extends('layouts.app')

@section('title', 'Shopping Cart | Asqi Apparel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="cartPage()">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 mt-6 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">My Cart</h1>
    </div>

    @if($cartItems->count() > 0)
        <div class="flex flex-col lg:flex-row gap-10">
            <!-- Cart Items -->
            <div class="flex-1">
                <div class="bg-surface rounded-none border-2 border-primary overflow-hidden">
                    <!-- Header -->
                    <div class="hidden sm:grid grid-cols-12 gap-4 p-4 border-b-2 border-primary bg-surface-secondary text-sm font-bold uppercase tracking-wider text-text-secondary">
                        <div class="col-span-6">Product</div>
                        <div class="col-span-3 text-center">Quantity</div>
                        <div class="col-span-3 text-right">Total</div>
                    </div>

                    <!-- Items -->
                    <div class="divide-y divide-border">
                        @foreach($cartItems as $item)
                            <div class="p-4 sm:grid sm:grid-cols-12 sm:gap-4 sm:items-center relative transition-colors" id="cart-item-{{ $item->id }}" :class="{ 'opacity-50 grayscale': !itemSelections[{{ $item->id }}] }">
                                
                                <!-- Product Info -->
                                <div class="col-span-6 flex items-center gap-4 mb-4 sm:mb-0">
                                    <div class="flex-shrink-0 mr-2">
                                        <input type="checkbox" x-model="itemSelections[{{ $item->id }}]" @change="toggleSelection({{ $item->id }}, itemSelections[{{ $item->id }}])" class="w-5 h-5 rounded border-gray-400 text-primary focus:ring-primary cursor-pointer transition-colors">
                                    </div>
                                    <div class="w-20 h-24 sm:w-24 sm:h-32 bg-surface border-2 border-primary rounded-none flex-shrink-0 overflow-hidden">
                                        <img src="{{ $item->variant->product->primaryImageUrl }}" alt="{{ $item->variant->product->name }}" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <p class="text-xs text-text-muted font-bold uppercase tracking-widest mb-1">{{ $item->variant->product->category->name ?? 'Category' }}</p>
                                        <a href="{{ route('products.show', $item->variant->product->slug) }}" class="font-bold text-text-primary hover:text-accent transition-colors leading-tight mb-1">{{ $item->variant->product->name }}</a>
                                        <p class="text-sm text-text-secondary mb-2">Color: {{ $item->variant->color }} | Size: {{ $item->variant->size }}</p>
                                        <p class="font-semibold sm:hidden">Rp {{ number_format($item->variant->getFinalPrice(), 0, ',', '.') }}</p>
                                    </div>
                                </div>

                                <!-- Quantity -->
                                <div class="col-span-3 flex justify-start sm:justify-center items-center">
                                    <div class="flex items-center border-2 border-primary rounded-none overflow-hidden" :class="{ 'opacity-50 pointer-events-none': !itemSelections[{{ $item->id }}] }">
                                        <button @click="updateQuantity({{ $item->id }}, {{ $item->quantity - 1 }})" class="px-3 py-1 bg-surface-secondary hover:bg-border text-text-secondary transition-colors" :disabled="isUpdating === {{ $item->id }} || {{ $item->quantity }} <= 1 || !itemSelections[{{ $item->id }}]">-</button>
                                        <span class="px-4 py-1 font-semibold text-sm w-12 text-center" x-text="itemQuantities[{{ $item->id }}] || {{ $item->quantity }}"></span>
                                        <button @click="updateQuantity({{ $item->id }}, {{ $item->quantity + 1 }})" class="px-3 py-1 bg-surface-secondary hover:bg-border text-text-secondary transition-colors" :disabled="isUpdating === {{ $item->id }} || {{ $item->quantity }} >= {{ $item->variant->stock }} || !itemSelections[{{ $item->id }}]">+</button>
                                    </div>
                                </div>

                                <!-- Total & Remove -->
                                <div class="col-span-3 flex justify-between sm:justify-end items-center mt-4 sm:mt-0">
                                    <span class="font-bold text-lg hidden sm:block">Rp {{ number_format($item->variant->getFinalPrice() * $item->quantity, 0, ',', '.') }}</span>
                                    
                                    <button @click="removeItem({{ $item->id }})" class="text-text-muted hover:text-danger p-2 sm:ml-4 transition-colors focus:outline-none" title="Remove item">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="w-full lg:w-80 flex-shrink-0">
                <div class="bg-surface rounded-none border-2 border-primary p-6 lg:sticky lg:top-24">
                    <h2 class="text-xl font-black uppercase tracking-tight mb-6">Order Summary</h2>
                    
                    <div class="space-y-4 text-sm mb-6">
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Subtotal</span>
                            <span class="font-bold" x-text="formatMoney(subtotal)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Shipping</span>
                            <span class="font-bold text-success">Calculated at checkout</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-text-secondary">Tax</span>
                            <span class="font-bold">Calculated at checkout</span>
                        </div>
                    </div>
                    
                    <hr class="border-t-2 border-primary mb-6">
                    
                    <div class="flex justify-between items-end mb-8">
                        <span class="font-bold uppercase tracking-wider">Estimated Total</span>
                        <span class="text-2xl font-black text-primary" x-text="formatMoney(subtotal)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    
                    @auth
                    <button @click="if(hasSelectedItems) window.location.href='{{ route('checkout') }}'" 
                            class="w-full py-4 text-lg text-center block font-bold tracking-widest uppercase transition-colors"
                            :class="hasSelectedItems ? 'bg-primary text-white hover:bg-primary-light cursor-pointer' : 'bg-gray-200 text-gray-500 cursor-not-allowed'"
                            :disabled="!hasSelectedItems">
                        Checkout
                    </button>
                    @else
                    <button @click="authModalOpen = true; authTab = 'login'" 
                            class="w-full py-4 text-lg text-center block font-bold tracking-widest uppercase transition-colors"
                            :class="hasSelectedItems ? 'bg-primary text-white hover:bg-primary-light cursor-pointer' : 'bg-gray-200 text-gray-500 cursor-not-allowed'"
                            :disabled="!hasSelectedItems">
                        Checkout
                    </button>
                    @endauth
                    
                    <p class="text-center text-xs text-text-muted mt-4">
                        Taxes and shipping are calculated at checkout
                    </p>
                </div>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="text-center py-24 bg-surface border-2 border-primary rounded-none mt-8">
            <svg class="w-20 h-20 mx-auto text-text-muted mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            <h2 class="text-2xl font-black uppercase tracking-tight mb-2">Your cart is empty</h2>
            <p class="text-text-secondary mb-8">Looks like you haven't added anything to your cart yet.</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline px-8 py-3">Start Shopping</a>
        </div>
    @endif
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cartPage', () => ({
            subtotal: {{ $subtotal }},
            isUpdating: null,
            itemQuantities: {},
            itemSelections: {
                @foreach($cartItems as $item)
                    {{ $item->id }}: {{ $item->is_selected ? 'true' : 'false' }},
                @endforeach
            },
            
            formatMoney(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            },
            
            get hasSelectedItems() {
                return Object.values(this.itemSelections).some(val => val === true);
            },

            async toggleSelection(id, is_selected) {
                try {
                    const response = await fetch(`/cart/${id}/toggle`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ is_selected })
                    });
                    
                    const data = await response.json();
                    if (response.ok) {
                        this.subtotal = data.subtotal;
                    } else {
                        this.$store.toast.error(data.message || 'Failed to update selection');
                    }
                } catch (error) {
                    this.$store.toast.error('Network error occurred');
                }
            },
            
            async updateQuantity(id, quantity) {
                if (quantity < 1) return;
                
                this.isUpdating = id;
                this.itemQuantities[id] = quantity;
                
                try {
                    const response = await fetch(`/cart/${id}`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ quantity })
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        this.subtotal = data.subtotal;
                        this.$store.cart.setCount(data.cart_count);
                        // Reload to reflect new line total
                        window.location.reload();
                    } else {
                        this.$store.toast.error(data.message || 'Failed to update quantity');
                        // Revert quantity
                        delete this.itemQuantities[id];
                    }
                } catch (error) {
                    this.$store.toast.error('Network error occurred');
                } finally {
                    this.isUpdating = null;
                }
            },
            
            async removeItem(id) {
                const result = await Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to remove this item from your cart?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e94560',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, remove it!',
                    background: '#ffffff',
                    color: '#111827'
                });

                if (!result.isConfirmed) return;
                
                try {
                    const response = await fetch(`/cart/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (response.ok) {
                        this.subtotal = data.subtotal;
                        this.$store.cart.setCount(data.cart_count);
                        
                        // Fade out and remove element
                        const el = document.getElementById(`cart-item-${id}`);
                        el.style.opacity = '0';
                        setTimeout(() => {
                            window.location.reload();
                        }, 300);
                        
                        this.$store.toast.success('Item removed');
                    } else {
                        this.$store.toast.error(data.message || 'Failed to remove item');
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
