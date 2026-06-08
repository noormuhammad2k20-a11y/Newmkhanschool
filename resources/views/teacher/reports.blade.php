@extends('layouts.app')

@section('title', 'Reports')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Reports generation</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">Generate and export attendance and marks reports.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-xl">
            <!-- Attendance Report -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
                <div class="flex items-center gap-3 mb-6 border-b border-outline-variant pb-4">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined">how_to_reg</span>
                    </div>
                    <h3 class="text-headline-sm font-headline-sm text-on-surface">Attendance Report</h3>
                </div>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Select Class</label>
                        <select class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Choose a Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">From Date</label>
                            <input type="date" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        </div>
                        <div>
                            <label class="block text-label-md text-on-surface mb-1">To Date</label>
                            <input type="date" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        </div>
                    </div>
                    <div class="pt-4 flex gap-2">
                        <button type="button" class="flex-1 bg-surface-variant text-on-surface border border-outline-variant rounded py-2 text-label-md font-label-md hover:bg-surface-container-low flex items-center justify-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">visibility</span> View
                        </button>
                        <button type="button" class="flex-1 bg-primary text-on-primary rounded py-2 text-label-md font-label-md hover:bg-primary-dark flex items-center justify-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">download</span> PDF Export
                        </button>
                    </div>
                </form>
            </div>

            <!-- Marks Report -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
                <div class="flex items-center gap-3 mb-6 border-b border-outline-variant pb-4">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700">
                        <span class="material-symbols-outlined">grade</span>
                    </div>
                    <h3 class="text-headline-sm font-headline-sm text-on-surface">Marks & Grades Report</h3>
                </div>
                
                <form class="space-y-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Select Class</label>
                        <select class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Choose a Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Select Exam Type</label>
                        <select class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="Mid-Term">Mid-Term Exam</option>
                            <option value="Final-Term">Final-Term Exam</option>
                            <option value="Unit-Test">Monthly Unit Test</option>
                        </select>
                    </div>
                    <div class="pt-4 flex gap-2">
                        <button type="button" class="flex-1 bg-surface-variant text-on-surface border border-outline-variant rounded py-2 text-label-md font-label-md hover:bg-surface-container-low flex items-center justify-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">visibility</span> View
                        </button>
                        <button type="button" class="flex-1 bg-emerald-600 text-white rounded py-2 text-label-md font-label-md hover:bg-emerald-700 flex items-center justify-center gap-2 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">table_chart</span> Excel Export
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection
