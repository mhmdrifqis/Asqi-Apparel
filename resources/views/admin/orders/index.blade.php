@extends('layouts.admin')

@section('title', 'Manage Orders')
@section('header', 'Orders')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Orders</h1>
        <p class="text-slate-500 text-sm mt-1">Manage customer orders</p>
    </div>
</div>
<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-secondary text-text-secondary text-xs font-bold uppercase tracking-wider border-b border-border">
                    <th class="p-4">Order ID</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Date</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Payment</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border text-sm">
                @forelse($orders as $order)
                    <tr class="hover:bg-surface-secondary/50 transition-colors">
                        <td class="p-4 font-bold text-primary">{{ $order->order_number }}</td>
                        <td class="p-4">
                            <p class="font-semibold">{{ $order->user->name ?? 'Guest' }}</p>
                            @php $address = is_string($order->shipping_address) ? json_decode($order->shipping_address, true) : $order->shipping_address; @endphp
                            <p class="text-xs text-text-muted">{{ $address['recipient_name'] ?? '' }}</p>
                        </td>
                        <td class="p-4 text-text-secondary">{{ \Carbon\Carbon::parse($order->ordered_at)->format('M d, Y H:i') }}</td>
                        <td class="p-4 font-bold">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $order->payment_status === 'paid' ? 'bg-success/10 text-success border border-success/20' : 'bg-warning/10 text-warning border border-warning/20' }}">
                                {{ $order->payment_status }}
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider 
                                {{ $order->status === 'pending' ? 'bg-warning/10 text-warning border border-warning/20' : '' }}
                                {{ $order->status === 'processing' ? 'bg-info/10 text-info border border-info/20' : '' }}
                                {{ $order->status === 'shipped' || $order->status === 'delivered' ? 'bg-success/10 text-success border border-success/20' : '' }}
                                {{ $order->status === 'cancelled' ? 'bg-danger/10 text-danger border border-danger/20' : '' }}
                            ">
                                {{ $order->status }}
                            </span>
                        </td>
                        <td class="p-4 text-right">
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline py-1.5 px-3 text-xs">Manage</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="p-8 text-center text-text-muted">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div class="p-4 border-t border-border">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
