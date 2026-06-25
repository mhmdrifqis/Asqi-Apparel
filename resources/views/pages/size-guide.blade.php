@extends('layouts.app')

@section('title', 'Size Guide | Asqi Apparel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">Size Guide</h1>
    </div>
    <div class="bg-surface border-2 border-primary rounded-none p-8 md:p-12 space-y-8">
        <p class="text-text-secondary leading-relaxed">
            Ensure you get the perfect fit for peak performance. Use the charts below to determine your ideal ASQI Apparel size.
        </p>

        <section>
            <h2 class="text-2xl font-black uppercase tracking-widest mb-4">Men's & Unisex Jerseys</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse border-2 border-primary">
                    <thead>
                        <tr class="bg-primary text-white uppercase tracking-widest text-sm">
                            <th class="p-3 border-r-2 border-primary">Size</th>
                            <th class="p-3 border-r-2 border-primary">Chest (cm)</th>
                            <th class="p-3">Waist (cm)</th>
                        </tr>
                    </thead>
                    <tbody class="text-text-secondary">
                        <tr class="border-b-2 border-primary"><td class="p-3 font-bold border-r-2 border-primary text-primary">S</td><td class="p-3 border-r-2 border-primary">88 - 94</td><td class="p-3">76 - 82</td></tr>
                        <tr class="border-b-2 border-primary"><td class="p-3 font-bold border-r-2 border-primary text-primary">M</td><td class="p-3 border-r-2 border-primary">95 - 102</td><td class="p-3">83 - 90</td></tr>
                        <tr class="border-b-2 border-primary"><td class="p-3 font-bold border-r-2 border-primary text-primary">L</td><td class="p-3 border-r-2 border-primary">103 - 111</td><td class="p-3">91 - 99</td></tr>
                        <tr class="border-b-2 border-primary"><td class="p-3 font-bold border-r-2 border-primary text-primary">XL</td><td class="p-3 border-r-2 border-primary">112 - 121</td><td class="p-3">100 - 109</td></tr>
                        <tr><td class="p-3 font-bold border-r-2 border-primary text-primary">XXL</td><td class="p-3 border-r-2 border-primary">122 - 132</td><td class="p-3">110 - 121</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section>
            <h2 class="text-2xl font-black uppercase tracking-widest mb-4">How to Measure</h2>
            <ul class="list-disc list-inside text-text-secondary leading-relaxed space-y-2">
                <li><strong>Chest:</strong> Measure around the fullest part of your chest, keeping the measuring tape horizontal.</li>
                <li><strong>Waist:</strong> Measure around the narrowest part (typically where your body bends side to side), keeping the tape horizontal.</li>
            </ul>
        </section>
    </div>
</div>
@endsection
