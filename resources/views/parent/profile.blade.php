@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-headline-xl font-headline-xl text-on-surface">My Profile</h1>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage your parent account settings</p>
            </div>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-emerald-100 text-emerald-800 p-4 rounded-lg border border-emerald-200 text-body-md font-body-md">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Left Column: Summary -->
            <div class="lg:col-span-1 space-y-md">
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden text-center p-xl">
                    <div class="relative w-32 h-32 mx-auto mb-6">
                        <div class="w-full h-full rounded-full bg-primary-fixed flex items-center justify-center border-4 border-surface-container-lowest shadow-sm mx-auto text-primary">
                            <span class="text-[48px] font-bold">{{ substr($user->name, 0, 1) }}</span>
                        </div>
                    </div>
                    <h2 class="text-headline-lg font-headline-lg text-on-surface">{{ $user->name }}</h2>
                    <p class="text-body-lg font-body-lg text-secondary mb-6">{{ $user->email }}</p>
                    
                    <div class="inline-flex items-center px-4 py-1.5 rounded-full bg-secondary-container text-on-secondary-container font-label-md text-label-md tracking-wide">
                        Parent Portal
                    </div>
                </div>
            </div>

            <!-- Right Column: Edit Form -->
            <div class="lg:col-span-2">
                <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
                    <div class="p-md border-b border-outline-variant bg-surface-bright">
                        <h3 class="text-headline-md font-headline-md text-on-surface">Update Information</h3>
                    </div>
                    
                    <form action="{{ route('parent.profile.update') }}" method="POST" class="p-lg">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-xl">
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface shadow-sm focus:border-primary focus:ring focus:ring-primary/20 px-3 py-2 outline-none transition-all font-body-md text-body-md" required>
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Email Address</label>
                                <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-lg border border-outline-variant bg-surface-container text-secondary shadow-sm px-3 py-2 cursor-not-allowed font-body-md text-body-md">
                                <p class="text-xs text-secondary mt-1">Contact admin to change email.</p>
                            </div>
                        </div>

                        <hr class="border-outline-variant my-xl">
                        
                        <h4 class="text-headline-md font-headline-md text-on-surface mb-md">Change Password</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-xl">
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface-variant mb-1">New Password</label>
                                <input type="password" name="password" class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface shadow-sm focus:border-primary focus:ring focus:ring-primary/20 px-3 py-2 outline-none transition-all font-body-md text-body-md" placeholder="Leave blank to keep current">
                            </div>
                            <div>
                                <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Confirm Password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface shadow-sm focus:border-primary focus:ring focus:ring-primary/20 px-3 py-2 outline-none transition-all font-body-md text-body-md">
                            </div>
                        </div>

                        <div class="flex justify-end pt-md border-t border-outline-variant">
                            <button type="submit" class="bg-primary text-on-primary font-label-md text-label-md py-2 px-6 rounded-lg hover:opacity-90 transition-opacity shadow-sm">
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
