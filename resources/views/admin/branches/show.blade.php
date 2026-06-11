@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-xl gap-4">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <div class="w-10 h-10 rounded-lg bg-primary-container text-on-primary-container flex items-center justify-center">
                    <span class="material-symbols-outlined">domain</span>
                </div>
                <h2 class="text-headline-lg font-headline-lg text-primary">{{ $branch->name }}</h2>
                @if(session('active_branch_id') == $branch->id)
                    <span class="bg-emerald-100 text-emerald-800 text-xs font-bold px-2.5 py-0.5 rounded border border-emerald-200">Active Context</span>
                @endif
            </div>
            <p class="text-body-md text-secondary ml-[52px]">Branch Code: <span class="font-mono text-on-surface">{{ $branch->branch_code ?? 'N/A' }}</span> | Principal: <span class="font-medium text-on-surface">{{ $branch->principal_name ?? 'N/A' }}</span></p>
        </div>
        <div class="flex flex-wrap items-center gap-sm">
            <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 text-secondary hover:text-on-surface hover:bg-surface-container-low rounded-lg transition-colors flex items-center gap-2 font-label-md">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span> Back
            </a>
            <a href="{{ route('admin.branches.edit', $branch->id) }}" class="px-4 py-2 border border-outline-variant text-on-surface rounded-lg font-label-md hover:bg-surface-container-low transition-colors flex items-center gap-2 bg-surface-container-lowest">
                <span class="material-symbols-outlined text-[18px]">edit</span> Edit Branch
            </a>
            <form method="POST" action="{{ route('admin.branches.switch') }}" class="m-0">
                @csrf
                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-2 shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">swap_horiz</span> Switch Context
                </button>
            </form>
        </div>
    </div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md mb-xl">
        <!-- Stat Card 1 -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Students</h3>
                <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($stats['students']) }}</span>
            </div>
            <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                <span>Enrolled in this branch</span>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Teachers</h3>
                <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined text-[18px]">school</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($stats['teachers']) }}</span>
            </div>
            <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                <span>Assigned to this branch</span>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Revenue (Month)</h3>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                    <span class="material-symbols-outlined text-[18px]">payments</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-on-surface">${{ number_format($stats['revenue_this_month'], 2) }}</span>
            </div>
            <div class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-700">
                <span class="material-symbols-outlined text-[14px]">trending_up</span>
                <span>Paid fees</span>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
        </div>

        <!-- Stat Card 4 -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default shadow-sm">
            <div class="flex justify-between items-start mb-4">
                <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Attendance (Today)</h3>
                <div class="w-8 h-8 rounded-lg bg-blue-100 flex items-center justify-center text-blue-700">
                    <span class="material-symbols-outlined text-[18px]">rule</span>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-headline-xl font-headline-xl text-on-surface">{{ number_format($stats['attendance_today']) }}</span>
            </div>
            <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                <span>Present students</span>
            </div>
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden shadow-sm">
        <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
            <span class="material-symbols-outlined text-secondary">info</span>
            <h3 class="text-headline-md font-headline-md text-on-surface">Branch Overview</h3>
        </div>
        <div class="p-lg grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-12 text-body-md">
            
            <div class="flex gap-4 items-start">
                <div class="mt-1 text-secondary">
                    <span class="material-symbols-outlined text-[20px]">email</span>
                </div>
                <div>
                    <span class="text-secondary block text-label-sm uppercase tracking-wider mb-1">Email Address</span>
                    <span class="text-on-surface font-medium text-body-lg">{{ $branch->email ?? 'Not Provided' }}</span>
                </div>
            </div>

            <div class="flex gap-4 items-start">
                <div class="mt-1 text-secondary">
                    <span class="material-symbols-outlined text-[20px]">call</span>
                </div>
                <div>
                    <span class="text-secondary block text-label-sm uppercase tracking-wider mb-1">Contact Number</span>
                    <span class="text-on-surface font-medium text-body-lg">{{ $branch->phone ?? 'Not Provided' }}</span>
                </div>
            </div>

            <div class="flex gap-4 items-start md:col-span-2">
                <div class="mt-1 text-secondary">
                    <span class="material-symbols-outlined text-[20px]">location_on</span>
                </div>
                <div>
                    <span class="text-secondary block text-label-sm uppercase tracking-wider mb-1">Physical Address</span>
                    <span class="text-on-surface font-medium text-body-lg">{{ $branch->address ?? 'No Address' }}{{ $branch->city ? ', ' . $branch->city : '' }}</span>
                </div>
            </div>

            <div class="flex gap-4 items-start md:col-span-2 pt-4 border-t border-outline-variant border-opacity-50">
                <div class="mt-1 text-secondary">
                    <span class="material-symbols-outlined text-[20px]">image</span>
                </div>
                <div>
                    <span class="text-secondary block text-label-sm uppercase tracking-wider mb-3">Branch Logo</span>
                    @if($branch->logo)
                        <div class="bg-surface border border-outline-variant p-2 rounded-lg inline-block shadow-sm">
                            <img src="{{ Storage::url($branch->logo) }}" alt="Logo" class="h-24 w-24 object-contain rounded">
                        </div>
                    @else
                        <div class="w-24 h-24 bg-surface-container-low border border-dashed border-outline-variant rounded-lg flex flex-col items-center justify-center text-secondary">
                            <span class="material-symbols-outlined text-[24px] mb-1">hide_image</span>
                            <span class="text-xs">No Logo</span>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
