@extends('layouts.app')

@section('title', 'My Children')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-headline-xl font-headline-xl text-on-surface">My Children</h1>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage profiles and academics for your linked students</p>
            </div>
        </div>

        @if(isset($linkedStudents) && count($linkedStudents) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-md">
                @foreach($linkedStudents as $student)
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col transition-all hover:border-primary group shadow-sm">
                        <div class="p-lg text-center border-b border-outline-variant relative bg-surface-bright">
                            <div class="absolute top-4 right-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    {{ $student->status ?? 'Active' }}
                                </span>
                            </div>
                            <div class="relative w-24 h-24 mx-auto mb-4">
                                @if($student->photo)
                                    <img src="{{ asset('storage/'.$student->photo) }}" class="w-full h-full rounded-full object-cover border-4 border-surface-container-lowest shadow-sm">
                                @else
                                    <div class="w-full h-full rounded-full bg-primary-fixed flex items-center justify-center border-4 border-surface-container-lowest shadow-sm mx-auto text-primary">
                                        <span class="text-headline-xl font-headline-xl">{{ substr($student->first_name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <h2 class="text-headline-md font-headline-md text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</h2>
                            <p class="text-body-md font-body-md text-secondary mb-2">Class {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</p>
                            <p class="text-label-md font-label-md text-outline">Admission No: {{ $student->admission_no }}</p>
                        </div>
                        
                        <div class="p-0 flex-1 bg-surface-container-lowest">
                            <ul class="divide-y divide-outline-variant">
                                <li>
                                    <a href="{{ route('parent.child.attendance', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
                                        <span class="flex items-center gap-3 text-body-md font-body-md text-on-surface">
                                            <span class="material-symbols-outlined text-primary text-[20px]">co_present</span> Attendance
                                        </span>
                                        <span class="material-symbols-outlined text-outline text-[20px]">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.marks', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
                                        <span class="flex items-center gap-3 text-body-md font-body-md text-on-surface">
                                            <span class="material-symbols-outlined text-emerald-600 text-[20px]">grade</span> Marks & Results
                                        </span>
                                        <span class="material-symbols-outlined text-outline text-[20px]">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.fees', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
                                        <span class="flex items-center gap-3 text-body-md font-body-md text-on-surface">
                                            <span class="material-symbols-outlined text-red-600 text-[20px]">account_balance_wallet</span> Fee Status
                                        </span>
                                        <span class="material-symbols-outlined text-outline text-[20px]">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.timetable', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
                                        <span class="flex items-center gap-3 text-body-md font-body-md text-on-surface">
                                            <span class="material-symbols-outlined text-purple-600 text-[20px]">calendar_today</span> Timetable
                                        </span>
                                        <span class="material-symbols-outlined text-outline text-[20px]">chevron_right</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('parent.child.assignments', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-surface-container-low transition-colors">
                                        <span class="flex items-center gap-3 text-body-md font-body-md text-on-surface">
                                            <span class="material-symbols-outlined text-orange-600 text-[20px]">assignment</span> Assignments
                                        </span>
                                        <span class="material-symbols-outlined text-outline text-[20px]">chevron_right</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-surface-container-lowest rounded-xl p-12 text-center border border-outline-variant shadow-sm">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-high mb-4 text-secondary">
                    <span class="material-symbols-outlined text-3xl">family_restroom</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">No Children Linked</h3>
                <p class="text-body-md font-body-md text-secondary mt-1 mb-4">You don't have any students linked to your parent account.</p>
                <div class="bg-surface-container-low p-4 rounded-lg inline-block text-left border border-outline-variant mt-4">
                    <strong class="text-on-surface font-label-md">How to link a child:</strong><br>
                    <span class="text-secondary text-body-md">Please contact the school administration office to have your children linked to your parent account profile.</span>
                </div>
            </div>
        @endif
    </div>
</main>
@endsection
