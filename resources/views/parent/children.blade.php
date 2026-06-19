@extends('layouts.app')

@section('title', 'My Children')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-label-md font-label-md text-secondary mb-2">
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span class="material-symbols-rounded text-[16px]">chevron_right</span>
                    <span class="text-on-surface">My Children</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">My Children</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage profiles and academics for your linked students</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('parent.dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors">
                    <span class="material-symbols-rounded text-[18px]">arrow_back</span>
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if(isset($children) && count($children) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-xl">
                @foreach($children as $student)
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col transition-all hover:border-primary hover:shadow-md group">
                        <div class="p-xl border-b border-outline-variant bg-surface-bright flex flex-col sm:flex-row items-center gap-6 relative">
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-label-sm font-label-sm bg-emerald-100 text-emerald-800 border border-emerald-200">
                                    {{ $student->status ?? 'Active' }}
                                </span>
                            </div>
                            <div class="w-24 h-24 shrink-0 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-3xl border-4 border-surface-container-lowest shadow-sm">
                                @if($student->photo)
                                    <img src="{{ asset('storage/'.$student->photo) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name ?? '', 0, 1) }}
                                @endif
                            </div>
                            <div class="text-center sm:text-left">
                                <h2 class="text-headline-md font-headline-md text-on-surface mb-2">{{ $student->first_name }} {{ $student->last_name }}</h2>
                                <div class="flex flex-wrap items-center justify-center sm:justify-start gap-4 text-body-md text-secondary">
                                    <span class="flex items-center gap-1"><span class="material-symbols-rounded text-[18px]">class</span> Class {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</span>
                                    <span class="flex items-center gap-1"><span class="material-symbols-rounded text-[18px]">badge</span> Reg: {{ $student->admission_no }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-0 flex-1">
                            <ul class="divide-y divide-outline-variant">
                                <li>
                                    <a href="{{ route('parent.child.attendance', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors group/item">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary group-hover/item:bg-primary group-hover/item:text-on-primary transition-colors">
                                                <span class="material-symbols-rounded text-[20px]">co_present</span>
                                            </div>
                                            <div>
                                                <h4 class="text-title-md font-title-md text-on-surface">Attendance</h4>
                                                <p class="text-label-sm font-label-sm text-secondary">View daily attendance records</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-rounded text-outline group-hover/item:text-primary transition-colors">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.marks', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors group/item">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-emerald-600 group-hover/item:bg-emerald-600 group-hover/item:text-white transition-colors">
                                                <span class="material-symbols-rounded text-[20px]">grade</span>
                                            </div>
                                            <div>
                                                <h4 class="text-title-md font-title-md text-on-surface">Marks & Results</h4>
                                                <p class="text-label-sm font-label-sm text-secondary">Exams, tests, and report cards</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-rounded text-outline group-hover/item:text-primary transition-colors">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.fees', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors group/item">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-red-600 group-hover/item:bg-red-600 group-hover/item:text-white transition-colors">
                                                <span class="material-symbols-rounded text-[20px]">account_balance_wallet</span>
                                            </div>
                                            <div>
                                                <h4 class="text-title-md font-title-md text-on-surface">Fee Status</h4>
                                                <p class="text-label-sm font-label-sm text-secondary">Invoices, payments, and history</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-rounded text-outline group-hover/item:text-primary transition-colors">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.timetable', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors group/item">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-purple-600 group-hover/item:bg-purple-600 group-hover/item:text-white transition-colors">
                                                <span class="material-symbols-rounded text-[20px]">calendar_today</span>
                                            </div>
                                            <div>
                                                <h4 class="text-title-md font-title-md text-on-surface">Timetable</h4>
                                                <p class="text-label-sm font-label-sm text-secondary">Weekly class schedule</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-rounded text-outline group-hover/item:text-primary transition-colors">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.assignments', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors group/item">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-orange-600 group-hover/item:bg-orange-600 group-hover/item:text-white transition-colors">
                                                <span class="material-symbols-rounded text-[20px]">assignment</span>
                                            </div>
                                            <div>
                                                <h4 class="text-title-md font-title-md text-on-surface">Assignments</h4>
                                                <p class="text-label-sm font-label-sm text-secondary">Homework and projects</p>
                                            </div>
                                        </div>
                                        <span class="material-symbols-rounded text-outline group-hover/item:text-primary transition-colors">chevron_right</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col items-center justify-center text-center py-20 shadow-sm">
                <div class="w-20 h-20 rounded-full bg-surface-container-low flex items-center justify-center text-secondary mb-4">
                    <span class="material-symbols-rounded text-[40px]">family_restroom</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface mb-2">No Children Linked</h3>
                <p class="text-body-lg font-body-lg text-secondary max-w-md">You don't have any students linked to your parent account.</p>
                <div class="bg-surface-container border border-outline-variant rounded-lg p-md mt-6 max-w-lg text-left">
                    <p class="text-label-md font-label-md text-on-surface flex items-center gap-2 mb-2">
                        <span class="material-symbols-rounded text-[18px]">info</span> How to link a child
                    </p>
                    <p class="text-body-md text-secondary">Please contact the school administration office to have your children officially linked to your parent account profile. They will verify your identity and link the student records.</p>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
