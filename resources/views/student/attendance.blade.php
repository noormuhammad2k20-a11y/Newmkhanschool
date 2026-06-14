@extends('layouts.app')
@section('title', 'My Attendance')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        {{-- Page Header --}}
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">My Attendance</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">View your attendance record and apply for leave.</p>
        </div>

{{-- Stats Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Present --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Present</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">check_circle</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $stats['present'] }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Days Attended</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Absent --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-error transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Absent</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[18px]">cancel</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $stats['absent'] }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Days Missed</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Leave --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-secondary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Leave</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">flight_takeoff</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $stats['leave'] }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-secondary">
                    <span>Approved Leaves</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Percentage --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-emerald-600 transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Percentage</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">analytics</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $stats['percentage'] }}%</span>
                </div>
                <div class="mt-2 flex items-center gap-1 text-xs font-medium text-emerald-700">
                    <span class="material-symbols-outlined text-[14px]">trending_up</span>
                    <span>Overall Attendance</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        {{-- Leave Application --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright">
                <h3 class="text-headline-md font-headline-md text-on-surface">Apply for Leave</h3>
            </div>
            <div class="p-xl">
                <form method="POST" action="{{ route('student.leave.store') }}" class="grid grid-cols-1 md:grid-cols-4 gap-md items-end">
                    @csrf
                    <div class="flex flex-col gap-1">
                        <label class="text-label-md font-label-md text-secondary">Leave Type</label>
                        <div class="relative">
                            <select name="leave_type" class="w-full py-2 px-3 border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none appearance-none pr-10" required>
                                <option value="Sick Leave">Sick Leave</option>
                                <option value="Personal Leave">Personal Leave</option>
                                <option value="Family Emergency">Family Emergency</option>
                                <option value="Other">Other</option>
                            </select>
                            <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-secondary pointer-events-none text-[20px]">expand_more</span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-label-md font-label-md text-secondary">Start Date</label>
                        <input type="date" name="start_date" class="w-full py-2 px-3 border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none" required min="{{ today()->toDateString() }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-label-md font-label-md text-secondary">End Date</label>
                        <input type="date" name="end_date" class="w-full py-2 px-3 border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none" required min="{{ today()->toDateString() }}">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-label-md font-label-md text-secondary">Reason</label>
                        <input type="text" name="reason" class="w-full py-2 px-3 border border-outline-variant rounded-lg bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none" required maxlength="1000" placeholder="Why are you taking leave?">
                    </div>
                    <div class="md:col-span-4 mt-2">
                        <button type="submit" class="py-2 px-6 bg-primary text-on-primary rounded-lg text-label-md font-label-md hover:opacity-90 transition-opacity w-full md:w-auto shadow-sm">Submit Leave Request</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Month Navigation and Calendar --}}
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                <a href="?month={{ $month == 1 ? 12 : $month-1 }}&year={{ $month == 1 ? $year-1 : $year }}" class="py-1 px-3 border border-outline-variant rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container-low transition-colors flex items-center gap-1">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    Prev
                </a>
                <strong class="text-headline-md font-headline-md text-on-surface">{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</strong>
                <a href="?month={{ $month == 12 ? 1 : $month+1 }}&year={{ $month == 12 ? $year+1 : $year }}" class="py-1 px-3 border border-outline-variant rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container-low transition-colors flex items-center gap-1">
                    Next
                    <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>
            <div class="p-xl">
                <div class="grid grid-cols-7 text-center mb-4">
                    @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                        <div class="text-label-md font-label-md font-bold text-secondary uppercase">{{ $day }}</div>
                    @endforeach
                </div>
                <div class="grid grid-cols-7 text-center gap-2">
                    @for($i = 0; $i < $startDay; $i++)
                        <div class="p-2"></div>
                    @endfor
                    @for($d = 1; $d <= $daysInMonth; $d++)
                        @php
                            $dateKey = \Carbon\Carbon::createFromDate($year,$month,$d)->format('Y-m-d');
                            $record  = $records[$dateKey] ?? null;
                            $status  = $record ? $record->status : null;
                            
                            $bgColor = 'bg-surface-container-lowest';
                            $textColor = 'text-on-surface';
                            $label = '-';
                            $borderColor = 'border-outline-variant/30';
                            
                            if ($status === 'P') {
                                $bgColor = 'bg-primary-fixed';
                                $textColor = 'text-primary';
                                $label = 'Present';
                                $borderColor = 'border-primary/20';
                            } elseif ($status === 'A') {
                                $bgColor = 'bg-error-container';
                                $textColor = 'text-error';
                                $label = 'Absent';
                                $borderColor = 'border-error/20';
                            } elseif ($status === 'L') {
                                $bgColor = 'bg-secondary-container';
                                $textColor = 'text-on-secondary-container';
                                $label = 'Leave';
                                $borderColor = 'border-secondary/20';
                            } elseif ($status === 'T') {
                                $bgColor = 'bg-emerald-100';
                                $textColor = 'text-emerald-800';
                                $label = 'Late';
                                $borderColor = 'border-emerald-200';
                            }

                            $hasExam = \App\Models\ExamSchedule::where('class_id', auth()->user()->student->current_class_id)->whereDate('exam_date', $dateKey)->exists();
                        @endphp
                        <div class="relative flex flex-col items-center justify-center p-3 rounded-lg {{ $bgColor }} min-h-[80px] border {{ $borderColor }} shadow-sm">
                            @if($hasExam)
                                <div class="absolute top-1 right-1 text-[10px] bg-orange-100 text-orange-800 px-1 rounded flex items-center border border-orange-200" title="Physical Exam Day">
                                    <span class="material-symbols-outlined text-[10px]">campaign</span>
                                </div>
                            @endif
                            <span class="text-title-lg font-title-lg font-bold {{ $textColor }}">{{ $d }}</span>
                            <span class="text-label-sm font-label-sm {{ $textColor }} opacity-80 mt-1">{{ $label }}</span>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
