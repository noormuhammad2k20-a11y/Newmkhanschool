@extends('layouts.app')

@section('title', 'Student Profile - ' . $student->first_name)

@section('content')
<main class="flex-1 p-margin-mobile md:p-margin-desktop overflow-y-auto w-full">
    <div class="max-w-[1000px] mx-auto space-y-lg">
        
        <!-- Header Section -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-background">Student Profile</h2>
                <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Detailed view of the student's records and information.</p>
            </div>
            <div class="flex items-center gap-sm">
                <a href="{{ route('admin.students') }}" class="bg-surface border border-outline-variant text-on-surface text-label-md font-label-md py-sm px-md rounded-DEFAULT flex items-center gap-sm hover:bg-surface-container-high transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">arrow_back</span>
                    Back
                </a>
                <a href="{{ route('admin.students.edit', $student->id) }}" class="bg-primary text-on-primary text-label-md font-label-md py-sm px-md rounded-DEFAULT flex items-center gap-sm hover:opacity-90 transition-opacity shadow-sm">
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
                $initials = strtoupper(substr($student->first_name, 0, 1) . ($student->last_name ? substr($student->last_name, 0, 1) : ''));
            @endphp
            <div class="w-24 h-24 rounded-full bg-primary-container text-primary text-headline-lg font-bold flex items-center justify-center shrink-0 z-10 shadow-sm">
                {{ $initials }}
            </div>
            
            <div class="flex-1 text-center md:text-left z-10">
                <h3 class="text-display-sm font-headline-lg text-on-background mb-xs">{{ $student->first_name }} {{ $student->last_name }}</h3>
                <div class="flex flex-wrap items-center justify-center md:justify-start gap-md mt-sm">
                    <span class="inline-flex items-center gap-xs px-sm py-xs bg-surface-container-high text-on-surface-variant rounded-md text-label-md">
                        <span class="material-symbols-outlined text-[16px]">badge</span>
                        Adm No: {{ $student->admission_no }}
                    </span>
                    <span class="inline-flex items-center gap-xs px-sm py-xs {{ strtolower($student->status) === 'regular' ? 'bg-[#e6f4ea] text-[#137333]' : 'bg-[#fce8e6] text-[#c5221f]' }} rounded-md text-label-md font-bold">
                        <span class="material-symbols-outlined text-[16px]">
                            {{ strtolower($student->status) === 'regular' ? 'check_circle' : 'cancel' }}
                        </span>
                        {{ ucfirst($student->status) }}
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
                    <h4 class="text-title-lg font-headline-md text-on-background">Academic Details</h4>
                </div>
                <div class="space-y-md">
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Class & Section</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $student->class_name ?? 'N/A' }} / {{ $student->section_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Enrollment Date</p>
                        <p class="text-body-lg font-medium text-on-background">{{ date('F d, Y', strtotime($student->created_at)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-center gap-sm mb-lg border-b border-outline-variant pb-sm">
                    <span class="material-symbols-outlined text-primary text-[24px]">person</span>
                    <h4 class="text-title-lg font-headline-md text-on-background">Personal Information</h4>
                </div>
                <div class="grid grid-cols-2 gap-md">
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Date of Birth</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $student->dob ? date('F d, Y', strtotime($student->dob)) : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Gender</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $student->gender ?? 'N/A' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">B-Form / National ID</p>
                        <p class="text-body-lg font-medium text-on-background font-mono">{{ $student->b_form_number ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Parent/Guardian Information -->
            <div class="bg-surface border border-outline-variant rounded-xl p-lg shadow-sm hover:shadow-md transition-shadow md:col-span-2">
                <div class="flex items-center gap-sm mb-lg border-b border-outline-variant pb-sm">
                    <span class="material-symbols-outlined text-primary text-[24px]">family_restroom</span>
                    <h4 class="text-title-lg font-headline-md text-on-background">Parent / Guardian Information</h4>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-lg">
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Father/Guardian Name</p>
                        <p class="text-body-lg font-medium text-on-background">{{ $student->father_name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Guardian CNIC</p>
                        <p class="text-body-lg font-medium text-on-background font-mono">{{ $student->father_cnic ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-label-sm text-on-surface-variant uppercase tracking-wider mb-xs">Emergency Contact</p>
                        <div class="flex items-center gap-sm text-body-lg font-medium text-on-background">
                            <span class="material-symbols-outlined text-[18px] text-secondary">phone</span>
                            <a href="tel:{{ $student->mobile_number }}" class="hover:text-primary transition-colors">{{ $student->mobile_number ?? 'N/A' }}</a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</main>
@endsection
