@extends('layouts.admin')

@section('title', 'Settings')
@section('header', 'System Settings')

@section('content')
<div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
    
    @if(session('success'))
        <div class="bg-success/10 border-b border-success text-success px-6 py-4">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="p-6 md:p-8 space-y-10" x-data="{ activeTab: 'store' }">
            
            <!-- Tabs -->
            <div class="flex border-b border-border overflow-x-auto">
                <button type="button" @click="activeTab = 'store'" :class="activeTab === 'store' ? 'border-accent text-accent' : 'border-transparent text-text-secondary hover:text-primary hover:border-border'" class="pb-3 px-4 font-bold border-b-2 whitespace-nowrap transition-colors">Store Profile</button>
                <button type="button" @click="activeTab = 'rajaongkir'" :class="activeTab === 'rajaongkir' ? 'border-accent text-accent' : 'border-transparent text-text-secondary hover:text-primary hover:border-border'" class="pb-3 px-4 font-bold border-b-2 whitespace-nowrap transition-colors">RajaOngkir API</button>
                <button type="button" @click="activeTab = 'midtrans'" :class="activeTab === 'midtrans' ? 'border-accent text-accent' : 'border-transparent text-text-secondary hover:text-primary hover:border-border'" class="pb-3 px-4 font-bold border-b-2 whitespace-nowrap transition-colors">Midtrans API</button>
                <button type="button" @click="activeTab = 'google'" :class="activeTab === 'google' ? 'border-accent text-accent' : 'border-transparent text-text-secondary hover:text-primary hover:border-border'" class="pb-3 px-4 font-bold border-b-2 whitespace-nowrap transition-colors">Google OAuth</button>
                <button type="button" @click="activeTab = 'recaptcha'" :class="activeTab === 'recaptcha' ? 'border-accent text-accent' : 'border-transparent text-text-secondary hover:text-primary hover:border-border'" class="pb-3 px-4 font-bold border-b-2 whitespace-nowrap transition-colors">reCAPTCHA v3</button>
            </div>

            <!-- Store Tab -->
            <div x-show="activeTab === 'store'" class="space-y-6">
                <h3 class="text-lg font-black uppercase tracking-tight mb-4">General Information</h3>
                
                <!-- Logo Upload -->
                <div class="mb-6 bg-surface-secondary border border-border p-4 rounded-xl flex items-start gap-6">
                    <div class="w-24 h-24 bg-surface rounded-lg flex items-center justify-center border border-border overflow-hidden">
                        @php $currentLogo = \App\Models\Setting::get('store_logo'); @endphp
                        @if($currentLogo)
                            <img src="{{ $currentLogo }}" alt="Store Logo" class="w-full h-full object-contain">
                        @else
                            <span class="text-xs text-text-muted text-center">No Logo</span>
                        @endif
                    </div>
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-text-secondary uppercase mb-2">Store Logo</label>
                        <input type="file" name="store_logo" accept="image/*" class="w-full text-sm text-text-secondary file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-accent file:text-white hover:file:bg-accent-hover transition-colors">
                        <p class="text-xs text-text-muted mt-2">Recommended: PNG/WEBP with transparent background, max 2MB.</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($settings['store'] as $setting)
                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase mb-2">{{ $setting->label }}</label>
                            @if($setting->type === 'text' || $setting->type === 'encrypted')
                                <input type="text" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            @endif
                            <p class="text-xs text-text-muted mt-1 font-mono text-[10px]">Key: {{ $setting->key }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- RajaOngkir Tab -->
            <div x-show="activeTab === 'rajaongkir'" class="space-y-6" x-cloak>
                <h3 class="text-lg font-black uppercase tracking-tight mb-4">RajaOngkir Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($settings['rajaongkir'] as $setting)
                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase mb-2">{{ $setting->label }}</label>
                            @if($setting->type === 'text' || $setting->type === 'encrypted')
                                <input type="{{ $setting->type === 'encrypted' ? 'password' : 'text' }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            @endif
                            <p class="text-xs text-text-muted mt-1 font-mono text-[10px]">Key: {{ $setting->key }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="bg-info/10 border border-info/20 rounded-lg p-4 text-sm text-info mt-4">
                    <strong>Note:</strong> Get your API Key from <a href="https://rajaongkir.com" target="_blank" class="underline font-bold">RajaOngkir.com</a>. Supported types: starter, basic, pro.
                </div>
            </div>

            <!-- Midtrans Tab -->
            <div x-show="activeTab === 'midtrans'" class="space-y-6" x-cloak>
                <h3 class="text-lg font-black uppercase tracking-tight mb-4">Midtrans Payment Gateway</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($settings['midtrans'] as $setting)
                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase mb-2">{{ $setting->label }}</label>
                            
                            @if($setting->type === 'boolean')
                                <select name="{{ $setting->key }}" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                                    <option value="1" {{ $setting->value == '1' ? 'selected' : '' }}>Yes (Production)</option>
                                    <option value="0" {{ $setting->value == '0' ? 'selected' : '' }}>No (Sandbox / Testing)</option>
                                </select>
                            @else
                                <input type="{{ $setting->type === 'encrypted' ? 'password' : 'text' }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            @endif
                            <p class="text-xs text-text-muted mt-1 font-mono text-[10px]">Key: {{ $setting->key }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Google OAuth Tab -->
            <div x-show="activeTab === 'google'" class="space-y-6" x-cloak>
                <h3 class="text-lg font-black uppercase tracking-tight mb-4">Google OAuth Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($settings['google'] ?? [] as $setting)
                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase mb-2">{{ $setting->label }}</label>
                            @if($setting->type === 'text' || $setting->type === 'encrypted')
                                <input type="{{ $setting->type === 'encrypted' ? 'password' : 'text' }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            @endif
                            <p class="text-xs text-text-muted mt-1">{{ $setting->description }}</p>
                            <p class="text-xs text-text-muted mt-1 font-mono text-[10px]">Key: {{ $setting->key }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="bg-info/10 border border-info/20 rounded-lg p-4 text-sm text-info mt-4">
                    <strong>Note:</strong> Set Authorized redirect URI in Google Cloud Console to: <code class="bg-white/50 px-2 py-1 rounded">{{ route('auth.google.callback') }}</code>
                </div>
            </div>

            <!-- reCAPTCHA v3 Tab -->
            <div x-show="activeTab === 'recaptcha'" class="space-y-6" x-cloak>
                <h3 class="text-lg font-black uppercase tracking-tight mb-4">Google reCAPTCHA v3 Configuration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($settings['recaptcha'] ?? [] as $setting)
                        <div>
                            <label class="block text-xs font-bold text-text-secondary uppercase mb-2">{{ $setting->label }}</label>
                            @if($setting->type === 'text' || $setting->type === 'encrypted')
                                <input type="{{ $setting->type === 'encrypted' ? 'password' : 'text' }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full bg-surface-secondary border border-border text-text-primary rounded-lg p-3 focus:ring-accent focus:border-accent">
                            @endif
                            <p class="text-xs text-text-muted mt-1">{{ $setting->description }}</p>
                            <p class="text-xs text-text-muted mt-1 font-mono text-[10px]">Key: {{ $setting->key }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="bg-surface-secondary p-6 border-t border-border flex justify-end">
            <button type="submit" class="btn btn-primary px-8">Save Changes</button>
        </div>
    </form>
</div>
@endsection
