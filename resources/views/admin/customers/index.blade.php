@extends('layouts.admin')

@section('title', 'Customers | Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Customers</h1>
        <p class="text-slate-500 text-sm mt-1">View registered customers and their activity</p>
    </div>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                <tr>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Phone</th>
                    <th class="p-4 text-center">Total Orders</th>
                    <th class="p-4">Joined At</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($customers as $customer)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4 font-bold text-slate-900">{{ $customer->name }}</td>
                        <td class="p-4 text-slate-500">{{ $customer->email }}</td>
                        <td class="p-4 text-slate-500">{{ $customer->phone ?? '-' }}</td>
                        <td class="p-4 text-center">
                            <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-100 text-xs font-bold text-slate-700">
                                {{ $customer->orders_count }}
                            </span>
                        </td>
                        <td class="p-4 text-slate-500 text-xs">{{ $customer->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">No customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($customers->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $customers->links() }}
    </div>
    @endif
</div>
@endsection
