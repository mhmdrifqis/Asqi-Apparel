<div x-show="authModalOpen" x-cloak class="relative z-[100]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    
    <!-- Background overlay -->
    <div x-show="authModalOpen" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0" 
         class="fixed inset-0 bg-black/80 backdrop-blur-sm transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <!-- Modal panel -->
            <div x-show="authModalOpen" 
                 @click.away="authModalOpen = false"
                 x-transition:enter="ease-out duration-300" 
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave="ease-in duration-200" 
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                 class="relative transform overflow-hidden rounded-xl bg-surface text-left shadow-xl transition-all w-full max-w-md">
                
                <!-- Close Button -->
                <div class="absolute right-0 top-0 hidden pr-4 pt-4 sm:block z-10">
                    <button type="button" @click="authModalOpen = false" class="rounded-md bg-surface text-text-muted hover:text-primary focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- Mobile Close Button -->
                <div class="absolute right-0 top-0 block pr-4 pt-4 sm:hidden z-10">
                    <button type="button" @click="authModalOpen = false" class="rounded-md bg-surface text-text-muted hover:text-primary focus:outline-none">
                        <span class="sr-only">Close</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-6 py-8">
                    
                    <!-- Store Logo -->
                    <div class="flex justify-center mb-6">
                        @php $modalLogo = \App\Models\Setting::get('store_logo'); @endphp
                        @if($modalLogo)
                            <img src="{{ $modalLogo }}" alt="{{ config('app.name') }}" class="h-12 object-contain">
                        @else
                            <h2 class="text-3xl font-black uppercase tracking-tighter text-primary">ASQI.</h2>
                        @endif
                    </div>

                    <!-- Tabs -->
                    <div class="flex border-b border-border mb-8">
                        <button @click="authTab = 'login'" class="w-1/2 py-4 text-center font-black uppercase tracking-wider text-sm transition-colors border-b-2 cursor-pointer" :class="authTab === 'login' ? 'border-primary text-primary' : 'border-transparent text-text-muted hover:text-primary'">
                            Log In
                        </button>
                        <button @click="authTab = 'register'" class="w-1/2 py-4 text-center font-black uppercase tracking-wider text-sm transition-colors border-b-2 cursor-pointer" :class="authTab === 'register' ? 'border-primary text-primary' : 'border-transparent text-text-muted hover:text-primary'">
                            Register
                        </button>
                    </div>

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4 bg-green-50 text-green-600 p-3 rounded font-bold text-sm border border-green-200" :status="session('status')" />

                    <!-- Login Form -->
                    <div x-show="authTab === 'login'">
                        <div class="mb-6 text-center">
                            <h2 class="text-2xl font-black uppercase tracking-tight text-primary">Welcome Back</h2>
                        </div>
                        
                        <form method="POST" action="{{ route('login') }}" class="space-y-5" id="loginModalForm">
                            @csrf
                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-login">

                            <div class="space-y-1">
                                <label for="login_email" class="block text-xs font-bold text-text-muted uppercase tracking-wider">Email Address</label>
                                <div class="relative">
                                    <input id="login_email" type="email" name="email" value="{{ old('email') }}"
                                           required autocomplete="username"
                                           class="block w-full px-4 py-3 text-text-primary bg-surface-secondary border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all">
                                </div>
                                @error('email')
                                    @if(old('name') == null && !request()->is('register'))
                                        <p class="text-danger text-xs mt-1 font-bold">{{ $message }}</p>
                                    @endif
                                @enderror
                            </div>

                            <div class="space-y-1" x-data="{ showPassword: false }">
                                <div class="flex justify-between items-center">
                                    <label for="login_password" class="block text-xs font-bold text-text-muted uppercase tracking-wider">Password</label>
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-accent hover:text-accent-hover transition-colors">Forgot?</a>
                                    @endif
                                </div>
                                <div class="relative">
                                    <input id="login_password" :type="showPassword ? 'text' : 'password'" name="password"
                                           required autocomplete="current-password"
                                           class="block w-full px-4 py-3 text-text-primary bg-surface-secondary border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all pr-12">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-0 bottom-0 flex items-center z-20 text-text-muted hover:text-primary transition-colors focus:outline-none" tabindex="-1">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                @error('password')
                                    @if(old('name') == null && !request()->is('register'))
                                        <p class="text-danger text-xs mt-1 font-bold">{{ $message }}</p>
                                    @endif
                                @enderror
                            </div>

                            <button type="submit" class="w-full btn btn-primary py-3 text-sm mt-4 shadow-xl shadow-accent/20 hover:shadow-accent/40 hover:-translate-y-1 transition-all duration-300">
                                Login
                            </button>
                        </form>
                    </div>

                    <!-- Register Form -->
                    <div x-show="authTab === 'register'" x-cloak>
                        <div class="mb-6 text-center">
                            <h2 class="text-2xl font-black uppercase tracking-tight text-primary">Join Us</h2>
                        </div>
                        
                        <form method="POST" action="{{ route('register') }}" class="space-y-5" id="registerModalForm">
                            @csrf
                            <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response-register">

                            <div class="space-y-1">
                                <label for="reg_name" class="block text-xs font-bold text-text-muted uppercase tracking-wider">Full Name</label>
                                <div class="relative">
                                    <input id="reg_name" type="text" name="name" value="{{ old('name') }}"
                                           required autocomplete="name"
                                           class="block w-full px-4 py-3 text-text-primary bg-surface-secondary border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all">
                                </div>
                                @error('name')
                                    <p class="text-danger text-xs mt-1 font-bold">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="space-y-1">
                                <label for="reg_email" class="block text-xs font-bold text-text-muted uppercase tracking-wider">Email Address</label>
                                <div class="relative">
                                    <input id="reg_email" type="email" name="email" value="{{ old('email') }}"
                                           required autocomplete="username"
                                           class="block w-full px-4 py-3 text-text-primary bg-surface-secondary border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all">
                                </div>
                                @error('email')
                                    @if(old('name') !== null || request()->is('register'))
                                        <p class="text-danger text-xs mt-1 font-bold">{{ $message }}</p>
                                    @endif
                                @enderror
                            </div>

                            <div class="space-y-1" x-data="{ showPassword: false }">
                                <label for="reg_password" class="block text-xs font-bold text-text-muted uppercase tracking-wider">Password</label>
                                <div class="relative">
                                    <input id="reg_password" :type="showPassword ? 'text' : 'password'" name="password"
                                           required autocomplete="new-password"
                                           class="block w-full px-4 py-3 text-text-primary bg-surface-secondary border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all pr-12">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-0 bottom-0 flex items-center z-20 text-text-muted hover:text-primary transition-colors focus:outline-none" tabindex="-1">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                                @error('password')
                                    @if(old('name') !== null || request()->is('register'))
                                        <p class="text-danger text-xs mt-1 font-bold">{{ $message }}</p>
                                    @endif
                                @enderror
                            </div>

                            <div class="space-y-1" x-data="{ showPassword: false }">
                                <label for="reg_password_confirmation" class="block text-xs font-bold text-text-muted uppercase tracking-wider">Confirm Password</label>
                                <div class="relative">
                                    <input id="reg_password_confirmation" :type="showPassword ? 'text' : 'password'" name="password_confirmation"
                                           required autocomplete="new-password"
                                           class="block w-full px-4 py-3 text-text-primary bg-surface-secondary border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all pr-12">
                                    <button type="button" @click="showPassword = !showPassword" class="absolute right-4 top-0 bottom-0 flex items-center z-20 text-text-muted hover:text-primary transition-colors focus:outline-none" tabindex="-1">
                                        <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                        <svg x-show="showPassword" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" /></svg>
                                    </button>
                                </div>
                            </div>

                            <button type="submit" class="w-full btn btn-primary py-3 text-sm mt-4 shadow-xl shadow-accent/20 hover:shadow-accent/40 hover:-translate-y-1 transition-all duration-300">
                                Create Account
                            </button>
                        </form>
                    </div>

                    <!-- Common Google Auth -->
                    <div class="relative flex items-center justify-center mt-8 mb-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-border"></div>
                        </div>
                        <div class="relative px-4 bg-surface text-xs text-text-muted font-bold tracking-wider uppercase">
                            Or continue with
                        </div>
                    </div>
                    
                    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 bg-surface-secondary border border-border text-text-primary py-3 px-4 rounded-lg font-bold text-sm tracking-wider uppercase hover:bg-border transition-colors shadow-sm">
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Google
                    </a>

                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
@if(!empty(config('services.recaptcha.site_key')) && !empty(config('services.recaptcha.secret_key')))
<script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const loginForm = document.getElementById('loginModalForm');
        const registerForm = document.getElementById('registerModalForm');
        
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'login'}).then(function(token) {
                        document.getElementById('g-recaptcha-response-login').value = token;
                        form.submit();
                    }).catch(function(error) {
                        form.submit();
                    });
                });
            });
        }
        
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const form = this;
                grecaptcha.ready(function() {
                    grecaptcha.execute('{{ config('services.recaptcha.site_key') }}', {action: 'register'}).then(function(token) {
                        document.getElementById('g-recaptcha-response-register').value = token;
                        form.submit();
                    }).catch(function(error) {
                        form.submit();
                    });
                });
            });
        }
    });
</script>
@endif
@endpush
