@extends('layouts.app')

@section('title', 'Fee Structure Configuration')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Breadcrumb & Page Header -->
        <div class="flex flex-col gap-2">
            <nav class="flex text-label-md text-secondary" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="{{ route('accountant.dashboard') }}" class="inline-flex items-center hover:text-primary transition-colors">
                            <span class="material-symbols-rounded text-[16px] mr-1">home</span>
                            Accountant Portal
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Fee Management</span>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <span class="material-symbols-rounded text-[16px] mx-1">chevron_right</span>
                            <span class="text-on-surface">Fee Structure</span>
                        </div>
                    </li>
                </ol>
            </nav>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-headline-xl font-headline-xl text-on-surface">Fee Structure</h2>
                    <p class="text-body-lg font-body-lg text-secondary mt-1">Configure base fee amounts for different classes and categories</p>
                </div>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 relative flex items-center gap-3" role="alert">
            <span class="material-symbols-rounded text-emerald-600">check_circle</span>
            <div><span class="font-semibold">Success!</span> {{ session('success') }}</div>
        </div>
        @endif

        @if($errors->any())
        <div class="p-4 mb-4 text-sm text-error rounded-xl bg-error-10 border border-error-container relative flex items-center gap-3" role="alert">
            <span class="material-symbols-rounded text-error">error</span>
            <div><span class="font-semibold">Error!</span> Please check the validation issues.</div>
        </div>
        @endif

        <!-- Table Section -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-xs font-semibold text-secondary uppercase tracking-wider border-b border-outline-variant">
                            <th class="py-4 px-6">Class</th>
                            <th class="py-4 px-6">Fee Category</th>
                            <th class="py-4 px-6 text-right">Default Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md divide-y divide-outline-variant">
                        @forelse($structures as $structure)
                        <tr class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-surface-variant text-on-surface-variant flex items-center justify-center border border-outline-variant">
                                        <span class="material-symbols-rounded text-[20px]">meeting_room</span>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-on-surface text-body-lg">{{ $structure->class->name ?? 'N/A' }}</div>
                                        <div class="text-xs text-secondary mt-0.5">
                                            {{ is_null($structure->school_id) ? 'Global Structure' : 'School Specific' }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 text-secondary">
                                @php
                                    $catName = $structure->category->name ?? 'General';
                                    $catLower = strtolower($catName);
                                    $badgeClass = 'bg-slate-50 text-slate-700 border-slate-200'; // Default
                                    
                                    if (str_contains($catLower, 'tuition')) {
                                        $badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                                    } elseif (str_contains($catLower, 'transport')) {
                                        $badgeClass = 'bg-purple-50 text-purple-700 border-purple-200';
                                    } elseif (str_contains($catLower, 'library')) {
                                        $badgeClass = 'bg-indigo-50 text-indigo-700 border-indigo-200';
                                    } elseif (str_contains($catLower, 'exam') || str_contains($catLower, 'test')) {
                                        $badgeClass = 'bg-orange-50 text-orange-700 border-orange-200';
                                    } elseif (str_contains($catLower, 'admission')) {
                                        $badgeClass = 'bg-teal-50 text-teal-700 border-teal-200';
                                    } elseif (str_contains($catLower, 'sport') || str_contains($catLower, 'activity')) {
                                        $badgeClass = 'bg-pink-50 text-pink-700 border-pink-200';
                                    } elseif (str_contains($catLower, 'lab') || str_contains($catLower, 'computer')) {
                                        $badgeClass = 'bg-cyan-50 text-cyan-700 border-cyan-200';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-semibold border {{ $badgeClass }}">
                                    {{ $catName }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <div class="font-bold text-on-surface text-body-lg">{{ number_format($structure->amount, 2) }}</div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="py-16 text-center text-secondary">
                                <span class="material-symbols-rounded text-5xl mb-3 text-outline">account_tree</span>
                                <p class="text-body-lg font-medium text-on-surface">No fee structures configured yet</p>
                                <p class="text-body-md mt-1">Please ask the administrator to configure fee structures from the Admin Portal.</p>
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
