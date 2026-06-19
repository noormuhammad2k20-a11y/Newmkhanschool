@extends('layouts.app')

@section('title', 'Teacher Dashboard')

@section('content')
        <!-- Main Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
            <div class="max-w-[1440px] mx-auto space-y-xl">
                <!-- Page Header -->
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Teacher Dashboard</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Welcome back, {{ auth()->user()->name }}! Here is your daily overview.
                    </p>
                </div>
                <!-- Stats Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                    <!-- Stat Card 1 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">My Classes</h3>
                            <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                                <span class="material-symbols-rounded text-[18px]">class</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">{{ $classesCount ?? 4 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Assigned for this term</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                    <!-- Stat Card 2 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Students</h3>
                            <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                                <span class="material-symbols-rounded text-[18px]">groups</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">{{ $totalStudents ?? 128 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Across all assigned classes</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                    <!-- Stat Card 3 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Assignments Pending</h3>
                            <div class="w-8 h-8 rounded-lg bg-surface-variant flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-rounded text-[18px]">assignment_turned_in</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">{{ $pendingAssignments ?? 12 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-error">
                            <span class="material-symbols-rounded text-[14px]">priority_high</span>
                            <span>Needs grading</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-surface-variant rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                    <!-- Stat Card 4 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Today's Timetable</h3>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                                <span class="material-symbols-rounded text-[18px]">schedule</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">{{ isset($todaysTimetable) ? $todaysTimetable->count() : 3 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-medium text-secondary">
                            <span>Periods scheduled today</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                </div>

                <!-- Extra Stats Grid for Modules -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
                    <!-- Stat Card 5 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">AI Graded</h3>
                            <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                                <span class="material-symbols-rounded text-[18px]">auto_awesome</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">{{ $aiGradedCount ?? 0 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Submissions Graded</span>
                        </div>
                    </div>
                    <!-- Stat Card 6 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Seating Plans</h3>
                            <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                                <span class="material-symbols-rounded text-[18px]">grid_view</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">{{ $seatingPlansCount ?? 0 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                            <span>Active Plans</span>
                        </div>
                    </div>
                    <!-- Stat Card 7 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Submissions</h3>
                            <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                                <span class="material-symbols-rounded text-[18px]">pending_actions</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-error">{{ $pendingSubmissionsCount ?? 0 }}</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-error">
                            <span>To be graded</span>
                        </div>
                    </div>
                    <!-- Stat Card 8 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Class Performance</h3>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                                <span class="material-symbols-rounded text-[18px]">trending_up</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">85%</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-medium text-emerald-700">
                            <span>Average Score</span>
                        </div>
                    </div>
                </div>

                <!-- Tables Section -->
                <div class="grid grid-cols-1 gap-md">
                    <!-- Table 1: Today's Classes -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Today's Schedule</h3>
                            <a href="{{ route('teacher.timetable') }}" class="text-primary text-label-md font-label-md hover:underline">View Timetable</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                        <th class="py-3 px-4 font-semibold">Time</th>
                                        <th class="py-3 px-4 font-semibold">Class</th>
                                        <th class="py-3 px-4 font-semibold">Subject</th>
                                        <th class="py-3 px-4 font-semibold">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-body-md font-body-md">
                                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                        <td class="py-3 px-4 text-secondary">08:00 AM - 09:00 AM</td>
                                        <td class="py-3 px-4 font-medium">Class X - A</td>
                                        <td class="py-3 px-4">Mathematics</td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('teacher.attendance') }}" class="inline-flex items-center gap-1 text-primary hover:underline font-medium text-sm">
                                                <span class="material-symbols-rounded text-[16px]">how_to_reg</span> Mark Attendance
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                        <td class="py-3 px-4 text-secondary">09:15 AM - 10:15 AM</td>
                                        <td class="py-3 px-4 font-medium">Class IX - B</td>
                                        <td class="py-3 px-4">Physics</td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('teacher.attendance') }}" class="inline-flex items-center gap-1 text-primary hover:underline font-medium text-sm">
                                                <span class="material-symbols-rounded text-[16px]">how_to_reg</span> Mark Attendance
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                {{-- S-01: Attendance Pattern Alert Card --}}
                @if(isset($currentMonthAbsentees) && $currentMonthAbsentees->count() > 0)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                    <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600">
                                <span class="material-symbols-rounded text-[22px]">warning</span>
                            </div>
                            <div>
                                <h3 class="text-headline-md font-headline-md text-on-surface">Attendance Alert — {{ now()->format('F Y') }}</h3>
                                <p class="text-body-md font-body-md text-secondary">Students with 3+ absences this month</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center justify-center px-3 py-1 rounded-full bg-amber-100 text-amber-800 text-label-md font-label-md">
                            {{ $currentMonthAbsentees->count() }} {{ $currentMonthAbsentees->count() === 1 ? 'Student' : 'Students' }}
                        </span>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                    <th class="py-3 px-4 font-semibold">#</th>
                                    <th class="py-3 px-4 font-semibold">Student Name</th>
                                    <th class="py-3 px-4 font-semibold">Class</th>
                                    <th class="py-3 px-4 font-semibold">Absences This Month</th>
                                </tr>
                            </thead>
                            <tbody class="text-body-md font-body-md">
                                @foreach($currentMonthAbsentees as $i => $row)
                                <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4 text-secondary">{{ $i + 1 }}</td>
                                    <td class="py-3 px-4 font-medium text-on-surface">{{ $row->first_name }} {{ $row->last_name }}</td>
                                    <td class="py-3 px-4 text-secondary">{{ $row->class_name ?? '—' }}</td>
                                    <td class="py-3 px-4">
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold {{ $row->absent_count >= 5 ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700' }}">
                                            <span class="material-symbols-rounded text-[14px]">event_busy</span>
                                            {{ $row->absent_count }} days
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                @endif
            </div>
        </main>
@endsection
