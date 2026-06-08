@extends('layouts.app')

@section('title', 'Parent Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ auth()->user()->name }}!</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Here is an overview of your children's academics.</p>
</div>

@if(isset($linkedStudents) && count($linkedStudents) > 0)
    <div class="grid grid-cols-1 lg:grid-cols-2 xl:grid-cols-3 gap-6 mb-8">
        @foreach($linkedStudents as $student)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all hover:shadow-md">
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-900/20 flex items-center gap-4">
                    <div class="w-14 h-14 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center shrink-0 border-2 border-white dark:border-gray-800 shadow-sm">
                        @if($student->photo)
                            <img src="{{ asset('storage/'.$student->photo) }}" class="w-full h-full rounded-full object-cover">
                        @else
                            <span class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ substr($student->first_name, 0, 1) }}</span>
                        @endif
                    </div>
                    <div>
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</h3>
                        <p class="text-sm text-gray-500">Class: {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</p>
                    </div>
                </div>
                
                <div class="p-5 grid grid-cols-2 gap-4">
                    <a href="{{ route('parent.child.attendance', $student->id) }}" class="flex flex-col items-center justify-center p-3 rounded-lg bg-blue-50 dark:bg-blue-900/10 text-blue-700 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-colors">
                        <span class="material-symbols-rounded mb-1">co_present</span>
                        <span class="text-xs font-medium">Attendance</span>
                    </a>
                    <a href="{{ route('parent.child.marks', $student->id) }}" class="flex flex-col items-center justify-center p-3 rounded-lg bg-green-50 dark:bg-green-900/10 text-green-700 dark:text-green-400 hover:bg-green-100 dark:hover:bg-green-900/30 transition-colors">
                        <span class="material-symbols-rounded mb-1">grade</span>
                        <span class="text-xs font-medium">Marks</span>
                    </a>
                    <a href="{{ route('parent.child.fees', $student->id) }}" class="flex flex-col items-center justify-center p-3 rounded-lg bg-red-50 dark:bg-red-900/10 text-red-700 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/30 transition-colors">
                        <span class="material-symbols-rounded mb-1">account_balance_wallet</span>
                        <span class="text-xs font-medium">Fees</span>
                    </a>
                    <a href="{{ route('parent.child.assignments', $student->id) }}" class="flex flex-col items-center justify-center p-3 rounded-lg bg-orange-50 dark:bg-orange-900/10 text-orange-700 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-900/30 transition-colors">
                        <span class="material-symbols-rounded mb-1">assignment</span>
                        <span class="text-xs font-medium">Assignments</span>
                    </a>
                </div>
                
                <div class="mt-auto border-t border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-800/50 p-3 flex justify-between">
                    <a href="{{ route('parent.child.timetable', $student->id) }}" class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1">
                        <span class="material-symbols-rounded text-[14px]">calendar_today</span> Timetable
                    </a>
                    <a href="{{ route('parent.messages') }}" class="text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 flex items-center gap-1">
                        <span class="material-symbols-rounded text-[14px]">chat</span> Contact Teacher
                    </a>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700 mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
            <span class="material-symbols-rounded text-3xl">family_restroom</span>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Children Linked</h3>
        <p class="text-gray-500 dark:text-gray-400 mt-1 mb-4">You don't have any students linked to your parent account.</p>
        <p class="text-sm text-gray-500">Please contact the school administration to link your children to your profile.</p>
    </div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    {{-- Announcements --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
            <h2 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-orange-500">campaign</span>
                Recent Announcements
            </h2>
            <a href="{{ route('parent.announcements') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">View All</a>
        </div>
        <div class="p-4 space-y-4">
            @forelse($announcements ?? [] as $ann)
                <div class="border-l-2 border-orange-400 pl-3 pb-2 border-b border-gray-100 dark:border-gray-700 last:border-b-0">
                    <h4 class="font-medium text-gray-900 dark:text-white text-sm mb-1">{{ $ann->title }}</h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">{{ Str::limit($ann->content, 120) }}</p>
                    <span class="text-[10px] text-gray-400 mt-1 block">{{ $ann->created_at->diffForHumans() }}</span>
                </div>
            @empty
                <p class="text-sm text-gray-500 text-center py-4">No recent announcements.</p>
            @endforelse
        </div>
    </div>
    
    {{-- Contact School --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden h-fit">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-900/50">
            <h2 class="font-bold text-gray-900 dark:text-white flex items-center gap-2">
                <span class="material-symbols-rounded text-blue-500">contact_support</span>
                Contact School
            </h2>
        </div>
        <div class="p-6">
            <ul class="space-y-4 text-sm">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400">phone</span>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Admin Office</p>
                        <p class="text-gray-500">+1 234 567 8900</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400">email</span>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Email Support</p>
                        <p class="text-gray-500">support@school.edu</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400">location_on</span>
                    <div>
                        <p class="font-medium text-gray-900 dark:text-white">Location</p>
                        <p class="text-gray-500">123 Education Street, City, State 12345</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
