@extends('layouts.app')

@section('title', 'Transport Details')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transport Details</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Your allocated transport route and schedule</p>
</div>

@if(isset($transport) && $transport)
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
    <div class="flex items-center gap-4 mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
        <div class="w-16 h-16 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
            <span class="material-symbols-rounded text-3xl">directions_bus</span>
        </div>
        <div>
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">{{ $transport->route_name }}</h2>
            <p class="text-gray-500 dark:text-gray-400">{{ $transport->vehicle_number ?? 'Vehicle Details Not Provided' }}</p>
        </div>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Route Information</h3>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400 mt-0.5">trip_origin</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Start Point</p>
                        <p class="text-sm text-gray-500">{{ $transport->start_point ?? '-' }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400 mt-0.5">location_on</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">End Point</p>
                        <p class="text-sm text-gray-500">{{ $transport->end_point ?? '-' }}</p>
                    </div>
                </li>
            </ul>
        </div>
        <div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3">Driver Details</h3>
            <ul class="space-y-3">
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400 mt-0.5">person</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Driver Name</p>
                        <p class="text-sm text-gray-500">{{ $transport->driver_name ?? '-' }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="material-symbols-rounded text-gray-400 mt-0.5">phone</span>
                    <div>
                        <p class="text-sm font-medium text-gray-900 dark:text-white">Driver Contact</p>
                        <p class="text-sm text-gray-500">{{ $transport->driver_contact ?? '-' }}</p>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
        <span class="material-symbols-rounded text-3xl">no_transfer</span>
    </div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Transport Allocated</h3>
    <p class="text-gray-500 dark:text-gray-400 mt-1">You have not been allocated to any school transport route.</p>
</div>
@endif
@endsection
