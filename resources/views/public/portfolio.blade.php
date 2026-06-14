@extends('layouts.app')

@section('title', 'Student Portfolio - ' . ($portfolio->student->first_name ?? ''))

@section('content')
<main class="flex-1 p-lg overflow-y-auto w-full min-w-0 bg-surface">
    <div class="max-w-[1200px] mx-auto">
        <!-- Header Profile -->
        <div class="card border-0 shadow-sm rounded-xl mb-4 bg-primary text-white overflow-hidden relative">
            <div class="card-body p-5">
                <div class="d-flex align-items-center gap-4">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center overflow-hidden" style="width: 100px; height: 100px;">
                        @if($portfolio->student->photo)
                            <img src="{{ asset('storage/' . $portfolio->student->photo) }}" alt="Photo" class="w-100 h-100 object-cover">
                        @else
                            <i class="fas fa-user-graduate text-primary fa-3x"></i>
                        @endif
                    </div>
                    <div>
                        <h2 class="mb-1 font-bold">{{ $portfolio->student->first_name }} {{ $portfolio->student->last_name }}</h2>
                        <h5 class="text-white-50 mb-0">{{ $portfolio->title }}</h5>
                        <p class="mt-2 mb-0">{{ $portfolio->description }}</p>
                    </div>
                </div>
            </div>
            <!-- decorative overlay -->
            <div class="position-absolute end-0 bottom-0 opacity-10 pe-3 pb-3">
                <i class="fas fa-medal fa-10x"></i>
            </div>
        </div>

        <!-- Portfolio Items -->
        <div class="row g-4">
            @if($items->isEmpty())
                <div class="col-12 text-center py-5">
                    <div class="text-muted mb-3"><i class="fas fa-folder-open fa-3x"></i></div>
                    <h5>No items in portfolio yet.</h5>
                </div>
            @else
                @foreach($items as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm rounded-xl hover-elevate transition-all">
                            @if($item->attachment)
                                <!-- Simple preview if image -->
                                @if(preg_match('/\.(jpg|jpeg|png|gif)$/i', $item->attachment))
                                    <img src="{{ asset('storage/' . $item->attachment) }}" class="card-img-top" alt="preview" style="height: 180px; object-fit: cover;">
                                @else
                                    <div class="bg-light text-center py-4 text-muted border-bottom">
                                        <i class="fas fa-file-alt fa-3x"></i>
                                    </div>
                                @endif
                            @else
                                <div class="bg-primary-fixed text-primary text-center py-4 border-bottom">
                                    <i class="fas fa-award fa-3x"></i>
                                </div>
                            @endif
                            
                            <div class="card-body">
                                <span class="badge bg-secondary mb-2">{{ ucfirst($item->type) }}</span>
                                <h5 class="card-title font-bold text-on-surface">{{ $item->title }}</h5>
                                <p class="card-text text-secondary small">{{ Str::limit($item->description, 100) }}</p>
                            </div>
                            <div class="card-footer bg-white border-0 pt-0 pb-3">
                                @if($item->attachment)
                                    <a href="{{ asset('storage/' . $item->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary w-100">
                                        View Attachment
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</main>
<style>
    .hover-elevate:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection
