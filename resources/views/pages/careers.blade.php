@extends('layouts.app')

@section('title', 'Careers | Asqi Apparel')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-12 pb-4 border-b-2 border-primary">
        <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">Careers at ASQI</h1>
    </div>
    
    <div class="bg-surface border-2 border-primary rounded-none p-8 md:p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-primary mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <h2 class="text-3xl font-black uppercase tracking-tight mb-4">Join The Team</h2>
        <p class="text-text-secondary leading-relaxed mb-8 max-w-2xl mx-auto">
            We are always on the lookout for passionate individuals driven by sports and innovation. Currently, there are no open positions available, but we'd love to hear from you.
        </p>
        <a href="mailto:careers@asqiapparel.com" class="btn btn-primary px-8 py-3 text-lg">Send Your Resume</a>
    </div>
</div>
@endsection
