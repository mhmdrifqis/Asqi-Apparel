@extends('layouts.app')

@section('title', 'Profile Settings | ' . config('app.name'))

@section('content')
<div class="py-12 bg-surface">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <div class="mb-8 mt-6 pb-4 border-b-2 border-primary">
            <h1 class="text-4xl sm:text-5xl font-black uppercase tracking-tight italic">Profile Settings</h1>
            <p class="text-text-secondary mt-1 font-bold">Manage your account details, password, and preferences.</p>
        </div>

        <div class="p-6 sm:p-8 bg-white border-2 border-primary rounded-none">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-white border-2 border-primary rounded-none">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-6 sm:p-8 bg-red-50 border-2 border-danger rounded-none">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
