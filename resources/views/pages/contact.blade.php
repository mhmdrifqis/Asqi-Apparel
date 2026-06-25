@extends('layouts.app')

@section('title', 'Contact Us | Asqi Apparel')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
    
    <div class="text-center max-w-3xl mx-auto mb-16">
        <h1 class="text-4xl md:text-5xl font-black uppercase tracking-tighter mb-4">Get in Touch</h1>
        <p class="text-text-secondary text-lg">Have a question about an order, our products, or anything else? Drop us a message.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-16 max-w-5xl mx-auto">
        
        <!-- Contact Info -->
        <div>
            <h2 class="text-2xl font-black uppercase tracking-tight mb-8">Contact Information</h2>
            
            <div class="space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-surface border-2 border-primary rounded-none flex items-center justify-center flex-shrink-0 text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold uppercase tracking-wider mb-1">Headquarters</h3>
                        <p class="text-text-secondary">{{ \App\Models\Setting::get('store_address') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-surface border-2 border-primary rounded-none flex items-center justify-center flex-shrink-0 text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold uppercase tracking-wider mb-1">Email Us</h3>
                        <p class="text-text-secondary">{{ \App\Models\Setting::get('store_email') }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-surface border-2 border-primary rounded-none flex items-center justify-center flex-shrink-0 text-accent">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <h3 class="font-bold uppercase tracking-wider mb-1">Call Us</h3>
                        <p class="text-text-secondary">{{ \App\Models\Setting::get('store_phone') }}</p>
                        <p class="text-xs text-text-muted mt-1">Mon-Fri, 9am - 5pm WIB</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-surface border-2 border-primary rounded-none p-8 h-full">
            <h2 class="text-2xl font-black uppercase tracking-tight mb-6">Send a Message</h2>
            
            @if(session('success'))
                <div class="bg-success/10 border border-success text-success px-4 py-3 rounded-none mb-6 text-sm font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-widest mb-1">Your Name</label>
                    <input type="text" id="name" name="name" required class="w-full bg-surface-secondary border-2 border-primary text-text-primary rounded-none px-4 py-3 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-widest mb-1">Email Address</label>
                    <input type="email" id="email" name="email" required class="w-full bg-surface-secondary border-2 border-primary text-text-primary rounded-none px-4 py-3 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-widest mb-1">Subject</label>
                    <input type="text" id="subject" name="subject" required class="w-full bg-surface-secondary border-2 border-primary text-text-primary rounded-none px-4 py-3 focus:ring-primary focus:border-primary">
                </div>
                <div>
                    <label class="block text-xs font-bold text-text-secondary uppercase tracking-widest mb-1">Message</label>
                    <textarea id="message" name="message" rows="5" required class="w-full bg-surface-secondary border-2 border-primary text-text-primary rounded-none px-4 py-3 focus:ring-primary focus:border-primary resize-none"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary w-full py-4 text-lg">Send Message</button>
            </form>
        </div>

    </div>
</div>
@endsection
