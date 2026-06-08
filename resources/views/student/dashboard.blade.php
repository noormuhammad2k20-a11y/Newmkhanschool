@extends('layouts.app')
@section('title', 'Student Dashboard')

@section('content')
<div class="row">
    {{-- Attendance Card --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Attendance</h6>
                <h3>{{ $attendancePct }}%</h3>
                <small class="text-muted">{{ $presentDays }} / {{ $totalDays }} days</small>
            </div>
        </div>
    </div>

    {{-- Pending Fees Card --}}
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <h6 class="text-muted">Pending Fees</h6>
                <h3>{{ number_format($pendingFees, 2) }}</h3>
                <a href="{{ route('student.fees') }}" class="btn btn-sm btn-outline-primary mt-2">View Details</a>
            </div>
        </div>
    </div>

    {{-- Today's Classes Card --}}
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Today's Timetable</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead><tr><th>Time</th><th>Subject</th><th>Teacher</th></tr></thead>
                    <tbody>
                    @forelse($todayClasses as $period)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($period->start_time)->format('h:i A') }}</td>
                            <td>{{ $period->subjectRef->name ?? $period->subject }}</td>
                            <td>{{ $period->teacher->full_name ?? $period->teacher }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted">No classes today</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Announcements --}}
<div class="row mt-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Recent Announcements</div>
            <div class="card-body">
                @forelse($announcements as $ann)
                    <div class="border-bottom pb-2 mb-2">
                        <strong>{{ $ann->title }}</strong>
                        <p class="text-muted small mb-0">{{ Str::limit($ann->content, 120) }}</p>
                        <small class="text-muted">{{ $ann->created_at->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="text-muted">No announcements yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Upcoming Exams --}}
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">Upcoming Exams</div>
            <div class="card-body">
                @forelse($upcomingExams as $exam)
                    <div class="border-bottom pb-2 mb-2">
                        <strong>{{ $exam->subjectRef->name ?? $exam->subject }}</strong>
                        <br>
                        <small class="text-muted">
                            {{ \Carbon\Carbon::parse($exam->exam_date)->format('d M Y') }}
                            @if($exam->exam_time) · {{ $exam->exam_time }} @endif
                        </small>
                    </div>
                @empty
                    <p class="text-muted small">No upcoming exams.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
