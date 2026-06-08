@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Announcements</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">School news and updates</p>
    </div>
</div>

<div class="space-y-4">
    @forelse($announcements as $announcement)
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm border border-gray-200 dark:border-gray-700 relative overflow-hidden group hover:border-blue-300 transition-colors">
            <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
            <div class="flex items-start justify-between gap-4 mb-2">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                    {{ $announcement->title }}
                </h3>
                <span class="text-xs text-gray-500 flex items-center gap-1 shrink-0">
                    <span class="material-symbols-rounded text-[14px]">schedule</span>
                    {{ $announcement->created_at->diffForHumans() }}
                </span>
            </div>
            <p class="text-gray-600 dark:text-gray-300 text-sm whitespace-pre-line">{{ $announcement->content }}</p>
        </div>
    @empty
        <div class="bg-white dark:bg-gray-800 rounded-xl p-12 text-center border border-gray-200 dark:border-gray-700">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 mb-4 text-gray-400">
                <span class="material-symbols-rounded text-3xl">campaign</span>
            </div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-white">No Announcements</h3>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Check back later for school news and updates.</p>
        </div>
    @endforelse

    @if(isset($announcements) && method_exists($announcements, 'links'))
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
