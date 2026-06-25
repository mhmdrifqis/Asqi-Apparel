@extends('layouts.admin')

@section('title', 'Add Voucher | Admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Add Voucher</h1>
    </div>
    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline py-2 px-4 text-sm">Back</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm max-w-2xl">
    <form action="{{ route('admin.vouchers.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
        @csrf
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Voucher Code</label>
                <input type="text" name="code" value="{{ old('code') }}" required class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500 font-mono uppercase" placeholder="e.g. SUMMER20">
                @error('code') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Campaign Name</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Discount Percentage (%)</label>
                <input type="number" name="value" value="{{ old('value') }}" min="1" max="100" required class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
                @error('value') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Usage Limit (Optional)</label>
                <input type="number" name="usage_limit" value="{{ old('usage_limit') }}" min="1" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500" placeholder="e.g. 100">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Min. Order Amount (Rp)</label>
                <input type="number" name="min_order" value="{{ old('min_order') }}" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Max. Discount Amount (Rp)</label>
                <input type="number" name="max_discount" value="{{ old('max_discount') }}" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Starts At</label>
                <input type="datetime-local" name="starts_at" value="{{ old('starts_at') }}" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Expires At</label>
                <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
        </div>

        <div class="pt-4">
            <label class="flex items-center cursor-pointer">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                <span class="ml-3 font-semibold text-slate-700">Active</span>
            </label>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="btn btn-primary py-3 px-8">Save Voucher</button>
        </div>
    </form>
</div>
@endsection
