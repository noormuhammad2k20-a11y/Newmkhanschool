@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">My Profile</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Manage your account and personal information.</p>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-xl">
            <!-- Profile Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl text-center flex flex-col items-center">
                <div class="w-32 h-32 rounded-full bg-primary-fixed flex items-center justify-center text-primary text-4xl font-bold mb-4">
                    {{ substr($teacher->full_name ?? auth()->user()->name, 0, 1) }}
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">{{ $teacher->full_name ?? auth()->user()->name }}</h3>
                <p class="text-body-md text-secondary">{{ $teacher->specialization ?? 'Teacher' }}</p>
                
                <div class="w-full mt-8 text-left space-y-4">
                    <div>
                        <p class="text-label-sm text-secondary uppercase tracking-wider mb-1">Employee No.</p>
                        <p class="font-medium text-on-surface">{{ $teacher->employee_number ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-secondary uppercase tracking-wider mb-1">Assigned Classes</p>
                        <p class="font-medium text-on-surface">{{ $classes ?: 'None' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-secondary uppercase tracking-wider mb-1">Assigned Subjects</p>
                        <p class="font-medium text-on-surface">{{ $subjects ?: 'None' }}</p>
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <div class="md:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl p-xl">
                <h3 class="text-headline-sm font-headline-sm text-on-surface mb-6 border-b border-outline-variant pb-4">Personal Information</h3>
                <form action="{{ route('teacher.profile.update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Full Name</label>
                            <input type="text" name="full_name" value="{{ $teacher->full_name ?? auth()->user()->name }}" class="w-full bg-surface-bright border border-outline-variant rounded p-3 text-body-md text-on-surface">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Email Address (Read Only)</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled class="w-full bg-surface-variant border border-outline-variant rounded p-3 text-body-md text-secondary cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Mobile Number</label>
                            <input type="text" name="mobile" value="{{ $teacher->mobile ?? '' }}" class="w-full bg-surface-bright border border-outline-variant rounded p-3 text-body-md text-on-surface">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">Qualification (Read Only)</label>
                            <input type="text" value="{{ $teacher->qualification ?? '' }}" disabled class="w-full bg-surface-variant border border-outline-variant rounded p-3 text-body-md text-secondary cursor-not-allowed">
                        </div>
                    </div>
                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="bg-primary text-on-primary px-6 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-dark transition-colors">Save Changes</button>
                    </div>
                </form>

                <!-- Password Change (Static UI for demo) -->
                <h3 class="text-headline-sm font-headline-sm text-on-surface mt-10 mb-6 border-b border-outline-variant pb-4">Change Password</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Current Password</label>
                        <input type="password" class="w-full max-w-md bg-surface-bright border border-outline-variant rounded p-3 text-body-md text-on-surface">
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">New Password</label>
                        <input type="password" class="w-full max-w-md bg-surface-bright border border-outline-variant rounded p-3 text-body-md text-on-surface">
                    </div>
                    <div>
                        <button class="border border-outline-variant text-on-surface px-6 py-2 rounded-lg text-label-md font-label-md hover:bg-surface-container-low transition-colors">Update Password</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
