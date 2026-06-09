@extends('layouts.app')

@section('title', 'Promotion Rules Setup')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Promotion Rules Setup</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Configure criteria for class-to-class promotions</p>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-2 px-4 py-2 bg-surface-container border border-outline-variant rounded-lg text-label-md font-label-md text-on-surface hover:bg-surface-container-high transition-colors">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Back to Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
            <!-- Left Column: Add/Edit Rule -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden lg:col-span-1 flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Add / Update Rule</h3>
                    <p class="text-xs text-secondary mt-1">For Academic Year: {{ $academicYear ? ($academicYear->start_date . ' to ' . $academicYear->end_date) : 'N/A' }}</p>
                </div>
                <div class="p-md flex-1">
                    @if($academicYear)
                        <form action="{{ route('admin.promotions.rules.save') }}" method="POST">
                            @csrf
                            <input type="hidden" name="academic_year_id" value="{{ $academicYear->id }}">
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-label-sm font-label-sm text-secondary mb-1">From Class</label>
                                    <select name="from_class_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" required>
                                        <option value="">-- Select Class --</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label class="block text-label-sm font-label-sm text-secondary mb-1">To Class</label>
                                    <select name="to_class_id" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" required>
                                        <option value="">-- Select Target Class --</option>
                                        @foreach($classes as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Minimum Percentage (%)</label>
                                    <input type="number" name="min_percentage" min="0" max="100" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" placeholder="e.g. 40" required>
                                </div>

                                <div>
                                    <label class="block text-label-sm font-label-sm text-secondary mb-1">Minimum Attendance (%)</label>
                                    <input type="number" name="min_attendance_pct" min="0" max="100" class="w-full bg-surface-container border border-outline-variant rounded-lg px-3 py-2 text-body-md text-on-surface focus:outline-none focus:border-primary" placeholder="e.g. 75" required>
                                </div>

                                <button type="submit" class="w-full py-2 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:bg-primary-hover shadow-sm transition-colors mt-4">
                                    Save Rule
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-8 text-secondary">
                            <span class="material-symbols-outlined text-4xl mb-2 opacity-50">event_busy</span>
                            <p>No active academic year found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Column: Current Rules -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden lg:col-span-2 flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Configured Rules</h3>
                    <p class="text-xs text-secondary mt-1">Rules applied during preview and bulk promotion</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                                <th class="py-3 px-4 font-semibold">From Class</th>
                                <th class="py-3 px-4 font-semibold">To Class</th>
                                <th class="py-3 px-4 font-semibold">Min Percentage</th>
                                <th class="py-3 px-4 font-semibold">Min Attendance</th>
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
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-12 text-center text-secondary">
                                        <div class="flex flex-col items-center justify-center">
                                            <span class="material-symbols-outlined text-4xl mb-3 opacity-50">rule_settings</span>
                                            <p class="text-lg font-medium text-on-surface mb-1">No rules configured</p>
                                            <p class="text-sm">Use the form to set minimum passing criteria for each class.</p>
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
