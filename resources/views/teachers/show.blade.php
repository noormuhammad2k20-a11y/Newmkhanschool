@extends('layouts.app')

@section('title', 'Teacher Profile - ' . $teacher->full_name)

@section('content')
<main class="flex-1 p-margin-mobile md:p-margin-desktop overflow-y-auto w-full">
    <div class="max-w-[1000px] mx-auto space-y-lg">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-background">Teacher Profile</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Detailed view of the teacher's records and information.</p>
            </div>
            <div class="flex items-center gap-sm">
                <a href="{{ route('admin.teachers') }}" class="bg-surface border border-outline-variant text-on-surface text-label-md font-label-md py-sm px-md rounded-DEFAULT flex items-center gap-sm hover:bg-surface-container-high transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Back
                </a>
                <a href="{{ route('admin.teachers.edit', $teacher->id) }}" class="bg-primary text-on-primary text-label-md font-label-md py-sm px-md rounded-DEFAULT flex items-center gap-sm hover:opacity-90 transition-opacity shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">edit</span>
                    Edit Profile
                </a>
            </div>
        </div>

        <!-- Profile Header Card -->
        <div class="bg-surface border border-outline-variant rounded-2xl p-xl shadow-sm flex flex-col md:flex-row items-center md:items-start gap-lg relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-container rounded-bl-full opacity-20 -z-0"></div>
            
            @php
                $names = explode(' ', $teacher->full_name);
                $initials = strtoupper(substr($names[0], 0, 1) . (isset($names[1]) ? substr($names[1], 0, 1) : ''));
            @endphp
            <div class="w-24 h-24 rounded-full bg-primary-container text-primary text-headline-lg font-bold flex items-center justify-center shrink-0 z-10 shadow-sm">
                {{ $initials }}
            </div>
            
            <div class="flex-1 text-center md:text-left z-10">
                <h3 class="text-display-sm font-headline-lg text-on-background mb-xs">{{ $teacher->full_name }}</h3>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-md mt-sm">
                    <span class="inline-flex items-center gap-xs px-sm py-xs bg-surface-container-high text-on-surface-variant rounded-md text-label-md">
                        <span class="material-symbols-outlined text-[16px]">badge</span>
                        Emp ID: {{ $teacher->employee_number ?? 'N/A' }}
                    </span>
                    <span class="inline-flex items-center gap-xs px-sm py-xs bg-[#e6f4ea] text-[#137333] rounded-md text-label-md font-bold">
                        <span class="material-symbols-outlined text-[16px]">check_circle</span>
                        Active
                    </span>
                </div>
            </div>
        </div>

        <!-- Details Bento Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-lg">
            
            <!-- Academic Information -->
            <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-sm mb-lg border-b border-outline-variant pb-sm">
                    <span class="material-symbols-outlined text-primary text-[24px]">school</span>
                    <h4 class="text-title-lg font-headline-md text-on-background">Professional Details</h4>
                </div>
                <div class="space-y-md">
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Highest Qualification</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $teacher->qualification ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Subject Specialization</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $teacher->specialization ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Years of Experience</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $teacher->experience ?? 0 }} Years</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-sm mb-lg border-b border-outline-variant pb-sm">
                    <span class="material-symbols-outlined text-primary text-[24px]">person</span>
                    <h4 class="text-title-lg font-headline-md text-on-background">Contact & Personal Information</h4>
                </div>
                <div class="space-y-md">
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Email Address</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $teacher->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Mobile Number</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $teacher->mobile ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">CNIC / National ID</p>
                        <p class="text-body-lg font-medium text-on-background font-mono">{{ $teacher->cnic ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
