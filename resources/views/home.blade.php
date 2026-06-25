@extends('layouts.app')

@section('title', 'Asqi Apparel | Premium Sportswear')

@section('content')

<!-- Hero Banners Slider -->
<div class="relative w-full h-[80vh] min-h-[600px] bg-black overflow-hidden" x-data="{ currentSlide: 0, slides: {{ count($banners) }} }" x-init="setInterval(() => { currentSlide = (currentSlide + 1) % slides }, 6000)">
    @foreach($banners as $index => $banner)
        <div x-show="currentSlide === {{ $index }}"
             x-transition:enter="transition-opacity duration-1000"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity duration-1000 absolute inset-0"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 w-full h-full">
            
            <div class="absolute inset-0 bg-black/40 z-10"></div>
            <img src="{{ $banner->image_path }}" alt="{{ $banner->title }}" class="absolute inset-0 w-full h-full object-cover object-center scale-105 transform animate-pulse-slow">
            
            <div class="relative z-20 h-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col justify-center items-start">
                <p class="text-white text-sm md:text-base font-bold tracking-widest uppercase mb-4">{{ $banner->subtitle }}</p>
                <h1 class="text-white text-5xl md:text-8xl font-black uppercase tracking-tighter italic max-w-4xl leading-none mb-8">
                    {{ $banner->title }}
                </h1>
                <a href="{{ $banner->link_url }}" class="bg-white text-black hover:bg-gray-200 px-10 py-4 text-sm font-bold uppercase tracking-widest transition-colors flex items-center gap-4 group">
                    {{ $banner->cta_text ?? 'Shop Now' }}
                    <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    @endforeach
    
    <!-- Slider Indicators -->
    @if(count($banners) > 1)
        <div class="absolute bottom-8 left-0 right-0 z-30 flex justify-center gap-2">
            @foreach($banners as $index => $banner)
                <button @click="currentSlide = {{ $index }}" class="w-12 h-1 transition-colors" :class="currentSlide === {{ $index }} ? 'bg-white' : 'bg-white/30 hover:bg-white/50'"></button>
            @endforeach
        </div>
    @endif
</div>

<!-- Who Are You Shopping For? -->
<section class="py-16 bg-surface">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-4xl font-black uppercase tracking-tight italic mb-10 text-center">Who Are You Shopping For?</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Men -->
            <a href="{{ route('products.index', ['gender' => 'men']) }}" class="group relative aspect-square overflow-hidden bg-gray-100 block">
                <img src="https://placehold.co/600x600/111111/ffffff?text=MEN" alt="Shop Men" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 grayscale group-hover:grayscale-0">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                <div class="absolute bottom-6 left-6 z-20">
                    <span class="bg-white text-black text-sm font-bold uppercase tracking-widest px-6 py-3 shadow-md">Men</span>
                </div>
            </a>
            <!-- Women -->
            <a href="{{ route('products.index', ['gender' => 'women']) }}" class="group relative aspect-square overflow-hidden bg-gray-100 block">
                <img src="https://placehold.co/600x600/333333/ffffff?text=WOMEN" alt="Shop Women" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 grayscale group-hover:grayscale-0">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                <div class="absolute bottom-6 left-6 z-20">
                    <span class="bg-white text-black text-sm font-bold uppercase tracking-widest px-6 py-3 shadow-md">Women</span>
                </div>
            </a>
            <!-- Kids -->
            <a href="{{ route('products.index', ['gender' => 'kids']) }}" class="group relative aspect-square overflow-hidden bg-gray-100 block">
                <img src="https://placehold.co/600x600/555555/ffffff?text=KIDS" alt="Shop Kids" class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 group-hover:scale-105 grayscale group-hover:grayscale-0">
                <div class="absolute inset-0 bg-black/20 group-hover:bg-transparent transition-colors"></div>
                <div class="absolute bottom-6 left-6 z-20">
                    <span class="bg-white text-black text-sm font-bold uppercase tracking-widest px-6 py-3 shadow-md">Kids</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Trending Now -->
<section class="py-20 bg-surface border-t-2 border-black">
    <div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-10">
            <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tight italic">Trending Now</h2>
            <a href="{{ route('products.index', ['sort' => 'popular']) }}" class="text-sm font-bold uppercase tracking-widest underline hover:text-gray-600 transition-colors hidden sm:block">View All</a>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
            @foreach($featuredProducts as $product)
                <x-ui.product-card :product="$product" />
            @endforeach
        </div>
        
        <div class="mt-10 text-center sm:hidden">
            <a href="{{ route('products.index') }}" class="inline-block border-2 border-black px-8 py-4 font-bold uppercase tracking-widest w-full text-center">View All</a>
        </div>
    </div>
</section>

<!-- Call to Action Banner -->
@guest
<section class="py-24 relative overflow-hidden bg-black text-white">
    <div class="absolute inset-0 bg-black/70 z-10"></div>
    <div class="absolute inset-0 bg-[url('https://placehold.co/1920x1080/111111/333333')] bg-cover bg-center bg-no-repeat opacity-50"></div>
    
    <div class="relative z-20 max-w-5xl mx-auto px-4 text-center">
        <h2 class="text-5xl md:text-7xl font-black uppercase tracking-tighter italic mb-6">Join The Club</h2>
        <p class="text-lg text-gray-300 mb-10 max-w-2xl mx-auto leading-relaxed font-medium">
            Sign up for free to get access to exclusive drops, members-only sales, and special rewards.
        </p>
        <a href="#" @click.prevent="authModalOpen = true; authTab = 'register'" class="inline-flex items-center gap-4 bg-white text-black hover:bg-gray-200 px-10 py-4 text-sm font-bold uppercase tracking-widest transition-colors group">
            Sign Up For Free
            <svg class="w-5 h-5 transform group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
        </a>
    </div>
</section>
@endguest

@endsection
