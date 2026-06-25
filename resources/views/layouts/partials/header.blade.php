<header class="sticky top-0 z-40 w-full bg-primary text-white shadow-md transition-all duration-300" x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 20)">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="flex justify-between items-center h-24">
            
            <div class="flex items-center lg:hidden">
                <button type="button" @click="mobileMenuOpen = true" class="text-white hover:text-accent transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <!-- Logo -->
            <div class="flex-shrink-0 flex items-center justify-center lg:justify-start flex-1 lg:flex-none">
                <a href="{{ route('home') }}" class="block">
                    @php $headerLogo = \App\Models\Setting::get('store_logo'); @endphp
                    @if($headerLogo)
                        <img src="{{ $headerLogo }}" alt="{{ config('app.name') }}" class="h-8 md:h-10 object-contain logo-contrast">
                    @else
                        <span class="font-black text-2xl tracking-tighter uppercase text-white group">
                            ASQI<span class="text-accent group-hover:text-white transition-colors">.</span>
                        </span>
                    @endif
                </a>
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:flex h-full" x-data="{ activeMenu: null }">
                <ul class="flex space-x-8 h-full items-center">
                    <li class="h-full flex items-center">
                        <a href="{{ route('products.index') }}" class="text-white hover:text-gray-300 font-bold uppercase tracking-widest text-sm transition-colors h-full flex items-center border-b-4 border-transparent hover:border-white">
                            Collections
                        </a>
                    </li>
                    @if(isset($navCategories))
                        @foreach($navCategories as $parent)
                            <li class="h-full flex items-center">
                                <a href="{{ route('products.index', ['category' => $parent->slug]) }}" class="text-white hover:text-gray-300 font-bold uppercase tracking-widest text-sm transition-colors h-full flex items-center border-b-4 border-transparent hover:border-white">
                                    {{ $parent->name }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </nav>

            <!-- Icons (Right) -->
            <div class="flex items-center space-x-5 sm:space-x-8">
                <!-- Search -->
                <button @click="$dispatch('open-search')" class="text-white hover:text-accent transition-colors cursor-pointer flex items-center justify-center" title="Search">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </button>

                <!-- Language Switcher -->
                <div class="hidden lg:flex items-center justify-center relative h-full" x-data="{ open: false }">
                    <button @click="open = !open" @click.away="open = false" class="text-sm font-bold uppercase hover:text-accent flex items-center justify-center gap-1 cursor-pointer h-full px-2" title="Language">
                        {{ app()->getLocale() }}
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" x-transition x-cloak class="absolute right-0 top-full mt-0 w-32 bg-white text-primary border-2 border-primary rounded-none shadow-none z-50 py-2 font-bold">
                        <a href="?lang=en" class="block px-4 py-2 hover:bg-surface-secondary hover:text-accent uppercase tracking-widest text-sm transition-colors">English</a>
                        <a href="?lang=id" class="block px-4 py-2 hover:bg-surface-secondary hover:text-accent uppercase tracking-widest text-sm transition-colors">Indonesian</a>
                    </div>
                </div>

                

                <!-- User Account -->
                @auth
                    <div class="relative hidden lg:flex items-center justify-center h-full" x-data="{ open: false }">
                        <button @click="open = !open" @click.away="open = false" class="text-white hover:text-accent transition-colors cursor-pointer flex items-center justify-center h-full px-2" title="Account">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </button>
                        <div x-show="open" x-transition x-cloak class="absolute right-0 top-full mt-0 w-56 bg-white text-primary border-2 border-primary rounded-none shadow-none z-50 py-2 font-bold">
                            <div class="px-4 py-3 border-b-2 border-border mb-2">
                                <p class="truncate uppercase tracking-wider text-xs text-text-muted">Signed in as</p>
                                <p class="truncate uppercase tracking-widest">{{ Auth::user()->name }}</p>
                            </div>
                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 hover:bg-surface-secondary hover:text-accent uppercase tracking-widest text-sm transition-colors">Admin Dashboard</a>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-surface-secondary hover:text-accent uppercase tracking-widest text-sm transition-colors">Profile Settings</a>
                            <form method="POST" action="{{ route('logout') }}" class="mt-2 border-t-2 border-border pt-2">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-danger hover:bg-danger hover:text-white uppercase tracking-widest text-sm transition-colors">Log Out</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="#" @click.prevent="authModalOpen = true; authTab = 'login'" class="hidden lg:flex items-center justify-center text-white hover:text-accent transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    </a>
                @endauth

                <!-- Orders (Desktop) -->
                @auth
                    @php $activeOrdersCount = \App\Models\Order::where('user_id', Auth::id())->whereIn('status', ['pending', 'processing', 'shipped'])->count(); @endphp
                    <a href="{{ route('profile.orders') }}" class="hidden lg:flex items-center justify-center text-white hover:text-accent transition-colors relative" title="Orders">
                        <svg class="w-5 h-5 mt-[2px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        <span id="orders-icon-badge" class="absolute -top-2 -right-2 bg-accent text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm transition-opacity" style="display: {{ $activeOrdersCount > 0 ? 'flex' : 'none' }}">{{ $activeOrdersCount }}</span>
                    </a>
                @endauth

                <!-- Wishlist (Desktop) -->
                @auth
                    @php $wishlistCount = \App\Models\Wishlist::where('user_id', Auth::id())->count(); @endphp
                    <a href="{{ route('wishlist.index') }}" class="hidden lg:flex items-center justify-center text-white hover:text-accent transition-colors group relative" title="Wishlist">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                        <span id="wishlist-icon-badge" class="absolute -top-2 -right-2 bg-accent text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm transition-opacity" style="display: {{ $wishlistCount > 0 ? 'flex' : 'none' }}">{{ $wishlistCount }}</span>
                    </a>
                @endauth

                <!-- Cart -->
                <a href="{{ route('cart.index') }}" class="hidden lg:flex items-center justify-center relative text-white hover:text-accent transition-colors group" title="Cart">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    <span id="cart-icon-badge" x-cloak x-show="$store.cart.count > 0" x-text="$store.cart.count" class="absolute -top-2 -right-2 bg-accent text-white text-[10px] font-bold w-4 h-4 rounded-full flex items-center justify-center shadow-sm"></span>
                </a>
            </div>
        </div>
    </div>
</header>
