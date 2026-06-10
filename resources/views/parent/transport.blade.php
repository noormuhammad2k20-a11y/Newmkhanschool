@extends('layouts.app')

@section('title', 'Transport Details')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-sm">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Transport Details</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Allocated transport routes for your children</p>
            </div>
        </div>

        @if(isset($transports) && count($transports) > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 gap-md mb-lg">
                @foreach($students as $student)
                    @if(isset($transports[$student->id]))
                        @php $transport = $transports[$student->id]->first(); @endphp
                        <div class="card p-0 flex flex-col group hover:border-primary transition-colors">
                            <div class="flex items-center justify-between p-md border-b border-outline-variant bg-surface-container-high">
                                <h3 class="font-headline-md text-headline-md text-on-surface flex items-center gap-2">
                                    <span class="material-symbols-outlined text-primary">person</span>
                                    {{ $student->first_name }} {{ $student->last_name }}
                                </h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-primary-container text-on-primary-container tracking-wide">
                                    {{ $transport->vehicle_number ?? 'Allocated' }}
                                </span>
                            </div>

                            <div class="p-md">
                                <div class="flex items-center gap-4 mb-6 pb-6 border-b border-outline-variant border-dashed">
                                    <div class="w-12 h-12 rounded-xl bg-primary-container flex items-center justify-center text-on-primary-container shadow-sm">
                                        <span class="material-symbols-outlined text-[24px]">directions_bus</span>
                                    </div>
                                    <div>
                                        <h2 class="text-headline-md font-headline-md text-on-surface">{{ $transport->route_name }}</h2>
                                        <div class="flex items-center gap-1 text-emerald-600 mt-1">
                                            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            <span class="text-label-md font-label-md">Active Route</span>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="space-y-6">
                                    <div>
                                        <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-3">Route Information</h3>
                                        <div class="bg-surface rounded-lg p-4 border border-outline-variant space-y-4">
                                            <div class="flex items-start gap-3 relative">
                                                <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-secondary z-10">
                                                    <span class="material-symbols-outlined text-[16px]">trip_origin</span>
                                                </div>
                                                <div class="absolute left-4 top-8 bottom-[-24px] w-0.5 bg-outline-variant border-dashed border-l-2"></div>
                                                <div>
                                                    <p class="text-label-md font-label-md text-on-surface">Start Point</p>
                                                    <p class="text-body-md font-body-md text-secondary mt-0.5">{{ $transport->start_point ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="flex items-start gap-3 relative">
                                                <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-secondary z-10">
                                                    <span class="material-symbols-outlined text-[16px]">location_on</span>
                                                </div>
                                                <div>
                                                    <p class="text-label-md font-label-md text-on-surface">End Point</p>
                                                    <p class="text-body-md font-body-md text-secondary mt-0.5">{{ $transport->end_point ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider mb-3">Driver Details</h3>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="bg-surface rounded-lg p-3 border border-outline-variant flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-secondary">
                                                    <span class="material-symbols-outlined text-[16px]">person</span>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Name</p>
                                                    <p class="text-body-md font-body-md text-on-surface">{{ $transport->driver_name ?? '-' }}</p>
                                                </div>
                                            </div>
                                            <div class="bg-surface rounded-lg p-3 border border-outline-variant flex items-center gap-3">
                                                <div class="w-8 h-8 rounded-full bg-surface-container-high flex items-center justify-center text-secondary">
                                                    <span class="material-symbols-outlined text-[16px]">phone</span>
                                                </div>
                                                <div>
                                                    <p class="text-xs text-secondary font-medium uppercase tracking-wide">Contact</p>
                                                    <p class="text-body-md font-body-md text-on-surface">{{ $transport->driver_contact ?? '-' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card border border-dashed border-outline-variant p-xl flex flex-col items-center justify-center text-center h-full min-h-[300px]">
                            <div class="w-16 h-16 rounded-full bg-surface-container-high flex items-center justify-center text-secondary mb-3">
                                <span class="material-symbols-outlined text-3xl">no_transfer</span>
                            </div>
                            <h3 class="font-headline-md text-headline-md text-on-surface">{{ $student->first_name }} {{ $student->last_name }}</h3>
                            <p class="font-body-md text-body-md text-secondary mt-1">No transport route allocated</p>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
        <div class="card p-xl text-center mb-lg">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-high mb-4 text-secondary">
                <span class="material-symbols-outlined text-3xl">no_transfer</span>
            </div>
            <h3 class="font-headline-md text-headline-md text-on-surface">No Transport Allocated</h3>
            <p class="font-body-md text-body-md text-secondary mt-1">None of your children have been allocated to a school transport route.</p>
        </div>
        @endif
    </div>
</main>
@endsection
