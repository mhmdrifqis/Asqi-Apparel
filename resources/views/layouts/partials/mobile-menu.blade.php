<div x-show="mobileMenuOpen" x-cloak class="relative z-50 lg:hidden" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
    
    <!-- Background overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="ease-in-out duration-500" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in-out duration-500" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-primary/80 backdrop-blur-sm transition-opacity" 
         @click="mobileMenuOpen = false"></div>

    <div class="fixed inset-0 overflow-hidden">
        <div class="absolute inset-0 overflow-hidden">
            <div class="pointer-events-none fixed inset-y-0 left-0 flex max-w-full pr-10">
                
                <!-- Slide-over panel -->
                <div x-show="mobileMenuOpen" 
                     x-transition:enter="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:enter-start="-translate-x-full" 
                     x-transition:enter-end="translate-x-0" 
                     x-transition:leave="transform transition ease-in-out duration-500 sm:duration-700" 
                     x-transition:leave-start="translate-x-0" 
                     x-transition:leave-end="-translate-x-full" 
                     class="pointer-events-auto w-screen max-w-md">
                     
                    <div class="flex h-full flex-col overflow-y-scroll bg-surface text-text-primary shadow-xl">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-6 border-b border-border">
                            @php $mobileLogo = \App\Models\Setting::get('store_logo'); @endphp
                            @if($mobileLogo)
                                <img src="{{ $mobileLogo }}" alt="{{ config('app.name') }}" class="h-8 object-contain filter brightness-0">
                            @else
                                <h2 class="text-2xl font-black tracking-tighter uppercase text-primary">ASQI.</h2>
                            @endif
                            <button @click="mobileMenuOpen = false" type="button" class="rounded-md text-text-secondary hover:text-primary focus:outline-none">
                                <span class="sr-only">Close panel</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="relative flex-1 px-4 py-6 sm:px-6">
                            
                            <!-- Search -->
                            <div class="mb-6">
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    </div>
                                    <input type="search" class="block w-full p-3 pl-10 text-sm text-text-primary border border-border rounded-lg bg-surface focus:ring-primary focus:border-primary" placeholder="Search products...">
                                </div>
                            </div>

                            <!-- Navigation Links -->
                            <nav class="space-y-4 text-lg font-bold uppercase tracking-wider">
                                @if(isset($navCategories))
                                    @foreach($navCategories as $parent)
                                        <div class="border-b border-border pb-2">
                                            <a href="{{ route('products.index', ['category' => $parent->slug]) }}" class="flex items-center justify-between w-full py-2 hover:text-accent transition-colors">
                                                <span>{{ $parent->name }}</span>
                                            </a>
                                        </div>
                                    @endforeach
                                @endif
                                <a href="{{ route('products.index') }}" class="block hover:text-accent">Collections</a>
                            </nav>


                            <hr class="my-6 border-border">

                            <!-- Secondary Links -->
                            <div class="space-y-3 font-medium">
                                @auth
                                    <p class="text-sm text-text-muted uppercase mt-4 mb-2">My Account ({{ Auth::user()->name }})</p>
                                    @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="block py-1 hover:text-accent">Admin Dashboard</a>
                                    @endif
                                    
                                    <a href="{{ route('profile.orders') }}" class="block py-1 hover:text-accent">
                                        Orders
                                        @php $activeOrdersCount = \App\Models\Order::where('user_id', Auth::id())->whereIn('status', ['pending', 'processing', 'shipped'])->count(); @endphp
                                        @if($activeOrdersCount > 0)
                                            <span class="text-accent font-bold">({{ $activeOrdersCount }})</span>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('wishlist.index') }}" class="block py-1 hover:text-accent">
                                        Wishlist
                                        @php $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count(); @endphp
                                        @if($wishlistCount > 0)
                                            <span class="text-accent font-bold">({{ $wishlistCount }})</span>
                                        @endif
                                    </a>
                                    
                                    <a href="{{ route('cart.index') }}" class="block py-1 hover:text-accent">
                                    Cart <span x-show="$store.cart.count > 0" x-text="'(' + $store.cart.count + ')'" class="text-accent font-bold"></span>
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="block py-1 hover:text-accent">Profile Settings</a>
                                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                                        @csrf
                                        <button type="submit" class="block py-1 text-danger">Log Out</button>
                                    </form>
                                @else
                                    <a href="#" @click.prevent="authModalOpen = true; authTab = 'login'; mobileMenuOpen = false" class="block w-full text-left py-1 hover:text-accent mt-4">Log In</a>
                                    <a href="#" @click.prevent="authModalOpen = true; authTab = 'register'; mobileMenuOpen = false" class="block py-1 hover:text-accent">Create Account</a>
                                @endauth
                            </div>

                            <hr class="my-6 border-border">

                            <!-- Settings (Lang) -->
                            <div class="flex justify-start items-center text-sm font-semibold uppercase">
                                <div class="flex gap-4">
                                    <a href="?lang=en" class="{{ app()->getLocale() == 'en' ? 'text-primary border-b-2 border-primary' : 'text-text-muted' }}">EN</a>
                                    <a href="?lang=id" class="{{ app()->getLocale() == 'id' ? 'text-primary border-b-2 border-primary' : 'text-text-muted' }}">ID</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
