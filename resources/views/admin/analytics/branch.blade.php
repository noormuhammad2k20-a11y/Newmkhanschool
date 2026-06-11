@extends('layouts.admin')

@section('title', 'Branch Analytics')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Branch Analytics</h2>
    </div>

    <div class="row">
        @foreach($branches as $branch)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                {{ $branch->name }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Students: {{ $branch->students_count }} <br>
                                Teachers: {{ $branch->teachers_count }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-building fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Additional analytics charts can be added here -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Branch Comparison Chart</h6>
                </div>
                <div class="card-body">
                    <canvas id="branchComparisonChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('branchComparisonChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($branches->pluck('name')) !!},
                datasets: [
                    {
                        label: 'Students',
                        data: {!! json_encode($branches->pluck('students_count')) !!},
                        backgroundColor: '#4e73df',
                    },
                    {
                        label: 'Teachers',
                        data: {!! json_encode($branches->pluck('teachers_count')) !!},
                        backgroundColor: '#1cc88a',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }
</script>
@endpush
