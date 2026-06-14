@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col gap-2">
            <nav class="flex text-label-md text-secondary" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('accountant.dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                            <span class="material-symbols-outlined text-[16px] mr-1">home</span>
                            Accountant Portal
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Settings</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-outlined text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">My Profile</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">My Profile</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Manage your account settings and preferences</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
            <!-- Left Column: Profile Summary -->
            <div class="xl:col-span-1">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm flex flex-col items-center text-center">
                    <div class="w-32 h-32 rounded-full bg-primary/10 text-primary flex items-center justify-center text-5xl font-bold mb-6 border-4 border-surface shadow-sm">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-1">{{ $user->name }}</h3>
                    <p class="text-body-lg text-secondary mb-4">{{ $user->email }}</p>
                    <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold bg-primary/10 text-primary border border-primary/20">
                        <span class="material-symbols-outlined text-[16px] mr-1.5">verified_user</span>
                        Accountant
                    </span>

                    <div class="w-full border-t border-outline-variant mt-8 pt-8 text-left space-y-4">
                        <div class="flex justify-between items-center">
                            <p class="text-label-md text-secondary">Account Status</p>
                            <p class="text-body-md font-semibold text-emerald-600 flex items-center">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Active
                            </p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-label-md text-secondary">Member Since</p>
                            <p class="text-body-md font-semibold text-on-surface">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Settings Form -->
            <div class="xl:col-span-2">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-2xl p-8 shadow-sm">
                    <h3 class="text-title-lg font-title-lg text-on-surface mb-6 pb-4 border-b border-outline-variant">Update Information</h3>
                    
                    <form method="POST" action="{{ route('accountant.profile.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-8">
                            <!-- Basic Info -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-label-md font-label-md text-on-surface mb-2">Full Name</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">person</span>
                                        <input type="text" name="name" class="input-field pl-10 bg-surface" value="{{ old('name', $user->name) }}" required>
                                    </div>
                                    @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                                
                                <div>
                                    <label class="block text-label-md font-label-md text-on-surface mb-2">Email Address</label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">mail</span>
                                        <input type="email" name="email" class="input-field pl-10 bg-surface" value="{{ old('email', $user->email) }}" required>
                                    </div>
                                    @error('email') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <!-- Password Section -->
                            <div class="bg-surface border border-outline-variant rounded-xl p-6">
                                <h4 class="text-title-md font-title-md text-on-surface mb-6 flex items-center">
                                    <div class="w-8 h-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center mr-3">
                                        <span class="material-symbols-outlined text-[20px]">lock</span>
                                    </div>
                                    Security Settings
                                </h4>
                                
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-label-md font-label-md text-on-surface mb-2">Current Password</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">key</span>
                                            <input type="password" name="current_password" class="input-field pl-10 bg-background" placeholder="Leave blank to keep current password">
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <label class="block text-label-md font-label-md text-on-surface mb-2">New Password</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">password</span>
                                                <input type="password" name="password" class="input-field pl-10 bg-background" placeholder="Minimum 8 characters">
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-label-md font-label-md text-on-surface mb-2">Confirm New Password</label>
                                            <div class="relative">
                                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-secondary material-symbols-outlined text-[20px]">password</span>
                                                <input type="password" name="password_confirmation" class="input-field pl-10 bg-background" placeholder="Repeat new password">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end gap-4 pt-4 border-t border-outline-variant">
                                <button type="reset" class="btn-outline px-6">Discard Changes</button>
                                <button type="submit" class="btn-primary px-6 shadow-sm flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[20px]">save</span>
                                    Save Profile
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
