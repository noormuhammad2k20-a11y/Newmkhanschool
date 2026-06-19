@extends('layouts.app')

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full">
    <div class="max-w-[1440px] mx-auto">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
            <div>
                <h2 class="font-headline-lg text-headline-lg font-bold text-primary mb-xs">AI Student Risk Analysis</h2>
                <p class="font-body-md text-body-md text-secondary">Early warning system for academic and behavioral risks.</p>
            </div>
            <div class="flex gap-sm">
                <form action="{{ route('admin.ai.risk') }}" method="GET" class="flex gap-sm">
                    <select name="class_id" class="px-md py-sm rounded-lg border border-outline-variant bg-surface-container-lowest text-on-surface focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                        <option value="">All Classes</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ $selectedClassId == $class->id ? 'selected' : '' }}>{{ $class->name }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="px-lg py-sm rounded-lg bg-primary text-on-primary font-label-md hover:bg-primary-container transition-colors shadow-sm flex items-center gap-xs">
                        <span class="material-symbols-rounded text-[18px]">filter_alt</span>
                        Filter
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-xl">
            <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[28px]">warning</span>
                </div>
                <div>
                    <h3 class="font-body-md text-on-surface-variant">High Risk Students</h3>
                    <p class="font-headline-xl text-primary font-bold">{{ $highRisk }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-yellow-100 text-yellow-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[28px]">info</span>
                </div>
                <div>
                    <h3 class="font-body-md text-on-surface-variant">Medium Risk Students</h3>
                    <p class="font-headline-xl text-primary font-bold">{{ $mediumRisk }}</p>
                </div>
            </div>
            <div class="bg-surface-container-lowest p-lg rounded-xl border border-outline-variant shadow-sm flex items-center gap-md">
                <div class="w-12 h-12 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                    <span class="material-symbols-rounded text-[28px]">check_circle</span>
                </div>
                <div>
                    <h3 class="font-body-md text-on-surface-variant">Low Risk Students</h3>
                    <p class="font-headline-xl text-primary font-bold">{{ $lowRisk }}</p>
                </div>
            </div>
        </div>

        <!-- Risk Profiles Table -->
        <div class="bg-surface-container-lowest rounded-xl border border-outline-variant shadow-sm overflow-hidden">
            <div class="p-lg border-b border-outline-variant">
                <h3 class="font-headline-md text-headline-md font-semibold text-on-surface">Detailed Risk Profiles</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-on-surface-variant border-b border-outline-variant">
                            <th class="px-lg py-md font-label-md text-label-md font-semibold w-[20%]">Student Info</th>
                            <th class="px-lg py-md font-label-md text-label-md font-semibold w-[15%]">Risk Level</th>
                            <th class="px-lg py-md font-label-md text-label-md font-semibold w-[30%]">Contributing Factors</th>
                            <th class="px-lg py-md font-label-md text-label-md font-semibold w-[35%]">Recommended Interventions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse($riskProfiles as $profile)
                            <tr class="hover:bg-surface-container-low transition-colors border-b border-outline-variant last:border-0">
                                <td class="px-lg py-md align-top">
                                    <div class="font-body-md font-semibold text-on-surface">{{ $profile['student_name'] }}</div>
                                    <div class="text-xs text-on-surface-variant mt-0.5">
                                        {{ $profile['admission_no'] }} &bull; {{ $profile['class_section'] }}
                                    </div>
                                </td>
                                <td class="px-lg py-md align-top">
                                    <div class="flex flex-col gap-1 items-start">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $profile['risk_color'] }}">
                                            {{ $profile['risk_level'] }}
                                        </span>
                                        <span class="text-[11px] font-medium text-on-surface-variant">
                                            Score: {{ $profile['risk_score_numeric'] }}/100
                                        </span>
                                    </div>
                                </td>
                                <td class="px-lg py-md align-top">
                                    <ul class="list-disc pl-4 text-sm text-on-surface-variant space-y-1">
                                        @foreach($profile['factors'] as $factor)
                                            <li>{{ $factor }}</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td class="px-lg py-md align-top">
                                    <ul class="list-none text-sm text-on-surface space-y-1.5">
                                        @foreach($profile['interventions'] as $intervention)
                                            <li class="flex items-start gap-xs">
                                                <span class="material-symbols-rounded text-[16px] text-primary flex-shrink-0 mt-[2px]">lightbulb</span>
                                                <span class="leading-snug">{{ $intervention }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-xl text-center text-on-surface-variant font-body-md">
                                    No risk profiles available.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
