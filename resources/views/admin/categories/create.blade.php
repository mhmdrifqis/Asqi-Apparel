@extends('layouts.admin')

@section('title', 'Add Category | Admin')

@section('content')
<div class="mb-6 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-black uppercase tracking-tight text-slate-900">Add Category</h1>
    </div>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline py-2 px-4 text-sm">Back</a>
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm max-w-2xl">
    <form action="{{ route('admin.categories.store') }}" method="POST" class="p-6 md:p-8 space-y-6">
        @csrf
        
        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Category Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Description</label>
            <textarea name="description" rows="3" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">{{ old('description') }}</textarea>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Sort Order</label>
                <input type="number" name="sort_order" value="{{ old('sort_order', 0) }}" class="w-full border border-slate-300 rounded-lg p-3 focus:ring-emerald-500 focus:border-emerald-500">
            </div>
            <div class="flex items-center pt-8">
                <label class="flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="w-5 h-5 text-emerald-600 border-slate-300 rounded focus:ring-emerald-500">
                    <span class="ml-3 font-semibold text-slate-700">Active</span>
                </label>
            </div>
        </div>

        <div class="pt-4 border-t border-slate-100 flex justify-end">
            <button type="submit" class="btn btn-primary py-3 px-8">Save Category</button>
        </div>
    </form>
</div>
@endsection
