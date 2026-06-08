@extends('layouts.app')

@section('title', 'My Children')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">My Children</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage profiles and academics for your linked students</p>
    </div>
</div>

@if(isset($linkedStudents) && count($linkedStudents) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($linkedStudents as $student)
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col transition-all hover:border-blue-300">
                <div class="p-6 text-center border-b border-gray-100 dark:border-gray-700 relative">
                    <div class="absolute top-4 right-4">
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                            {{ $student->status }}
                        </span>
                    </div>
                    <div class="relative w-20 h-20 mx-auto mb-4">
                        @if($student->photo)
                            <img src="{{ asset('storage/'.$student->photo) }}" class="w-20 h-20 rounded-full object-cover border-4 border-blue-50 dark:border-blue-900/30">
                        @else
                            <div class="w-20 h-20 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center border-4 border-blue-50 dark:border-gray-700 mx-auto">
                                <span class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ substr($student->first_name, 0, 1) }}</span>
                            </div>
                        @endif
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</h2>
                    <p class="text-sm text-gray-500 mb-2">Class {{ $student->currentClass->name ?? 'N/A' }} {{ $student->currentSection->name ?? '' }}</p>
                    <p class="text-xs text-gray-400">Admission No: {{ $student->admission_no }}</p>
                </div>
                
                <div class="p-0 flex-1 bg-gray-50 dark:bg-gray-900/50">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                        <li>
                            <a href="{{ route('parent.child.attendance', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-rounded text-blue-500">co_present</span> Attendance
                                </span>
                                <span class="material-symbols-rounded text-gray-400 text-sm">chevron_right</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parent.child.marks', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-rounded text-green-500">grade</span> Marks & Results
                                </span>
                                <span class="material-symbols-rounded text-gray-400 text-sm">chevron_right</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parent.child.fees', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-rounded text-red-500">account_balance_wallet</span> Fee Status
                                </span>
                                <span class="material-symbols-rounded text-gray-400 text-sm">chevron_right</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parent.child.timetable', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-rounded text-purple-500">calendar_today</span> Timetable
                                </span>
                                <span class="material-symbols-rounded text-gray-400 text-sm">chevron_right</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('parent.child.assignments', $student->id) }}" class="flex items-center justify-between p-4 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                <span class="flex items-center gap-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    <span class="material-symbols-rounded text-orange-500">assignment</span> Assignments
                                </span>
                                <span class="material-symbols-rounded text-gray-400 text-sm">chevron_right</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700 shadow-sm">
        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
            <span class="material-symbols-rounded text-3xl">family_restroom</span>
        </div>
        <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Children Linked</h3>
        <p class="text-gray-500 dark:text-gray-400 mt-1 mb-4">You don't have any students linked to your parent account.</p>
        <p class="text-sm text-gray-500 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg inline-block text-left border border-gray-100 dark:border-gray-700">
            <strong>How to link a child:</strong><br>
            Please contact the school administration office to have your children linked to your parent account profile.
        </p>
    </div>
@endif
@endsection
