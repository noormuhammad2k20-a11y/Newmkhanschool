@extends('layouts.app')

@section('title', 'Transport Details')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Transport Details</h1>
    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Allocated transport routes for your children</p>
</div>

@if(isset($transports) && count($transports) > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($students as $student)
            @if(isset($transports[$student->id]))
                @php $transport = $transports[$student->id]->first(); @endphp
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                            {{ $transport->vehicle_number ?? 'Transport Allocated' }}
                        </span>
                    </div>

                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                            <span class="material-symbols-rounded text-2xl">directions_bus</span>
                        </div>
                        <div>
                            <h2 class="font-bold text-gray-900 dark:text-white">{{ $transport->route_name }}</h2>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Route Information</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-rounded text-gray-400 mt-0.5 text-sm">trip_origin</span>
                                    <div>
                                        <p class="text-xs font-medium text-gray-900 dark:text-white">Start Point</p>
                                        <p class="text-xs text-gray-500">{{ $transport->start_point ?? '-' }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-rounded text-gray-400 mt-0.5 text-sm">location_on</span>
                                    <div>
                                        <p class="text-xs font-medium text-gray-900 dark:text-white">End Point</p>
                                        <p class="text-xs text-gray-500">{{ $transport->end_point ?? '-' }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Driver Details</h3>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-rounded text-gray-400 mt-0.5 text-sm">person</span>
                                    <div>
                                        <p class="text-xs font-medium text-gray-900 dark:text-white">Name</p>
                                        <p class="text-xs text-gray-500">{{ $transport->driver_name ?? '-' }}</p>
                                    </div>
                                </li>
                                <li class="flex items-start gap-2">
                                    <span class="material-symbols-rounded text-gray-400 mt-0.5 text-sm">phone</span>
                                    <div>
                                        <p class="text-xs font-medium text-gray-900 dark:text-white">Contact</p>
                                        <p class="text-xs text-gray-500">{{ $transport->driver_contact ?? '-' }}</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-dashed border-gray-300 dark:border-gray-700 p-6 flex flex-col items-center justify-center text-center h-full min-h-[250px]">
                    <span class="material-symbols-rounded text-4xl text-gray-400 mb-2">no_transfer</span>
                    <h3 class="font-medium text-gray-900 dark:text-white">{{ $student->first_name }} {{ $student->last_name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">No transport route allocated</p>
                </div>
            @endif
        @endforeach
    </div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
        <span class="material-symbols-rounded text-3xl">no_transfer</span>
    </div>
    <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Transport Allocated</h3>
    <p class="text-gray-500 dark:text-gray-400 mt-1">None of your children have been allocated to a school transport route.</p>
</div>
@endif
@endsection
