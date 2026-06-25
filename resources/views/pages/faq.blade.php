@extends('layouts.app')

@section('title', 'FAQ | Asqi Apparel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">Freq. Asked Questions</h1>
    </div>
    
    <div class="space-y-4" x-data="{ active: null }">
        <!-- FAQ Item 1 -->
        <div class="border-2 border-primary rounded-none bg-surface">
            <button @click="active = (active === 1 ? null : 1)" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                <h3 class="text-xl font-black uppercase tracking-widest text-primary">Are your jerseys authentic?</h3>
                <svg class="w-6 h-6 transform transition-transform" :class="active === 1 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="active === 1" x-collapse>
                <div class="p-6 pt-0 border-t-2 border-primary text-text-secondary leading-relaxed">
                    Yes, absolutely. We source all our materials from authorized manufacturers to ensure 100% authenticity and highest grade performance standard.
                </div>
            </div>
        </div>

        <!-- FAQ Item 2 -->
        <div class="border-2 border-primary rounded-none bg-surface">
            <button @click="active = (active === 2 ? null : 2)" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                <h3 class="text-xl font-black uppercase tracking-widest text-primary">Can I customize my jersey with a name and number?</h3>
                <svg class="w-6 h-6 transform transition-transform" :class="active === 2 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="active === 2" x-collapse>
                <div class="p-6 pt-0 border-t-2 border-primary text-text-secondary leading-relaxed">
                    Currently, we do not offer online customization. However, our jerseys are engineered with premium materials ready to be pressed at your local professional sports shop.
                </div>
            </div>
        </div>

        <!-- FAQ Item 3 -->
        <div class="border-2 border-primary rounded-none bg-surface">
            <button @click="active = (active === 3 ? null : 3)" class="w-full text-left p-6 flex justify-between items-center focus:outline-none">
                <h3 class="text-xl font-black uppercase tracking-widest text-primary">How do I wash and care for the jerseys?</h3>
                <svg class="w-6 h-6 transform transition-transform" :class="active === 3 ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div x-show="active === 3" x-collapse>
                <div class="p-6 pt-0 border-t-2 border-primary text-text-secondary leading-relaxed">
                    We recommend washing your jerseys inside-out in cold water. Do not use bleach or fabric softeners. Tumble dry on low heat or hang dry to preserve the longevity of the performance fabric.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
