@extends('layouts.app')

@section('title', 'AI Report Card Narratives')

@section('content')
<div class="container-fluid py-4">
    <div class="card mb-4 shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">AI Report Card Narratives</h5>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <p class="text-muted">Select a class and exam to generate narratives for all students.</p>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-primary" onclick="alert('Batch generate narrative functionality to be implemented')">
                        <i class="fas fa-magic"></i> Batch Generate For Entire Class
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Student</th>
                            <th>Class</th>
                            <th>Exam</th>
                            <th>Grade</th>
                            <th>AI Narrative Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Placeholder for narratives loop -->
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                Data will be loaded here. Please ensure report cards exist.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
