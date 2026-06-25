@extends('layouts.app')

@section('title', 'Shop | Asqi Apparel')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ filtersOpen: false }">
    <x-ui.breadcrumb :items="[['label' => 'Shop']]" />

    <!-- Top Bar: Title, Count, Filter & Sort -->
    <div class="flex flex-col sm:flex-row sm:items-end justify-between mb-8 mt-6 pb-4 border-b-2 border-primary">
        <div>
            <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">
                @if(request('category'))
                    {{ \App\Models\Category::where('slug', request('category'))->first()->name ?? 'Shop' }}
                @else
                    All Products
                @endif
            </h1>
            <p class="text-text-secondary mt-1 font-bold">[{{ $products->total() }}]</p>
        </div>
        
        <div class="mt-4 sm:mt-0 flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4">
            <button @click="filtersOpen = true" class="flex items-center gap-2 font-bold uppercase tracking-widest text-sm border-2 border-primary px-6 py-3 hover:bg-primary hover:text-white transition-colors">
                <span>Filter & Sort</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
            </button>
        </div>
    </div>

    <!-- Main Product Area -->
    <main class="w-full">
        <!-- Product Grid -->
        @if($products->count() > 0)
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4 sm:gap-6">
                @foreach($products as $product)
                    <x-ui.product-card :product="$product" />
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-12 flex justify-center border-t-2 border-primary pt-8">
                {{ $products->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-20 bg-surface-secondary border border-border">
                <svg class="w-16 h-16 mx-auto text-text-muted mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <h2 class="text-2xl font-black uppercase tracking-tight mb-2">No products found</h2>
                <p class="text-text-secondary font-medium mb-6">Try adjusting your filters or search criteria.</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline uppercase font-bold tracking-widest border-2 border-primary">Clear All Filters</a>
            </div>
        @endif
    </main>

    <!-- Filter Modal (Centered Pop-up) -->
    <div class="relative z-50" x-show="filtersOpen" x-cloak>
        <!-- Backdrop -->
        <div x-show="filtersOpen" 
             x-transition:enter="ease-out duration-150" 
             x-transition:enter-start="opacity-0" 
             x-transition:enter-end="opacity-100" 
             x-transition:leave="ease-in duration-100" 
             x-transition:leave-start="opacity-100" 
             x-transition:leave-end="opacity-0" 
             class="fixed inset-0 bg-black/60 transition-opacity" 
             @click="filtersOpen = false"></div>

        <div class="fixed inset-0 z-10 w-screen overflow-y-auto pointer-events-none">
            <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
                <div x-show="filtersOpen" 
                     x-transition:enter="ease-out duration-150" 
                     x-transition:enter-start="opacity-0 scale-95" 
                     x-transition:enter-end="opacity-100 scale-100" 
                     x-transition:leave="ease-in duration-100" 
                     x-transition:leave-start="opacity-100 scale-100" 
                     x-transition:leave-end="opacity-0 scale-95" 
                     class="pointer-events-auto relative transform flex flex-col w-full max-w-md bg-surface text-left shadow-2xl transition-all sm:my-8 border-2 border-primary max-h-[85vh] sm:max-h-[80vh]">
                     
                    <div class="px-6 py-6 border-b-2 border-primary flex items-center justify-between shrink-0">
                        <h2 class="text-2xl font-black uppercase tracking-tight italic">Filter & Sort</h2>
                        <button type="button" @click="filtersOpen = false" class="text-primary hover:text-accent transition-colors focus:outline-none">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    
                    <div class="relative flex-1 overflow-y-auto px-6 py-8">
                        <form action="{{ route('products.index') }}" method="GET" id="filterForm">
                            
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-xs font-bold uppercase tracking-widest text-text-secondary">Clear Options</h3>
                                @if(request()->except('page'))
                                    <a href="{{ route('products.index') }}" class="text-xs font-bold uppercase text-danger underline hover:text-black">Clear All</a>
                                @endif
                            </div>

                            <!-- Sorting -->
                            <div class="mb-8 pb-8 border-b border-border">
                                <h3 class="font-bold uppercase tracking-wider mb-4 text-sm">Sort By</h3>
                                <select name="sort" onchange="document.getElementById('filterForm').submit()" class="w-full appearance-none bg-transparent border-2 border-primary rounded-none py-3 pl-4 pr-10 focus:outline-none focus:ring-1 focus:ring-primary cursor-pointer uppercase text-sm font-bold">
                                    <option value="newest" {{ request('sort') == 'newest' || !request('sort') ? 'selected' : '' }}>Newest</option>
                                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                                </select>
                            </div>

                            <!-- Categories -->
                            <div class="mb-8 pb-8 border-b border-border">
                                <h3 class="font-bold uppercase tracking-wider mb-4 text-sm">Categories</h3>
                                <div class="space-y-4">
                                    @foreach($categories as $cat)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="category" value="{{ $cat->slug }}" onchange="document.getElementById('filterForm').submit()" 
                                                {{ request('category') == $cat->slug ? 'checked' : '' }}
                                                class="w-5 h-5 text-primary border-2 border-primary focus:ring-primary rounded-none cursor-pointer">
                                            <span class="text-sm font-semibold uppercase text-text-secondary group-hover:text-primary transition-colors">{{ $cat->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Gender -->
                            <div class="mb-8">
                                <h3 class="font-bold uppercase tracking-wider mb-4 text-sm">Gender</h3>
                                <div class="space-y-4">
                                    @foreach(['men' => 'Men', 'women' => 'Women', 'kids' => 'Kids', 'unisex' => 'Unisex'] as $val => $label)
                                        <label class="flex items-center gap-3 cursor-pointer group">
                                            <input type="radio" name="gender" value="{{ $val }}" onchange="document.getElementById('filterForm').submit()" 
                                                {{ request('gender') == $val ? 'checked' : '' }}
                                                class="w-5 h-5 text-primary border-2 border-primary focus:ring-primary rounded-none cursor-pointer">
                                            <span class="text-sm font-semibold uppercase text-text-secondary group-hover:text-primary transition-colors">{{ $label }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                            <button type="submit" class="hidden">Apply</button>
                        </form>
                    </div>
                    
                    <div class="px-6 py-6 border-t-2 border-primary bg-surface shrink-0 mt-auto">
                        <button type="button" @click="filtersOpen = false" class="w-full bg-primary text-white font-bold uppercase tracking-widest py-4 hover:bg-primary-light transition-colors">
                            Show Results
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
