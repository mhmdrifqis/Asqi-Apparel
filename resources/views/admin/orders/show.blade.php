@extends('layouts.admin')

@section('title', 'Order Details')
@section('header', 'Order ' . $order->order_number)

@section('content')
<div class="mb-6 flex items-center justify-between">
    <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-text-secondary hover:text-primary flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Back to Orders
    </a>
</div>

@if(session('success'))
    <div class="bg-success/10 border border-success text-success px-6 py-4 rounded-xl mb-6">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Left Column: Details & Items -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Order Items -->
        <div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-border">
                <h2 class="text-lg font-black uppercase tracking-tight">Order Items</h2>
            </div>
            <div class="divide-y divide-border">
                @foreach($order->items as $item)
                    <div class="p-6 flex gap-4">
                        <div class="w-16 h-20 bg-surface-secondary rounded border border-border flex-shrink-0">
                            <img src="{{ $item->product_image }}" class="w-full h-full object-cover rounded">
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm mb-1"><a href="{{ route('products.show', $item->variant->product->slug ?? '') }}" target="_blank" class="hover:text-accent">{{ $item->product_name }}</a></p>
                            <p class="text-xs text-text-secondary mb-2">Color: {{ $item->product_color }} | Size: {{ $item->product_size }} | SKU: {{ $item->variant->sku ?? 'N/A' }}</p>
                            <div class="flex justify-between mt-auto">
                                <span class="text-sm font-semibold">Qty: {{ $item->quantity }}</span>
                                <span class="text-sm font-bold text-primary">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="bg-surface-secondary p-6 space-y-2 text-sm border-t border-border">
                <div class="flex justify-between text-text-secondary">
                    <span>Subtotal</span>
                    <span class="font-semibold text-text-primary">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between text-text-secondary">
                    <span>Shipping ({{ $order->courier }})</span>
                    <span class="font-semibold text-text-primary">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                </div>
                <hr class="border-border my-2">
                <div class="flex justify-between items-end pt-2">
                    <span class="font-bold uppercase tracking-wider">Total</span>
                    <span class="text-xl font-black text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Customer & Status -->
    <div class="space-y-8">
        
        <!-- Order Status Updater -->
        <div class="bg-surface border border-border rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-black uppercase tracking-tight mb-4">Update Status</h2>
            
            <form action="{{ route('admin.orders.update_status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-2">Payment Status</label>
                    <span class="px-3 py-1.5 rounded-md text-xs font-bold uppercase tracking-wider inline-block {{ $order->payment_status === 'paid' ? 'bg-success/10 text-success border border-success/20' : 'bg-warning/10 text-warning border border-warning/20' }}">
                        {{ $order->payment_status }}
                    </span>
                    @if($order->payment_method)
                        <p class="text-xs text-text-muted mt-2">Via: {{ $order->payment_method }}</p>
                    @endif
                </div>

                <div class="mb-4">
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-2">Order Status</label>
                    <select name="status" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-text-secondary uppercase mb-2">Tracking Number</label>
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Enter AWB / Resi" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                </div>

                <button type="submit" class="w-full btn btn-primary py-3">Update Order</button>
            </form>
        </div>

        <!-- Customer & Shipping Info -->
        <div class="bg-surface border border-border rounded-xl shadow-sm p-6">
            <h2 class="text-lg font-black uppercase tracking-tight mb-4">Customer Info</h2>
            
            <div class="mb-6">
                <p class="font-bold text-sm">{{ $order->user->name ?? 'Guest User' }}</p>
                <p class="text-sm text-text-secondary">{{ $order->user->email ?? 'N/A' }}</p>
            </div>

            <h2 class="text-lg font-black uppercase tracking-tight mb-4">Shipping Info</h2>
            @php $address = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address; @endphp
            <div class="text-sm text-text-secondary space-y-1">
                <p class="font-bold text-text-primary">{{ $address['recipient_name'] ?? '' }}</p>
                <p>{{ $address['phone'] ?? '' }}</p>
                <p class="mt-2">{{ $address['full_address'] ?? '' }}</p>
                <p>{{ $address['city'] ?? '' }}, {{ $address['province'] ?? '' }} {{ $address['postal_code'] ?? '' }}</p>
            </div>
            
            <div class="mt-4 pt-4 border-t border-border">
                <p class="text-xs font-bold uppercase text-text-muted mb-1">Courier Service</p>
                <p class="font-bold text-sm">{{ $order->courier }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
