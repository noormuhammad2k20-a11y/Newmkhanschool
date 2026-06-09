@extends('layouts.app')

@section('title', 'Student Promotions')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Student Promotions</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage academic year transitions and class promotions</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.promotions.rules') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">rule_settings</span>
                    Promotion Rules
                </a>
                <button class="flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors">
                    <span class="material-symbols-outlined text-[18px]">history</span>
                    Promotion History
                </button>
            </div>
        </div>

        <!-- Stats Grid (4 cards) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            <!-- Stat Card 1 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Eligible Students</h3>
                    <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-outlined text-[18px]">school</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">342</span>
                </div>
                <div class="mt-2 text-xs font-medium text-secondary flex items-center gap-1">
                    Based on current rules
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 2 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending Promotions</h3>
                    <div class="w-8 h-8 rounded-lg bg-orange-100 flex items-center justify-center text-orange-700">
                        <span class="material-symbols-outlined text-[18px]">hourglass_empty</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">128</span>
                </div>
                <div class="mt-2 text-xs font-medium text-orange-700 flex items-center gap-1">
                    Awaiting approval
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-orange-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 3 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Promoted</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">verified</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">210</span>
                </div>
                <div class="mt-2 text-xs font-medium text-emerald-700 flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">check_circle</span> Successfully processed
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <!-- Stat Card 4 -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Retained</h3>
                    <div class="w-8 h-8 rounded-lg bg-red-100 flex items-center justify-center text-red-700">
                        <span class="material-symbols-outlined text-[18px]">block</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-lg font-headline-lg text-on-surface">14</span>
                </div>
                <div class="mt-2 text-xs font-medium text-red-700 flex items-center gap-1">
                    Did not meet criteria
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-red-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Workflow Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Left Column: Initiate Promotion -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden lg:col-span-1 flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Initiate Promotion</h3>
                    <p class="text-xs text-secondary mt-1">Select source class to preview results</p>
                </div>
                <div class="p-md flex-1">
                    <form action="{{ route('admin.promotions.preview') }}" method="POST">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label class="block text-label-sm font-label-sm text-secondary mb-1">Academic Year</label>
                                <select name="academic_year_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" required>
                                    @foreach($academicYears as $year)
                                        <option value="{{ $year->id }}" {{ ($activeYear && $activeYear->id == $year->id) ? 'selected' : '' }}>
                                            {{ $year->start_date }} to {{ $year->end_date }}
                                            {{ $year->is_active ? '(Active)' : '' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-label-sm font-label-sm text-secondary mb-1">Promote From Class</label>
                                <select name="class_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" required>
                                    <option value="">-- Select Class --</option>
                                    @foreach($classes as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="bg-surface-variant p-4 rounded-lg mt-4">
                                <h4 class="text-label-md font-label-md text-on-surface-variant flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">info</span>
                                    How it works
                                </h4>
                                <p class="text-xs text-secondary mt-2 leading-relaxed">
                                    Selecting a class will preview the students eligible for promotion based on the active rules. You can review the list and approve the bulk promotion to the next class level.
                                </p>
                            </div>

                            <button type="submit" class="w-full py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors mt-4">
                                Preview Promotion
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Column: Active Rules Summary -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden lg:col-span-2 flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                    <div>
                        <h3 class="text-headline-md font-headline-md text-on-surface">Active Promotion Rules</h3>
                        <p class="text-xs text-secondary mt-1">Current criteria for the active academic year</p>
                    </div>
                    <a href="{{ route('admin.promotions.rules') }}" class="text-primary text-label-md font-label-md hover:underline">Manage Rules</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                <th class="py-3 px-4 font-semibold">From Class</th>
                                <th class="py-3 px-4 font-semibold">To Class</th>
                                <th class="py-3 px-4 font-semibold">Min Percentage</th>
                                <th class="py-3 px-4 font-semibold">Min Attendance</th>
                                <th class="py-3 px-4 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="text-body-md font-body-md">
                            @forelse($rules as $rule)
                                <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-3 px-4 text-on-surface font-medium">{{ $rule->fromClass->name }}</td>
                                    <td class="py-3 px-4 text-on-surface font-medium">{{ $rule->toClass->name }}</td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 h-2 bg-surface-variant rounded-full overflow-hidden">
                                                <div class="h-full bg-primary" style="width: {{ $rule->min_percentage }}%"></div>
                                            </div>
                                            <span class="text-secondary text-xs">{{ $rule->min_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4">
                                        <div class="flex items-center gap-2">
                                            <div class="w-16 h-2 bg-surface-variant rounded-full overflow-hidden">
                                                <div class="h-full bg-emerald-500" style="width: {{ $rule->min_attendance_pct }}%"></div>
                                            </div>
                                            <span class="text-secondary text-xs">{{ $rule->min_attendance_pct }}%</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-4 text-center">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Active</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-secondary">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">rule_settings</span>
                                            <p>No promotion rules defined for this academic year.</p>
                                            <a href="{{ route('admin.promotions.rules') }}" class="text-primary hover:underline mt-2">Setup Rules</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
