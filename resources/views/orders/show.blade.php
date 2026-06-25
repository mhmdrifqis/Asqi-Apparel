@extends('layouts.app')

@section('title', 'Order Details | Asqi Apparel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    
    <!-- Success Banner (Only show if newly created) -->
    @if(session('success') || $order->status === 'pending')
        <div class="bg-success/10 border-2 border-success text-success px-6 py-4 rounded-none mb-8 flex items-center gap-4">
            <div class="bg-success text-white p-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-lg">Order Placed Successfully!</h3>
                <p class="text-sm opacity-90">Thank you for your purchase. Your order number is {{ $order->order_number }}.</p>
            </div>
        </div>
    @endif

    <div class="bg-surface border-2 border-primary rounded-none overflow-hidden">
        
        <!-- Order Header -->
        <div class="bg-surface-secondary p-6 sm:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b-2 border-primary">
            <div>
                <p class="text-sm text-text-muted font-bold tracking-widest uppercase mb-1">Order Number</p>
                <h1 class="text-2xl font-black uppercase tracking-tight">{{ $order->order_number }}</h1>
                <p class="text-sm text-text-secondary mt-1">{{ \Carbon\Carbon::parse($order->ordered_at)->format('F j, Y, g:i a') }}</p>
            </div>
            <div class="flex gap-3">
                <span class="px-4 py-2 rounded-none text-xs font-bold uppercase tracking-wider {{ $order->status === 'pending' ? 'bg-warning/10 text-warning border-2 border-warning' : ($order->status === 'cancelled' ? 'bg-danger/10 text-danger border-2 border-danger' : 'bg-info/10 text-info border-2 border-info') }}">
                    Status: {{ $order->status }}
                </span>
                <span class="px-4 py-2 rounded-none text-xs font-bold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-success/10 text-success border-2 border-success' : 'bg-warning/10 text-warning border-2 border-warning' }}">
                    Payment: {{ $order->payment_status }}
                </span>
            </div>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Payment Action -->
            @if($order->payment_status === 'unpaid' && $order->status !== 'cancelled' && $order->payment_token)
                <div class="mb-10 bg-primary/5 border-2 border-primary rounded-none p-6 text-center">
                    <h3 class="text-lg font-bold mb-2">Awaiting Payment</h3>
                    <p class="text-text-secondary text-sm mb-4">Please complete your payment to process the order.</p>
                    <button id="pay-button" class="btn btn-primary px-8 py-3">Pay Now (Rp {{ number_format($order->total, 0, ',', '.') }})</button>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                
                <!-- Items -->
                <div>
                    <h2 class="text-sm font-bold uppercase tracking-widest text-primary mb-6">Order Items</h2>
                    <div class="space-y-6">
                        @foreach($order->items as $item)
                            <div class="flex gap-4">
                                <div class="w-20 h-24 bg-surface-secondary rounded-none border-2 border-primary flex-shrink-0 overflow-hidden">
                                    <img src="{{ $item->product_image }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <p class="font-bold text-sm mb-1 leading-tight">{{ $item->product_name }}</p>
                                    <p class="text-xs text-text-secondary mb-2">{{ $item->product_color }} / {{ $item->product_size }}</p>
                                    <div class="flex justify-between items-center mt-auto">
                                        <span class="text-sm font-semibold">Qty: {{ $item->quantity }}</span>
                                        <span class="text-sm font-bold text-primary">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Summary & Details -->
                <div class="space-y-10">
                    
                    <!-- Cost Summary -->
                    <div class="bg-surface-secondary rounded-none border-2 border-primary p-6">
                        <h2 class="text-sm font-bold uppercase tracking-widest text-primary mb-4">Summary</h2>
                        <div class="space-y-3 text-sm mb-4">
                            <div class="flex justify-between">
                                <span class="text-text-secondary">Subtotal</span>
                                <span class="font-semibold">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-text-secondary">Shipping ({{ $order->courier }})</span>
                                <span class="font-semibold">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                            </div>
                            @if($order->discount > 0)
                                <div class="flex justify-between text-success">
                                    <span>Discount</span>
                                    <span class="font-semibold">-Rp {{ number_format($order->discount, 0, ',', '.') }}</span>
                                </div>
                            @endif
                        </div>
                        <hr class="border-t-2 border-primary mb-4">
                        <div class="flex justify-between items-end">
                            <span class="font-bold uppercase tracking-wider text-sm">Total</span>
                            <span class="text-2xl font-black text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div>
                        <h2 class="text-sm font-bold uppercase tracking-widest text-primary mb-4">Shipping Information</h2>
                        @php $address = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address; @endphp
                        <div class="text-sm text-text-secondary leading-relaxed bg-surface-secondary border-2 border-primary p-4 rounded-none">
                            <p class="font-bold text-text-primary">{{ $address['recipient_name'] ?? '' }}</p>
                            <p>{{ $address['phone'] ?? '' }}</p>
                            <p class="mt-2">{{ $address['full_address'] ?? '' }}</p>
                            <p>{{ $address['city'] ?? '' }}, {{ $address['province'] ?? '' }} {{ $address['postal_code'] ?? '' }}</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@if($order->payment_status === 'unpaid' && $order->status !== 'cancelled' && $order->payment_token)
    @push('scripts')
    <!-- Midtrans Snap.js -->
    <script src="{{ \App\Models\Setting::get('midtrans_is_production') ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}" data-client-key="{{ \App\Models\Setting::get('midtrans_client_key') }}"></script>
    <script>
        document.getElementById('pay-button').onclick = function(){
            window.snap.pay('{{ $order->payment_token }}', {
                onSuccess: function(result){
                    window.location.reload();
                },
                onPending: function(result){
                    window.location.reload();
                },
                onError: function(result){
                    alert("Payment failed!");
                },
                onClose: function(){
                    // User closed popup
                }
            });
        };
    </script>
    @endpush
@endif
@endsection
