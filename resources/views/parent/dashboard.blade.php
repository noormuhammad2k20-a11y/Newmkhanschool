@extends('layouts.app')

@section('title', 'Parent Dashboard')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Welcome back, {{ auth()->user()->name }}!</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Here is an overview of your children's academics.</p>
        </div>

        @if(isset($linkedStudents) && count($linkedStudents) > 0)
            <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-md">
                @foreach($linkedStudents as $student)
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col group hover:border-primary transition-colors">
                        <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-4">
                            <div class="w-14 h-14 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold text-lg border-2 border-surface-container-lowest shadow-sm">
                                @if($student->photo)
                                    <img src="{{ asset('storage/'.$student->photo) }}" class="w-full h-full rounded-full object-cover">
                                @else
                                    {{ substr($student->first_name, 0, 1) }}
                                @endif
                            </div>
                            <div>
                                <h3 class="text-headline-md font-headline-md text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</h3>
                                <p class="text-body-md font-body-md text-secondary">Class: {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</p>
                            </div>
                        </div>
                        
                        <div class="p-md grid grid-cols-2 gap-3">
                            <a href="{{ route('parent.child.attendance', $student->id) }}" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant transition-colors">
                                <span class="material-symbols-outlined text-primary text-[20px]">co_present</span>
                                <span class="font-label-md text-label-md">Attendance</span>
                            </a>
                            <a href="{{ route('parent.child.marks', $student->id) }}" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant transition-colors">
                                <span class="material-symbols-outlined text-emerald-600 text-[20px]">grade</span>
                                <span class="font-label-md text-label-md">Marks</span>
                            </a>
                            <a href="{{ route('parent.child.fees', $student->id) }}" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant transition-colors">
                                <span class="material-symbols-outlined text-red-600 text-[20px]">account_balance_wallet</span>
                                <span class="font-label-md text-label-md">Fees</span>
                            </a>
                            <a href="{{ route('parent.child.assignments', $student->id) }}" class="flex items-center gap-3 p-3 rounded-lg bg-surface-container-low hover:bg-surface-container text-on-surface-variant transition-colors">
                                <span class="material-symbols-outlined text-orange-600 text-[20px]">assignment</span>
                                <span class="font-label-md text-label-md">Assignments</span>
                            </a>
                        </div>
                        
                        <div class="mt-auto border-t border-outline-variant bg-surface-container-lowest p-3 flex justify-between">
                            <a href="{{ route('parent.child.timetable', $student->id) }}" class="font-label-md text-label-md text-secondary hover:text-primary flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">calendar_today</span> Timetable
                            </a>
                            <a href="{{ route('parent.messages') }}" class="font-label-md text-label-md text-secondary hover:text-primary flex items-center gap-1 transition-colors">
                                <span class="material-symbols-outlined text-[16px]">chat</span> Contact Teacher
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-surface-container-lowest rounded-xl p-12 text-center border border-outline-variant">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-high mb-4 text-secondary">
                    <span class="material-symbols-outlined text-3xl">family_restroom</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">No Children Linked</h3>
                <p class="text-body-md font-body-md text-secondary mt-1 mb-4">You don't have any students linked to your parent account.</p>
                <p class="text-body-md font-body-md text-secondary">Please contact the school administration to link your children to your profile.</p>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-md">
            {{-- Announcements --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                    <h2 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-orange-500">campaign</span>
                        Recent Announcements
                    </h2>
                    <a href="{{ route('parent.announcements') }}" class="text-label-md font-label-md text-primary hover:underline">View All</a>
                </div>
                <div class="p-md flex-1 space-y-4">
                    @forelse($announcements ?? [] as $ann)
                        <div class="border-l-2 border-orange-400 pl-3 pb-3 border-b border-outline-variant last:border-b-0">
                            <h4 class="font-label-md text-label-md text-on-surface mb-1">{{ $ann->title }}</h4>
                            <p class="text-body-md font-body-md text-secondary line-clamp-2">{{ Str::limit($ann->content, 120) }}</p>
                            <span class="text-xs font-medium text-outline mt-1 block">{{ $ann->created_at->diffForHumans() }}</span>
                        </div>
                    @empty
                        <p class="text-body-md font-body-md text-secondary text-center py-4">No recent announcements.</p>
                    @endforelse
                </div>
            </div>
            
            {{-- Contact School --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden h-fit">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                    <h2 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">contact_support</span>
                        Contact School
                    </h2>
                </div>
                <div class="p-md">
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">phone</span>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface">Admin Office</p>
                                <p class="text-body-md font-body-md text-secondary">+1 234 567 8900</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">email</span>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface">Email Support</p>
                                <p class="text-body-md font-body-md text-secondary">support@school.edu</p>
                            </div>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-secondary">
                                <span class="material-symbols-outlined">location_on</span>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface">Location</p>
                                <p class="text-body-md font-body-md text-secondary">123 Education Street, City, State 12345</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>
</main>
@endsection
