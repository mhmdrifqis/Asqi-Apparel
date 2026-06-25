@extends('layouts.app')

@section('title', 'About Us | Asqi Apparel')

@section('content')
<div class="relative w-full h-[50vh] min-h-[400px] bg-primary overflow-hidden flex items-center justify-center">
    <div class="absolute inset-0 bg-black/60 z-10"></div>
    <img src="https://plus.unsplash.com/premium_photo-1747645829954-9ec54a9a245d?auto=format&fit=crop&q=80" alt="Athletes" class="absolute inset-0 w-full h-full object-cover">
    
    <div class="relative z-20 text-center px-4 max-w-4xl mx-auto">
        <h1 class="text-5xl md:text-6xl font-black uppercase tracking-tighter text-white mb-4">Our Story</h1>
        <p class="text-xl text-gray-300">Pushing the boundaries of performance apparel.</p>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 items-center mb-20">
        <div>
            <h2 class="text-3xl font-black uppercase tracking-tight mb-6">Born from Passion</h2>
            <p class="text-text-secondary leading-relaxed mb-4">
                Founded in 2026, ASQI Apparel was born out of a simple necessity: the need for high-performance sportswear that doesn't compromise on aesthetics. We believe that when you look good, you feel good, and when you feel good, you perform better.
            </p>
            <p class="text-text-secondary leading-relaxed">
                Our team consists of former athletes and textile engineers who have spent years researching and developing fabrics that wick sweat, regulate temperature, and move dynamically with your body.
            </p>
        </div>
        <div class="aspect-square bg-surface-secondary border-2 border-primary rounded-none overflow-hidden">
            <img src="https://images.unsplash.com/photo-1761358531213-f3748b4a92a0?auto=format&fit=crop&q=80" alt="Design Process" class="w-full h-full object-cover">
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
        <div class="p-8 bg-surface rounded-none border-2 border-primary">
            <div class="w-16 h-16 mx-auto bg-accent/10 text-accent border-2 border-accent rounded-none flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <h3 class="text-xl font-bold uppercase tracking-wider mb-3">Innovation</h3>
            <p class="text-text-secondary text-sm">We constantly test new materials to ensure peak performance in every condition.</p>
        </div>
        <div class="p-8 bg-surface rounded-none border-2 border-primary">
            <div class="w-16 h-16 mx-auto bg-accent/10 text-accent border-2 border-accent rounded-none flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold uppercase tracking-wider mb-3">Sustainability</h3>
            <p class="text-text-secondary text-sm">Using recycled fabrics and ethical manufacturing processes to protect our playground.</p>
        </div>
        <div class="p-8 bg-surface rounded-none border-2 border-primary">
            <div class="w-16 h-16 mx-auto bg-accent/10 text-accent border-2 border-accent rounded-none flex items-center justify-center mb-6">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold uppercase tracking-wider mb-3">Community</h3>
            <p class="text-text-secondary text-sm">Building a global network of athletes who support and inspire each other.</p>
        </div>
    </div>
</div>
@endsection
