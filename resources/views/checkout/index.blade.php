@extends('layouts.app')

@section('title', 'Checkout | Asqi Apparel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10" x-data="checkoutProcess({{ json_encode($provinces) }}, {{ $subtotal }}, {{ $totalWeight }})">
    <div class="flex flex-col lg:flex-row gap-10">
        
        <!-- Checkout Form -->
        <div class="flex-1">
            <div class="bg-surface rounded-xl border border-border p-6 sm:p-8">
                <h1 class="text-2xl font-black uppercase tracking-tight mb-8">Shipping Information</h1>
                
                <form id="checkout-form" @submit.prevent="placeOrder">
                    
                    <!-- Contact -->
                    <div class="mb-8">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-primary mb-4">Contact</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">Recipient Name</label>
                                <input type="text" x-model="form.recipient_name" required class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">Phone Number</label>
                                <input type="tel" x-model="form.phone" required class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="mb-8">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-primary mb-4">Address</h2>
                        
                        <div class="mb-4">
                            <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">Full Address</label>
                            <textarea x-model="form.full_address" required rows="3" class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent" placeholder="Street name, building, house number..."></textarea>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">Province</label>
                                <select x-model="selectedProvinceId" @change="fetchCities" required class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                                    <option value="">Select Province</option>
                                    <template x-for="prov in provinces" :key="prov.province_id">
                                        <option :value="prov.province_id" x-text="prov.province"></option>
                                    </template>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">City / Regency</label>
                                <select x-model="selectedCityId" @change="calculateShipping" required :disabled="!cities.length" class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent disabled:opacity-50">
                                    <option value="">Select City</option>
                                    <template x-for="city in cities" :key="city.city_id">
                                        <option :value="city.city_id" x-text="city.type + ' ' + city.city_name"></option>
                                    </template>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">Postal Code</label>
                                <input type="text" x-model="form.postal_code" required class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-text-secondary uppercase mb-1">Courier</label>
                                <select x-model="form.courier" @change="calculateShipping" required class="w-full border border-border bg-surface text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                                    <option value="jne">JNE</option>
                                    <option value="pos">POS Indonesia</option>
                                    <option value="tiki">TIKI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Method -->
                    <div class="mb-8" x-show="shippingOptions.length > 0">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-primary mb-4">Shipping Method</h2>
                        <div class="space-y-3">
                            <template x-for="(opt, index) in shippingOptions" :key="index">
                                <label class="flex items-center justify-between p-4 border border-border rounded-lg cursor-pointer transition-colors" :class="form.shipping_service === opt.service ? 'border-accent bg-accent/5' : 'hover:border-text-muted'">
                                    <div class="flex items-center gap-3">
                                        <input type="radio" name="shipping_method" :value="opt.service" x-model="form.shipping_service" @change="setShippingCost(opt.cost[0].value)" class="text-accent focus:ring-accent w-4 h-4">
                                        <div>
                                            <p class="font-bold text-sm" x-text="opt.service + ' (' + opt.cost[0].etd + ' Days)'"></p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-sm text-primary" x-text="formatMoney(opt.cost[0].value)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div x-show="isLoadingShipping" class="flex justify-center my-6">
                        <svg class="animate-spin h-6 w-6 text-accent" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>

                </form>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="w-full lg:w-96 flex-shrink-0">
            <div class="bg-surface rounded-xl border border-border p-6 lg:sticky lg:top-24">
                <h2 class="text-xl font-black uppercase tracking-tight mb-6">Order Summary</h2>
                
                <!-- Items Mini List -->
                <div class="space-y-4 mb-6 max-h-60 overflow-y-auto custom-scrollbar pr-2">
                    @foreach($cart->items as $item)
                        <div class="flex gap-3 items-center">
                            <div class="relative w-16 h-16 bg-surface-secondary rounded border border-border flex-shrink-0">
                                <img src="{{ $item->variant->product->primaryImageUrl }}" class="w-full h-full object-cover rounded">
                                <span class="absolute -top-2 -right-2 w-5 h-5 bg-text-secondary text-white text-[10px] font-bold flex items-center justify-center rounded-full">{{ $item->quantity }}</span>
                            </div>
                            <div class="flex-1 text-sm">
                                <p class="font-semibold truncate">{{ $item->variant->product->name }}</p>
                                <p class="text-xs text-text-muted">{{ $item->variant->color }} / {{ $item->variant->size }}</p>
                            </div>
                            <span class="font-bold text-sm whitespace-nowrap">Rp {{ number_format($item->variant->getFinalPrice() * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>

                <hr class="border-border mb-6">
                
                <div class="space-y-3 text-sm mb-6">
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Subtotal</span>
                        <span class="font-bold">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Total Weight</span>
                        <span class="font-medium">{{ number_format($totalWeight / 1000, 1) }} kg</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-text-secondary">Shipping</span>
                        <span class="font-bold" x-text="form.shipping_cost > 0 ? formatMoney(form.shipping_cost) : '-'"></span>
                    </div>
                </div>
                
                <hr class="border-border mb-6">
                
                <div class="flex justify-between items-end mb-8">
                    <span class="font-bold uppercase tracking-wider">Total</span>
                    <span class="text-2xl font-black text-primary" x-text="formatMoney(subtotal + form.shipping_cost)">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                
                <button type="submit" form="checkout-form" class="w-full btn btn-primary py-4 text-lg shadow-lg hover:shadow-xl transition-all" :disabled="!isReadyToPay || isProcessing">
                    <span x-show="!isProcessing">Pay Now</span>
                    <span x-show="isProcessing" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>

    </div>
</div>

@push('scripts')
<!-- Midtrans Snap.js -->
<script src="{{ \App\Models\Setting::get('midtrans_is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ \App\Models\Setting::get('midtrans_client_key') }}"></script>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('checkoutProcess', (provinces, subtotal, weight) => ({
            provinces: provinces,
            subtotal: subtotal,
            weight: weight,
            
            selectedProvinceId: '',
            selectedCityId: '',
            cities: [],
            
            shippingOptions: [],
            isLoadingShipping: false,
            isProcessing: false,
            
            form: {
                recipient_name: '{{ Auth::user()->name }}',
                phone: '{{ Auth::user()->phone }}',
                full_address: '',
                province: '',
                city: '',
                postal_code: '',
                courier: 'jne',
                shipping_service: '',
                shipping_cost: 0
            },
            
            formatMoney(amount) {
                return 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            },
            
            get isReadyToPay() {
                return this.form.recipient_name && this.form.phone && this.form.full_address && 
                       this.selectedProvinceId && this.selectedCityId && this.form.postal_code && 
                       this.form.shipping_service && this.form.shipping_cost > 0;
            },
            
            async fetchCities() {
                this.cities = [];
                this.selectedCityId = '';
                this.shippingOptions = [];
                this.form.shipping_service = '';
                this.form.shipping_cost = 0;
                
                if (!this.selectedProvinceId) return;
                
                const prov = this.provinces.find(p => p.province_id == this.selectedProvinceId);
                if (prov) this.form.province = prov.province;
                
                try {
                    const res = await fetch(`/checkout/cities/${this.selectedProvinceId}`);
                    this.cities = await res.json();
                } catch (e) {
                    this.$store.toast.error('Failed to load cities');
                }
            },
            
            async calculateShipping() {
                this.shippingOptions = [];
                this.form.shipping_service = '';
                this.form.shipping_cost = 0;
                
                if (!this.selectedCityId || !this.form.courier) return;
                
                const city = this.cities.find(c => c.city_id == this.selectedCityId);
                if (city) this.form.city = city.type + ' ' + city.city_name;
                
                this.isLoadingShipping = true;
                
                try {
                    const res = await fetch('/checkout/shipping-cost', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            destination_city_id: this.selectedCityId,
                            weight: this.weight,
                            courier: this.form.courier
                        })
                    });
                    
                    const data = await res.json();
                    if (data && data.length > 0) {
                        this.shippingOptions = data;
                        // Auto select first option
                        if (this.shippingOptions[0] && this.shippingOptions[0].cost.length > 0) {
                            this.form.shipping_service = this.shippingOptions[0].service;
                            this.form.shipping_cost = this.shippingOptions[0].cost[0].value;
                        }
                    } else {
                        this.$store.toast.warning('No shipping methods available for this destination.');
                    }
                } catch (e) {
                    this.$store.toast.error('Failed to calculate shipping');
                } finally {
                    this.isLoadingShipping = false;
                }
            },
            
            setShippingCost(cost) {
                this.form.shipping_cost = cost;
            },
            
            async placeOrder() {
                if (!this.isReadyToPay) return;
                
                this.isProcessing = true;
                
                try {
                    const res = await fetch('/orders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(this.form)
                    });
                    
                    const data = await res.json();
                    
                    if (res.ok && data.token) {
                        // Open Midtrans Snap Window
                        window.snap.pay(data.token, {
                            onSuccess: function(result){
                                window.location.href = `/orders/${data.order_id}`;
                            },
                            onPending: function(result){
                                window.location.href = `/orders/${data.order_id}`;
                            },
                            onError: function(result){
                                alert("Payment failed!");
                                window.location.href = `/orders/${data.order_id}`;
                            },
                            onClose: function(){
                                window.location.href = `/orders/${data.order_id}`;
                            }
                        });
                    } else {
                        this.$store.toast.error(data.message || 'Failed to place order');
                        this.isProcessing = false;
                    }
                } catch (e) {
                    this.$store.toast.error('Network error occurred');
                    this.isProcessing = false;
                }
            }
        }));
    });
</script>
@endpush
@endsection
