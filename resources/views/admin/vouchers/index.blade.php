@extends('layouts.admin')

@section('title', 'Vouchers | Admin')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Vouchers</h1>
        <p class="text-slate-500 text-sm mt-1">Manage discount codes</p>
    </div>
    <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary py-2 px-4 text-sm flex items-center justify-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
        Add Voucher
    </a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left text-sm text-slate-600">
            <thead class="bg-slate-50 border-b border-slate-200 text-xs uppercase font-semibold text-slate-500">
                <tr>
                    <th class="p-4 w-1/4">Code & Name</th>
                    <th class="p-4 w-1/6 text-center">Discount</th>
                    <th class="p-4 w-1/6 text-center">Usage</th>
                    <th class="p-4 w-1/6 text-center">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($vouchers as $voucher)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="p-4">
                            <span class="font-bold font-mono text-slate-900 bg-slate-100 px-2 py-1 rounded">{{ $voucher->code }}</span>
                            <div class="text-xs text-slate-500 mt-1">{{ $voucher->name }}</div>
                        </td>
                        <td class="p-4 text-center font-bold text-emerald-600">
                            {{ $voucher->type === 'percentage' ? number_format($voucher->value, 0) . '%' : 'Rp ' . number_format($voucher->value, 0) }}
                        </td>
                        <td class="p-4 text-center text-xs">
                            {{ $voucher->used_count }} / {{ $voucher->usage_limit ?: '∞' }}
                        </td>
                        <td class="p-4 text-center">
                            <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase rounded-md {{ $voucher->is_active && $voucher->isValid() ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                {{ $voucher->is_active ? ($voucher->isValid() ? 'Active' : 'Expired/Maxed') : 'Inactive' }}
                            </span>
                        </td>
                        <td class="p-4 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.vouchers.edit', $voucher->id) }}" class="text-slate-400 hover:text-emerald-600 p-1" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="inline-block" onsubmit="event.preventDefault(); window.confirmDelete(this, 'Are you sure you want to delete this voucher?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-slate-400 hover:text-red-600 p-1" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-slate-500">No vouchers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($vouchers->hasPages())
    <div class="p-4 border-t border-slate-200">
        {{ $vouchers->links() }}
    </div>
    @endif
</div>
@endsection
