@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="fas fa-robot text-primary"></i> Academic Promotion Intelligence System</h2>
        
        @if(!$isWindowActive)
            <span class="badge bg-warning text-dark fs-6">Promotion Window: INACTIVE</span>
        @else
            <span class="badge bg-success fs-6">Promotion Window: ACTIVE</span>
        @endif
    </div>

    @if(!$isWindowActive)
    <div class="alert alert-info">
        <h5><i class="fas fa-info-circle"></i> Info</h5>
        <p>The academic cycle rules indicate that the promotion window is not currently active. Auto-batch generation is paused until the configured months.</p>
    </div>
    @else
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h5>Total Analyzed</h5>
                        <h3>{{ $metrics['total_analyzed'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h5>Auto Eligible</h5>
                        <h3>{{ $metrics['eligible'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark">
                    <div class="card-body">
                        <h5>Conditional Cases</h5>
                        <h3>{{ $metrics['conditional'] }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h5>Defaulters</h5>
                        <h3>{{ $metrics['defaulters'] }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h4>Promotion Readiness Score: <span class="text-primary">{{ $readinessScore }}%</span></h4>
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $readinessScore }}%" aria-valuenow="{{ $readinessScore }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="aiTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="eligible-tab" data-bs-toggle="tab" href="#eligible" role="tab">Auto Eligible Students</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="conditional-tab" data-bs-toggle="tab" href="#conditional" role="tab">Conditional Cases</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="defaulter-tab" data-bs-toggle="tab" href="#defaulter" role="tab">Defaulters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="batches-tab" data-bs-toggle="tab" href="#batches" role="tab">Pending Batches Review</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="aiTabsContent">
                    <!-- Eligible -->
                    <div class="tab-pane fade show active" id="eligible" role="tabpanel">
                        <p class="text-muted">These students have passed all automated checks (attendance, exams, fees) and are ready for approval.</p>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class/Section</th>
                                    <th>Score</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingBatches as $batch)
                                    @foreach($batch->students->where('category', 'eligible') as $student)
                                    <tr>
                                        <td>{{ $student->student->first_name ?? 'N/A' }} {{ $student->student->last_name ?? '' }}</td>
                                        <td>{{ $batch->fromClass->name ?? '' }}</td>
                                        <td>{{ $student->eligibility_score }}</td>
                                        <td><span class="badge bg-success">Ready</span></td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Conditional -->
                    <div class="tab-pane fade" id="conditional" role="tabpanel">
                        <p class="text-muted">These students require manual review due to borderline issues.</p>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Score</th>
                                    <th>Risk Flags</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingBatches as $batch)
                                    @foreach($batch->students->where('category', 'conditional') as $student)
                                    <tr>
                                        <td>{{ $student->student->first_name ?? 'N/A' }} {{ $student->student->last_name ?? '' }}</td>
                                        <td>{{ $batch->fromClass->name ?? '' }}</td>
                                        <td>{{ $student->eligibility_score }}</td>
                                        <td>
                                            @if($student->risk_flags)
                                                @foreach($student->risk_flags as $flag)
                                                    <span class="badge bg-warning text-dark">{{ $flag }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Defaulters -->
                    <div class="tab-pane fade" id="defaulter" role="tabpanel">
                        <p class="text-muted">These students have failed critical eligibility checks and are marked as defaulters.</p>
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Class</th>
                                    <th>Score</th>
                                    <th>Risk Flags</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingBatches as $batch)
                                    @foreach($batch->students->where('category', 'defaulter') as $student)
                                    <tr>
                                        <td>{{ $student->student->first_name ?? 'N/A' }} {{ $student->student->last_name ?? '' }}</td>
                                        <td>{{ $batch->fromClass->name ?? '' }}</td>
                                        <td>{{ $student->eligibility_score }}</td>
                                        <td>
                                            @if($student->risk_flags)
                                                @foreach($student->risk_flags as $flag)
                                                    <span class="badge bg-danger">{{ $flag }}</span>
                                                @endforeach
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Batches Review -->
                    <div class="tab-pane fade" id="batches" role="tabpanel">
                        <p class="text-muted">Review and approve complete batches. Only Admin can perform these actions.</p>
                        @foreach($pendingBatches as $batch)
                        <div class="card mb-3 border-primary">
                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                <strong>Batch #{{ $batch->id }} - {{ $batch->fromClass->name ?? '' }} to {{ $batch->toClass->name ?? '' }}</strong>
                                <span>Total Students: {{ $batch->students->count() }}</span>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.promotions.batches.approve', $batch->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success" onclick="return confirm('Approve this batch for execution?');">Approve Batch</button>
                                </form>
                                <form action="{{ route('admin.promotions.batches.reject', $batch->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this batch?');">Reject Batch</button>
                                </form>
                                <a href="{{ route('admin.promotions.batches.show', $batch->id) }}" class="btn btn-outline-primary">View Details</a>
                            </div>
                        </div>
                        @endforeach
                        
                        @if($pendingBatches->isEmpty())
                            <div class="alert alert-success">No pending batches to review.</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
