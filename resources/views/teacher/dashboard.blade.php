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
                                <span class="material-symbols-outlined text-[18px]">class</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">4</span>
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
                                <span class="material-symbols-outlined text-[18px]">groups</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">128</span>
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
                                <span class="material-symbols-outlined text-[18px]">assignment_turned_in</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">12</span>
                        </div>
                        <div class="mt-2 flex items-center gap-1 text-xs font-medium text-error">
                            <span class="material-symbols-outlined text-[14px]">priority_high</span>
                            <span>Needs grading</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-surface-variant rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                    <!-- Stat Card 4 -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Today's Timetable</h3>
                            <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                                <span class="material-symbols-outlined text-[18px]">schedule</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-2">
                            <span class="text-headline-xl font-headline-xl text-on-surface">3</span>
                        </div>
                        <div class="mt-2 flex items-center gap-2 text-xs font-medium text-secondary">
                            <span>Periods scheduled today</span>
                        </div>
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
                    </div>
                </div>

                <!-- Tables Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
                    <!-- Table 1: Today's Classes -->
                    <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
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
                                                <span class="material-symbols-outlined text-[16px]">how_to_reg</span> Mark Attendance
                                            </a>
                                        </td>
                                    </tr>
                                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                        <td class="py-3 px-4 text-secondary">09:15 AM - 10:15 AM</td>
                                        <td class="py-3 px-4 font-medium">Class IX - B</td>
                                        <td class="py-3 px-4">Physics</td>
                                        <td class="py-3 px-4">
                                            <a href="{{ route('teacher.attendance') }}" class="inline-flex items-center gap-1 text-primary hover:underline font-medium text-sm">
                                                <span class="material-symbols-outlined text-[16px]">how_to_reg</span> Mark Attendance
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Table 2: Recent Announcements -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
                        <div class="p-md border-b border-outline-variant bg-surface-bright">
                            <h3 class="text-headline-md font-headline-md text-on-surface">Announcements</h3>
                        </div>
                        <div class="p-md flex-1">
                            <ul class="space-y-4">
                                <li class="pb-3 border-b border-outline-variant border-opacity-50">
                                    <p class="font-medium text-body-md text-on-surface">Mid-Term Exams Schedule</p>
                                    <p class="text-xs text-secondary mt-0.5">Please submit your question papers by Friday.</p>
                                </li>
                                <li class="pb-3 border-b border-outline-variant border-opacity-50">
                                    <p class="font-medium text-body-md text-on-surface">Staff Meeting</p>
                                    <p class="text-xs text-secondary mt-0.5">Mandatory staff meeting today at 3:00 PM in the Main Hall.</p>
                                </li>
                            </ul>
                            <a href="{{ route('teacher.announcements') }}" class="block text-center w-full mt-4 py-2 border border-outline-variant rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container-low transition-colors">View All Notices</a>
                        </div>
                    </div>
                </div>
            </div>
        </main>
@endsection
