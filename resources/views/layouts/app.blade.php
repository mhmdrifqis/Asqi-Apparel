<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', config('app.name', 'Asqi Apparel'))</title>
    <meta name="description" content="@yield('meta_description', 'Premium sportswear and activewear designed for peak performance and unparalleled aesthetics. Elevate your game with Asqi Apparel.')">
    <meta name="keywords" content="sportswear, activewear, gym clothes, performance apparel, fitness, running">
    
    <!-- Favicon -->
    @php $favicon = \App\Models\Setting::get('store_logo'); @endphp
    @if($favicon)
        <link rel="icon" type="image/png" href="{{ $favicon }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    @endif
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('title', config('app.name', 'Asqi Apparel'))">
    <meta property="og:description" content="@yield('meta_description', 'Premium sportswear and activewear designed for peak performance and unparalleled aesthetics.')">
    <meta property="og:image" content="@yield('meta_image', asset('images/og-image.jpg'))">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('title', config('app.name', 'Asqi Apparel'))">
    <meta property="twitter:description" content="@yield('meta_description', 'Premium sportswear and activewear designed for peak performance and unparalleled aesthetics.')">
    <meta property="twitter:image" content="@yield('meta_image', asset('images/og-image.jpg'))">

    <style>
        /* Force uploaded store logo to white in dark headers/footers */
        .logo-contrast {
            filter: brightness(0) invert(1);
        }
        
        /* Prevent Alpine.js FOUC (Flash of Unstyled Content) */
        [x-cloak] { display: none !important; }
    </style>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-surface text-text-primary transition-colors duration-300" x-data="{ mobileMenuOpen: false, authModalOpen: {{ $errors->any() ? 'true' : 'false' }}, authTab: '{{ old('name') || request()->is('register') ? 'register' : 'login' }}' }">
    
    <!-- Header -->
    @include('layouts.partials.header')
    
    <!-- Mobile Menu Overlay -->
    @include('layouts.partials.mobile-menu')
    
    <!-- Auth Modal -->
    @include('layouts.partials.auth-modal')
    
    <!-- Main Content -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('layouts.partials.footer')

    <!-- Live Search Overlay -->
    <div x-data="{ searchOpen: false, query: '', results: [], loading: false }"
         @open-search.window="searchOpen = true; $nextTick(() => $refs.searchInput.focus())"
         @keydown.escape.window="searchOpen = false"
         class="relative z-50"
         x-show="searchOpen"
         x-cloak>
         
         <div x-show="searchOpen" class="fixed inset-0 bg-black/80 backdrop-blur-md transition-opacity" @click="searchOpen = false"></div>
         
         <div class="fixed inset-0 z-10 overflow-y-auto pt-20 sm:pt-32 px-4">
            <div x-show="searchOpen" class="mx-auto max-w-3xl transform divide-y divide-border overflow-hidden rounded-2xl bg-surface shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
                <div class="relative flex items-center">
                    <svg class="pointer-events-none absolute left-4 h-6 w-6 text-text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                    <input type="text" x-ref="searchInput" x-model="query" @input.debounce.500ms="
                        let term = query.trim();
                        if(term.length >= 2) {
                            loading = true;
                            fetch('/api/search?q=' + encodeURIComponent(term))
                                .then(res => res.json())
                                .then(data => { results = data; loading = false; })
                                .catch(err => { results = []; loading = false; })
                        } else {
                            results = [];
                            loading = false;
                        }
                    " class="h-14 w-full border-0 bg-transparent pl-12 pr-14 text-text-primary placeholder:text-text-muted focus:ring-0 sm:text-lg" placeholder="Search products, categories...">
                    
                    <button type="button" @click="searchOpen = false; query = ''; results = []" class="absolute right-4 text-text-muted hover:text-danger transition-colors focus:outline-none bg-surface-secondary p-1 rounded-md" aria-label="Close Search">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

                <div x-show="loading" class="p-8 text-center text-accent flex justify-center">
                    <svg class="animate-spin h-8 w-8 text-accent" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                </div>

                <ul x-show="!loading && results.length > 0" class="max-h-96 overflow-y-auto p-2 text-sm text-text-secondary" id="search-options">
                    <template x-for="item in results" :key="item.id">
                        <li class="group cursor-default select-none rounded-xl px-4 py-3 hover:bg-surface-secondary">
                            <a :href="'/shop/' + item.slug" class="flex items-center gap-4">
                                <div class="h-16 w-12 flex-shrink-0 rounded bg-surface-secondary overflow-hidden">
                                    <img :src="item.image" class="h-full w-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <span class="block text-xs font-bold text-text-muted uppercase tracking-wider mb-1" x-text="item.category"></span>
                                    <span class="block font-bold text-text-primary group-hover:text-accent" x-text="item.name"></span>
                                </div>
                                <span class="font-bold text-primary" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(item.price)"></span>
                            </a>
                        </li>
                    </template>
                </ul>

                <div x-show="searchOpen && !loading && query.length >= 2 && results.length === 0" class="p-10 text-center sm:px-14">
                    <svg class="mx-auto h-12 w-12 text-text-muted mb-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <p class="font-semibold text-text-primary">No results found</p>
                    <p class="text-sm text-text-secondary mt-1">We couldn't find anything matching "<span x-text="query"></span>". Try another term.</p>
                </div>
            </div>
         </div>
    </div>
    
    <!-- Toast Notifications -->
    <div x-data x-cloak
         class="fixed top-4 right-4 z-[100] flex flex-col gap-3 max-w-sm w-full">
        <template x-for="toast in $store.toast.messages" :key="toast.id">
            <div x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-x-8"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-x-0"
                 x-transition:leave-end="opacity-0 translate-x-8"
                 class="toast-notification"
                 :class="{
                     'toast-success': toast.type === 'success',
                     'toast-error': toast.type === 'error',
                     'toast-warning': toast.type === 'warning',
                     'toast-info': toast.type === 'info'
                 }">
                <div class="flex items-center gap-3">
                    <template x-if="toast.type === 'success'">
                        <svg class="w-5 h-5 flex-shrink-0 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                    <template x-if="toast.type === 'error'">
                        <svg class="w-5 h-5 flex-shrink-0 text-danger" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </template>
                    <span x-text="toast.message" class="text-sm font-medium"></span>
                </div>
                <button @click="$store.toast.dismiss(toast.id)" class="ml-auto text-current opacity-60 hover:opacity-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </template>
    </div>
    
    <!-- Cookie Consent -->
    <div x-data="{ shown: !localStorage.getItem('asqi_cookie_consent') }" x-show="shown" x-cloak
         x-transition:enter="transition ease-out duration-500"
         x-transition:enter-start="translate-y-full"
         x-transition:enter-end="translate-y-0"
         class="fixed bottom-0 left-0 right-0 z-50 bg-primary text-white p-4 md:p-6 shadow-modal">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-center md:text-left">
                We use cookies to enhance your shopping experience. By continuing to browse, you agree to our 
                <a href="#" class="underline hover:text-accent-light">Privacy Policy</a>.
            </p>
            <div class="flex gap-3">
                <button @click="localStorage.setItem('asqi_cookie_consent', 'true'); shown = false"
                        class="px-6 py-2 bg-accent hover:bg-accent-hover text-white text-sm font-semibold rounded-full transition-colors">
                    Accept All
                </button>
                <button @click="shown = false"
                        class="px-6 py-2 border border-white/30 hover:bg-white/10 text-white text-sm font-medium rounded-full transition-colors">
                    Decline
                </button>
            </div>
        </div>
    </div>
    
    @php
        $dbCartCount = 0;
        if (auth()->check() && auth()->user()->cart) {
            $dbCartCount = auth()->user()->cart->items()->sum('quantity');
        } elseif (session()->has('cart_session_id')) {
            $cart = \App\Models\Cart::where('session_id', session('cart_session_id'))->first();
            if ($cart) $dbCartCount = $cart->items()->sum('quantity');
        }
    @endphp
    
    @stack('scripts')
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        window.confirmDelete = function(form, message = 'Are you sure you want to delete this?') {
            Swal.fire({
                title: 'Are you sure?',
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#e94560',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, proceed!',
                background: '#ffffff',
                color: '#111827'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
    
    <script>
        document.addEventListener('alpine:init', () => {
            // Sync cart count from database to Alpine store on page load
            const serverCartCount = {{ $dbCartCount }};
            Alpine.store('cart').setCount(serverCartCount);

            // Global Wishlist Toggle Component
            Alpine.data('wishlistToggle', (productId, isLoggedIn, initialStatus) => ({
                inWishlist: initialStatus,
                
                async toggle() {
                    if (!isLoggedIn) {
                        window.location.href = '/login';
                        return;
                    }
                    
                    try {
                        const response = await fetch('/wishlist/toggle', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({ product_id: productId })
                        });
                        
                        const data = await response.json();
                        if (response.ok) {
                            this.inWishlist = data.status === 'added';
                            this.$store.toast.success(data.message);
                        } else {
                            this.$store.toast.error(data.message || 'Failed to update wishlist');
                        }
                    } catch (error) {
                        this.$store.toast.error('Network error occurred');
                    }
                }
            }));
        });
    </script>
</body>
</html>
