@extends('layouts.app')

@section('title', 'My Orders | Asqi Apparel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 mt-6 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">My Orders</h1>
        <span class="bg-primary text-white border-2 border-primary text-sm font-bold px-4 py-2 uppercase tracking-widest mt-4 sm:mt-0">{{ $orders->count() }} Orders</span>
    </div>

    @if($orders->isEmpty())
        <div class="text-center py-24 bg-surface border-2 border-primary rounded-none mt-8">
            <svg class="w-20 h-20 mx-auto text-text-muted mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
            <h2 class="text-2xl font-black uppercase tracking-tight mb-2">No orders yet</h2>
            <p class="text-text-secondary mb-8">Looks like you haven't made your first purchase.</p>
            <a href="{{ route('products.index') }}" class="btn btn-outline px-8 py-3">Start Shopping</a>
        </div>
    @else
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-surface border-2 border-primary rounded-none p-6">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4 pb-4 border-b border-border">
                        <div>
                            <p class="text-sm text-text-secondary mb-1">Order {{ $order->order_number }}</p>
                            <p class="font-bold">{{ $order->created_at->format('d M Y, H:i') }}</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="px-4 py-2 border-2 border-primary rounded-none text-xs font-bold uppercase tracking-widest text-primary">{{ $order->getStatusLabel() }}</span>
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline py-2 px-4 text-sm">View Details</a>
                        </div>
                    </div>
                    
                    <div class="flex justify-between items-end">
                        <div>
                            <p class="text-sm text-text-secondary mb-1">Total Payment</p>
                            <p class="text-lg font-bold text-primary">Rp {{ number_format($order->total, 0, ',', '.') }}</p>
                        </div>
                        @if($order->status === 'pending' && $order->payment_status === 'unpaid')
                            <p class="text-xs text-danger font-semibold">Waiting for payment</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
