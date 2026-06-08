@extends('layouts.app')
@section('title', 'My Attendance')

@section('content')
<div class="row mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Present</h6><h3 class="text-success">{{ $stats['present'] }}</h3>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Absent</h6><h3 class="text-danger">{{ $stats['absent'] }}</h3>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Leave</h6><h3 class="text-warning">{{ $stats['leave'] }}</h3>
    </div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body text-center">
        <h6 class="text-muted">Percentage</h6><h3>{{ $stats['percentage'] }}%</h3>
    </div></div></div>
</div>

{{-- Month Navigation --}}
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <a href="?month={{ $month == 1 ? 12 : $month-1 }}&year={{ $month == 1 ? $year-1 : $year }}" class="btn btn-sm btn-outline-secondary">← Prev</a>
        <strong>{{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F Y') }}</strong>
        <a href="?month={{ $month == 12 ? 1 : $month+1 }}&year={{ $month == 12 ? $year+1 : $year }}" class="btn btn-sm btn-outline-secondary">Next →</a>
    </div>
    <div class="card-body">
        <div class="row text-center mb-2">
            @foreach(['Sun','Mon','Tue','Wed','Thu','Fri','Sat'] as $day)
                <div class="col"><strong>{{ $day }}</strong></div>
            @endforeach
        </div>
        <div class="row text-center">
            @for($i = 0; $i < $startDay; $i++)
                <div class="col"></div>
            @endfor
            @for($d = 1; $d <= $daysInMonth; $d++)
                @php
                    $dateKey = \Carbon\Carbon::createFromDate($year,$month,$d)->format('Y-m-d');
                    $record  = $records[$dateKey] ?? null;
                    $status  = $record ? $record->status : null;
                    $class   = match($status) { 'P'=>'bg-success text-white','A'=>'bg-danger text-white','L'=>'bg-warning','T'=>'bg-info text-white', default=>'' };
                @endphp
                <div class="col mb-1">
                    <span class="badge {{ $class }} d-block p-2">{{ $d }}<br><small>{{ $status ?? '-' }}</small></span>
                </div>
                @if((($d + $startDay) % 7 == 0) && $d < $daysInMonth)
                    </div><div class="row text-center">
                @endif
            @endfor
        </div>
    </div>
</div>

{{-- Leave Application --}}
<div class="card">
    <div class="card-header">Apply for Leave</div>
    <div class="card-body">
        <form method="POST" action="{{ route('student.leave.store') }}">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <label>Leave Type</label>
                    <select name="leave_type" class="form-control" required>
                        <option value="Sick Leave">Sick Leave</option>
                        <option value="Personal Leave">Personal Leave</option>
                        <option value="Family Emergency">Family Emergency</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label>Start Date</label>
                    <input type="date" name="start_date" class="form-control" required min="{{ today()->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label>End Date</label>
                    <input type="date" name="end_date" class="form-control" required min="{{ today()->toDateString() }}">
                </div>
                <div class="col-md-3">
                    <label>Reason</label>
                    <input type="text" name="reason" class="form-control" required maxlength="1000">
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit Leave Request</button>
        </form>
    </div>
</div>
@endsection
