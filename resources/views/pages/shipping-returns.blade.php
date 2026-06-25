@extends('layouts.app')

@section('title', 'Shipping & Returns | Asqi Apparel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">Shipping & Returns</h1>
    </div>
    <div class="bg-surface border-2 border-primary rounded-none p-8 md:p-12 space-y-8">
        <section>
            <h2 class="text-2xl font-black uppercase tracking-widest mb-4">Shipping Information</h2>
            <p class="text-text-secondary leading-relaxed mb-4">
                We currently ship to all locations within Indonesia. All orders are processed within 1-2 business days.
                Standard shipping takes 3-5 business days, while express shipping delivers in 1-2 business days.
            </p>
            <ul class="list-disc list-inside text-text-secondary leading-relaxed space-y-2">
                <li>Standard Shipping: Rp 20,000 (Free over Rp 1,000,000)</li>
                <li>Express Shipping: Rp 50,000</li>
            </ul>
        </section>

        <hr class="border-border">

        <section>
            <h2 class="text-2xl font-black uppercase tracking-widest mb-4">Return Policy</h2>
            <p class="text-text-secondary leading-relaxed mb-4">
                Not perfectly satisfied? We accept returns within 14 days of the delivery date. Items must be unworn, unwashed, and have original tags attached.
            </p>
            <p class="text-text-secondary leading-relaxed">
                To initiate a return, please contact our support team with your order number. Once approved, you will receive a shipping label. Refunds will be processed within 5-7 business days after we receive your returned items.
            </p>
        </section>
    </div>
</div>
@endsection
