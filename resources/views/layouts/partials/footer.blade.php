<footer class="bg-primary text-white pt-16 pb-8 border-t-4 border-accent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">
            
            <!-- Brand & Newsletter -->
            <div class="col-span-1 md:col-span-2 lg:col-span-1">
                <a href="{{ route('home') }}" class="block mb-6">
                    @php $footerLogo = \App\Models\Setting::get('store_logo'); @endphp
                    @if($footerLogo)
                        <img src="{{ $footerLogo }}" alt="{{ config('app.name') }}" class="h-8 md:h-10 object-contain logo-contrast opacity-80 hover:opacity-100 transition-all">
                    @else
                        <span class="font-black text-3xl tracking-tighter uppercase text-white">ASQI<span class="text-accent">.</span></span>
                    @endif
                </a>
                <p class="text-gray-400 text-sm mb-6 leading-relaxed">
                    Premium sportswear engineered for performance. Push your limits with our cutting-edge athletic apparel.
                </p>
                <form class="flex gap-2">
                    <input type="email" placeholder="Enter your email" class="w-full bg-surface-dark border border-border-dark text-white text-sm rounded-md px-4 py-2 focus:ring-accent focus:border-accent">
                    <button type="submit" class="btn btn-accent whitespace-nowrap">Subscribe</button>
                </form>
            </div>

            <!-- Categories -->
            <div>
                <h3 class="text-sm font-bold uppercase tracking-widest mb-6">Shop</h3>
                <ul class="space-y-4 text-sm text-gray-400">
                    @if(isset($navCategories))
                        @foreach($navCategories as $cat)
                            <li><a href="{{ route('products.index', ['category' => $cat->slug]) }}" class="hover:text-white transition-colors">{{ $cat->name }}</a></li>
                        @endforeach
                    @endif
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">All Collections</a></li>
            
                </ul>
            </div>

            <!-- Support -->
            <div>
                <h3 class="text-sm font-bold tracking-widest uppercase mb-6 text-white">Support</h3>
                <ul class="space-y-4 text-sm text-gray-400 font-bold uppercase tracking-wider">
                    <li><a href="{{ route('shipping-returns') }}" class="hover:text-accent transition-colors">Shipping & Returns</a></li>
                    <li><a href="{{ route('size-guide') }}" class="hover:text-accent transition-colors">Size Guide</a></li>
                    <li><a href="{{ route('faq') }}" class="hover:text-accent transition-colors">FAQ</a></li>
                    <li><a href="{{ route('terms') }}" class="hover:text-accent transition-colors">Terms & Conditions</a></li>
                    <li><a href="{{ route('privacy') }}" class="hover:text-accent transition-colors">Privacy Policy</a></li>
                </ul>
            </div>

            <!-- Company -->
            <div>
                <h3 class="text-sm font-bold tracking-widest uppercase mb-6 text-white">Company</h3>
                <ul class="space-y-4 text-sm text-gray-400 font-bold uppercase tracking-wider">
                    <li><a href="{{ route('about') }}" class="hover:text-accent transition-colors">About ASQI</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-accent transition-colors">Contact Us</a></li>
                    <li><a href="{{ route('careers') }}" class="hover:text-accent transition-colors">Careers</a></li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center gap-6">
            <p class="text-gray-500 text-sm">
                &copy; {{ date('Y') }} Asqi Apparel. All rights reserved.
            </p>
            
            <!-- Payment Methods (Icons) -->
            <div class="flex gap-3 items-center opacity-70">
                <span class="text-xs font-medium uppercase tracking-wider text-gray-400 mr-2">Secure Payments:</span>
                <div class="w-10 h-6 bg-white rounded border border-gray-200 flex items-center justify-center text-primary text-[10px] font-bold">VISA</div>
                <div class="w-10 h-6 bg-white rounded border border-gray-200 flex items-center justify-center text-primary text-[10px] font-bold">MC</div>
                <div class="w-10 h-6 bg-white rounded border border-gray-200 flex items-center justify-center text-primary text-[10px] font-bold">BCA</div>
                <div class="w-10 h-6 bg-[#00A5CF] rounded flex items-center justify-center text-white text-[9px] font-bold">QRIS</div>
            </div>
        </div>
    </div>
</footer>
