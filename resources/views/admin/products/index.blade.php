@extends('layouts.admin')

@section('title', 'Manage Products')
@section('header', 'Products')

@section('content')

<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Products</h1>
        <p class="text-slate-500 text-sm mt-1">Manage product inventory</p>
    </div>
   </div>
<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
    
    <div class="p-4 border-b border-border flex justify-between items-center bg-surface-secondary">
        <div class="relative w-64">
            <input type="text" placeholder="Search products..." class="w-full bg-surface border border-border text-text-primary rounded-lg pl-10 pr-4 py-2 text-sm focus:ring-accent focus:border-accent">
            <svg class="w-4 h-4 absolute left-3 top-2.5 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary py-2 px-4 text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Product
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-secondary text-text-secondary text-xs font-bold uppercase tracking-wider border-b border-border">
                    <th class="p-4">Product</th>
                    <th class="p-4">Category</th>
                    <th class="p-4">Price</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border text-sm">
                @forelse($products as $product)
                    <tr class="hover:bg-surface-secondary/50 transition-colors">
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-12 bg-surface-secondary rounded border border-border overflow-hidden flex-shrink-0">
                                    <img src="{{ $product->primaryImageUrl }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="font-bold text-primary">{{ mb_strimwidth($product->name, 0, 40, '...') }}</p>
                                    <p class="text-[10px] text-text-muted uppercase tracking-wider mt-0.5">Gender: {{ $product->gender }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-4 text-text-secondary">{{ $product->category->name ?? '-' }}</td>
                        <td class="p-4">
                            @if($product->sale_price)
                                <span class="font-bold text-accent block">Rp {{ number_format($product->sale_price, 0, ',', '.') }}</span>
                                <span class="text-xs text-text-muted line-through block">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            @else
                                <span class="font-bold">Rp {{ number_format($product->base_price, 0, ',', '.') }}</span>
                            @endif
                        </td>
                        <td class="p-4">
                            <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider {{ $product->is_active ? 'bg-success/10 text-success border border-success/20' : 'bg-danger/10 text-danger border border-danger/20' }}">
                                {{ $product->is_active ? 'Active' : 'Draft' }}
                            </span>
                            @if($product->is_featured)
                                <span class="px-2 py-1 rounded text-[10px] font-bold uppercase tracking-wider bg-warning/10 text-warning border border-warning/20 ml-1">
                                    Featured
                                </span>
                            @endif
                        </td>
                        <td class="p-4 text-right flex justify-end gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="text-text-secondary hover:text-accent p-1" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" onsubmit="event.preventDefault(); window.confirmDelete(this, 'Are you sure you want to delete this product?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-text-secondary hover:text-danger p-1" title="Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="p-8 text-center text-text-muted">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($products->hasPages())
        <div class="p-4 border-t border-border">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection
