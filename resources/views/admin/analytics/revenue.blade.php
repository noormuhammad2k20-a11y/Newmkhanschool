@extends('layouts.admin')

@section('title', 'Branch Revenue Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="h3 text-gray-800">Branch Revenue Reports</h2>
    </div>

    <div class="row">
        @foreach($revenueData as $data)
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                {{ $data['branch'] }}
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Paid: ${{ number_format($data['paid'], 2) }} <br>
                                Pending: ${{ number_format($data['pending'], 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Revenue Comparison</h6>
                </div>
                <div class="card-body">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(array_column($revenueData, 'branch')) !!},
                datasets: [
                    {
                        label: 'Paid Revenue',
                        data: {!! json_encode(array_column($revenueData, 'paid')) !!},
                        backgroundColor: '#1cc88a',
                    },
                    {
                        label: 'Pending Revenue',
                        data: {!! json_encode(array_column($revenueData, 'pending')) !!},
                        backgroundColor: '#f6c23e',
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
