<x-guest-layout>
    <div class="flex min-h-screen bg-surface">
        
        <!-- Left Side: Image / Brand (Hidden on mobile) -->
        <div class="hidden lg:flex lg:w-1/2 relative bg-primary items-center justify-center overflow-hidden">
            <!-- Background Image with Overlay -->
            <div class="absolute inset-0 z-0">
                <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                     alt="Athletic Performance" 
                     class="w-full h-full object-cover opacity-60">
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/40 to-transparent"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 p-12 text-center text-white max-w-xl mx-auto">
                <a href="{{ route('home') }}" class="inline-block mb-8">
                    @php $headerLogo = \App\Models\Setting::get('store_logo'); @endphp
                    @if($headerLogo)
                        <img src="{{ $headerLogo }}" alt="{{ config('app.name') }}" class="h-12 object-contain mx-auto filter brightness-0">
                    @else
                        <span class="font-black text-4xl tracking-tighter uppercase">
                            ASQI<span class="text-accent">.</span>
                        </span>
                    @endif
                </a>
                
                <h1 class="text-4xl lg:text-5xl font-black uppercase tracking-tight mb-6 leading-tight">
                    Push Your <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-accent to-accent-light">Limits</span>
                </h1>
                
                <p class="text-lg text-gray-300 font-medium">
                    Premium sportswear engineered for peak performance and unparalleled aesthetics. Join the elite.
                </p>
            </div>
        </div>

        <!-- Right Side: Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12 lg:p-24 relative overflow-hidden">
             
            <!-- Close Button to Home -->
            <a href="{{ route('home') }}" class="absolute top-6 right-6 lg:top-8 lg:right-8 z-50 p-2 text-text-muted hover:text-primary hover:bg-surface-secondary rounded-full transition-colors" aria-label="Back to home" title="Back to home">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </a>
             
            <!-- Background Decoration (Mobile only) -->
            <div class="absolute inset-0 z-0 lg:hidden opacity-10">
                <img src="https://images.unsplash.com/photo-1517836357463-d25dfeac3438?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80" 
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-surface"></div>
            </div>
             
            <div class="w-full max-w-md relative z-10">
                 
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-10">
                    <a href="{{ route('home') }}" class="inline-block">
                        @php $headerLogo = \App\Models\Setting::get('store_logo'); @endphp
                        @if($headerLogo)
                            <img src="{{ $headerLogo }}" alt="{{ config('app.name') }}" class="h-10 object-contain mx-auto filter brightness-0">
                        @else
                            <span class="font-black text-3xl tracking-tighter uppercase text-primary">
                                ASQI<span class="text-accent">.</span>
                            </span>
                        @endif
                    </a>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-black uppercase tracking-tight text-primary mb-2">Welcome Back</h2>
                    <p class="text-text-secondary font-medium">Enter your credentials to access your account.</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 bg-green-50 text-green-600 p-4 rounded-lg font-bold text-sm border border-green-200" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6" id="loginForm">
                    @csrf
                    <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                    <!-- Email Address (Floating Label) -->
                    <div class="relative">
                        <input id="email" type="email" name="email" value="{{ old('email') }}"
                               required autofocus autocomplete="username" placeholder=" "
                               class="peer block w-full px-4 pb-2 pt-8 text-text-primary bg-surface-secondary border-b-2 border-border appearance-none focus:outline-none focus:ring-0 focus:border-accent transition-colors rounded-t-lg">
                        <label for="email" 
                               class="absolute text-text-muted duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-bold uppercase tracking-wider text-xs">
                            Email Address
                        </label>
                        @error('email')
                            <p class="text-danger text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password (Floating Label) -->
                    <div class="relative" x-data="{ showPassword: false }">
                        <input id="password" :type="showPassword ? 'text' : 'password'" name="password"
                               required autocomplete="current-password" placeholder=" "
                               class="peer block w-full px-4 pb-2 pt-8 text-text-primary bg-surface-secondary border-b-2 border-border appearance-none focus:outline-none focus:ring-0 focus:border-accent transition-colors rounded-t-lg">
                        
                        <label for="password" 
                               class="absolute text-text-muted duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-accent peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 font-bold uppercase tracking-wider text-xs">
                            Password
                        </label>
                        
                        <div class="absolute right-4 top-0 bottom-0 flex items-center gap-3 z-20">
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-accent hover:text-accent-hover transition-colors">Forgot?</a>
                            @endif
                            <!-- Toggle Password Visibility -->
                            <button type="button" @click="showPassword = !showPassword" class="text-text-muted hover:text-primary transition-colors focus:outline-none" tabindex="-1">
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                            </button>
                        </div>
                        
                        @error('password')
                            <p class="text-danger text-xs mt-2 font-bold">{{ $message }}</p>
                        @enderror
                    </div>

                    @error('g-recaptcha-response')
                        <div class="bg-red-50 text-danger p-3 rounded-lg text-sm font-bold border border-red-200">
                            {{ $message }}
                        </div>
                    @enderror

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-5 h-5 text-accent border-border rounded focus:ring-accent bg-surface-secondary cursor-pointer">
                        <label for="remember_me" class="ml-3 text-sm text-text-secondary font-medium cursor-pointer select-none">Remember me for 30 days</label>
                    </div>

                    <button type="submit" class="w-full btn btn-primary py-4 text-lg mt-6 shadow-xl shadow-accent/20 hover:shadow-accent/40 hover:-translate-y-1 transition-all duration-300">
                        Login
                    </button>
                    
                    <div class="relative flex items-center justify-center mt-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-border"></div>
                        </div>
                        <div class="relative px-4 bg-surface text-sm text-text-muted font-bold tracking-wider uppercase">
                            Or continue with
                        </div>
                    </div>
                    
                    <a href="{{ route('auth.google') }}" class="w-full mt-6 flex items-center justify-center gap-3 bg-white border border-gray-300 text-gray-700 py-3.5 px-4 rounded-none font-bold text-sm tracking-wider uppercase hover:bg-gray-50 transition-colors shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Sign in with Google
                    </a>
                </form>

                <div class="mt-10 pt-8 border-t border-border text-center">
                    <p class="text-text-secondary font-medium">
                        New to Asqi Apparel? 
                        <a href="{{ route('register') }}" class="text-primary font-black uppercase tracking-wider hover:text-accent transition-colors ml-1">Create Account</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

@push('scripts')
@if(!empty(config('services.recaptcha.site_key')) && !empty(config('services.recaptcha.secret_key')))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        grecaptcha.ready(function() {
            grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'login'}).then(function(token) {
                document.getElementById('g-recaptcha-response').value = token;
                form.submit();
            }).catch(function(error) {
                console.error("reCAPTCHA Error:", error);
                // Fallback submit if reCAPTCHA fails execution
                form.submit();
            });
        });
    });
</script>
@endif
@endpush
