@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('header', 'Overview')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    
    <!-- Total Sales -->
    <div class="bg-surface border border-border rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-text-secondary text-sm font-bold uppercase tracking-wider">Total Sales</h3>
            <div class="bg-success/10 text-success p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-black text-primary">Rp {{ number_format($totalSales, 0, ',', '.') }}</p>
    </div>

    <!-- Total Orders -->
    <div class="bg-surface border border-border rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-text-secondary text-sm font-bold uppercase tracking-wider">Total Orders</h3>
            <div class="bg-info/10 text-info p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-black text-primary">{{ number_format($totalOrders) }}</p>
    </div>

    <!-- Products -->
    <div class="bg-surface border border-border rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-text-secondary text-sm font-bold uppercase tracking-wider">Products</h3>
            <div class="bg-warning/10 text-warning p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
        </div>
        <p class="text-3xl font-black text-primary">{{ number_format($totalProducts) }}</p>
    </div>

    <!-- Customers -->
    <div class="bg-surface border border-border rounded-xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-text-secondary text-sm font-bold uppercase tracking-wider">Customers</h3>
            <div class="bg-accent/10 text-accent p-2 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
        </div>
        <p class="text-3xl font-black text-primary">{{ number_format($totalCustomers) }}</p>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Sales Chart Mockup -->
    <div class="lg:col-span-2 bg-surface border border-border rounded-xl p-6 shadow-sm">
        <h2 class="text-lg font-bold mb-6">Sales Last 7 Days</h2>
        <div class="h-64 flex items-end gap-2 pb-6 border-b border-l border-border px-4 pt-4">
            @php $max = max($salesData['data']) ?: 1; @endphp
            @foreach($salesData['data'] as $index => $amount)
                <div class="flex-1 flex flex-col items-center gap-2 group relative">
                    <div class="absolute -top-10 bg-text-primary text-surface text-xs py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity">Rp {{ number_format($amount, 0, ',', '.') }}</div>
                    <div class="w-full bg-accent/80 hover:bg-accent rounded-t-sm transition-all" style="height: {{ ($amount / $max) * 100 }}%"></div>
                    <span class="text-xs text-text-muted mt-2 rotate-45 origin-left">{{ $salesData['labels'][$index] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="bg-surface border border-border rounded-xl p-6 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold">Recent Orders</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-accent hover:underline">View All</a>
        </div>
        <div class="space-y-4">
            @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-3 hover:bg-surface-secondary rounded-lg transition-colors border border-transparent hover:border-border">
                    <div>
                        <a href="{{ route('admin.orders.show', $order->id) }}" class="font-bold text-sm text-primary hover:text-accent block">{{ $order->order_number }}</a>
                        <span class="text-xs text-text-secondary">{{ $order->user->name ?? 'Guest' }}</span>
                    </div>
                    <div class="text-right">
                        <span class="block font-bold text-sm">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                        <span class="text-[10px] uppercase tracking-wider font-bold px-2 py-0.5 rounded-full {{ $order->status === 'pending' ? 'bg-warning/20 text-warning' : 'bg-info/20 text-info' }}">{{ $order->status }}</span>
                    </div>
                </div>
            @empty
                <p class="text-sm text-text-muted text-center py-4">No recent orders.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
