@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-label-md font-label-md text-secondary mb-2">
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface">My Profile</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">My Profile</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage your parent account settings and security.</p>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 text-emerald-800 p-4 rounded-xl border border-emerald-200 font-body-md text-body-md flex items-center gap-3 shadow-sm">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-xl">
            <!-- Left Column: Summary -->
            <div class="lg:col-span-1 space-y-xl">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center shadow-sm flex flex-col items-center">
                    <div class="relative w-32 h-32 mb-6 group">
                        <div class="w-full h-full rounded-full bg-primary-fixed flex items-center justify-center border-4 border-surface shadow-md text-primary transition-transform group-hover:scale-105">
                            <span class="text-[48px] font-bold">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <h2 class="text-headline-md font-headline-md text-on-surface">{{ $user->name }}</h2>
                    <p class="text-body-lg font-body-lg text-secondary mb-6">{{ $user->email }}</p>
                    
                    <div class="inline-flex items-center px-4 py-2 rounded-full bg-secondary-container text-on-secondary-container font-label-md text-label-md tracking-wide">
                        Parent Portal Account
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col">
                    <div class="p-xl border-b border-outline-variant bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">manage_accounts</span>
                            Update Information
                        </h3>
                    </div>
                    
                    <form action="{{ route('parent.profile.update') }}" method="POST" class="p-xl">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-2">Full Name <span class="text-error">*</span></label>
                                <div class="relative">
                                    <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-surface border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-body-lg font-body-lg focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors" required>
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">person</span>
                                </div>
                                @error('name') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-2">Email Address</label>
                                <div class="relative">
                                    <input type="email" value="{{ $user->email }}" disabled class="w-full bg-surface-container border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-body-lg font-body-lg text-secondary cursor-not-allowed border-dashed">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px] opacity-50">mail</span>
                                </div>
                                <p class="font-label-sm text-label-sm text-secondary mt-2 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[14px]">info</span>
                                    Contact administration to change email.
                                </p>
                            </div>
                        </div>

                        <hr class="border-outline-variant my-8">
                        
                        <div class="mb-6">
                            <h4 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2 mb-2">
                                <span class="material-symbols-outlined text-secondary">lock</span>
                                Change Password
                            </h4>
                            <p class="text-body-md text-secondary">Leave the fields blank if you do not wish to change your password.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-2">New Password</label>
                                <div class="relative">
                                    <input type="password" name="password" class="w-full bg-surface border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-body-lg font-body-lg focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors" placeholder="••••••••">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">key</span>
                                </div>
                                @error('password') <span class="text-error text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface mb-2">Confirm Password</label>
                                <div class="relative">
                                    <input type="password" name="password_confirmation" class="w-full bg-surface border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-body-lg font-body-lg focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors" placeholder="••••••••">
                                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">password</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-6 border-t border-outline-variant">
                            <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label-lg hover:bg-primary/90 transition-colors flex items-center justify-center shadow-sm gap-2">
                                <span class="material-symbols-outlined text-[18px]">save</span>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
