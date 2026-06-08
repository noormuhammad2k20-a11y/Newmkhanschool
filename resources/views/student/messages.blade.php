@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Messages</h1>
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Communicate with your teachers</p>
    </div>
    <button type="button" onclick="document.getElementById('messageModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
        <span class="material-symbols-rounded text-[20px]">edit</span>
        Compose
    </button>
</div>

@if(session('success'))
<div class="mb-4 bg-green-50 text-green-700 p-4 rounded-lg border border-green-200">
    {{ session('success') }}
</div>
@endif

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 h-[calc(100vh-12rem)] min-h-[500px]">
    <!-- Inbox List -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex flex-col">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
            <h2 class="font-semibold text-gray-900 dark:text-white">Inbox</h2>
        </div>
        <div class="overflow-y-auto flex-1 p-2 space-y-1">
            @forelse($messages as $msg)
                <a href="#" class="block p-3 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors {{ $msg->status === 'Unread' && $msg->receiver_id == auth()->id() ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                    <div class="flex justify-between items-start mb-1">
                        <span class="font-semibold text-sm text-gray-900 dark:text-white">
                            {{ $msg->sender_id == auth()->id() ? 'To: ' . (collect($teachers)->firstWhere('user_id', $msg->receiver_id)->first_name ?? 'Teacher') : 'From: Teacher' }}
                        </span>
                        <span class="text-[10px] text-gray-500">{{ \Carbon\Carbon::parse($msg->created_at)->format('M d') }}</span>
                    </div>
                    <div class="text-sm font-medium text-gray-800 dark:text-gray-200 truncate">{{ $msg->subject }}</div>
                    <div class="text-xs text-gray-500 truncate mt-1">{{ $msg->body }}</div>
                </a>
            @empty
                <div class="p-8 text-center text-gray-500">
                    <span class="material-symbols-rounded text-3xl mb-2">inbox</span>
                    <p class="text-sm">No messages</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Message Content (Placeholder) -->
    <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden flex items-center justify-center">
        <div class="text-center text-gray-500">
            <span class="material-symbols-rounded text-4xl mb-2 opacity-50">forum</span>
            <p>Select a message to view details</p>
        </div>
    </div>
</div>

<!-- Compose Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-gray-900/50 backdrop-blur-sm z-50 flex items-center justify-center p-4">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-xl w-full max-w-lg border border-gray-200 dark:border-gray-700">
        <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">New Message</h3>
            <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('student.messages.send') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">To (Teacher)</label>
                    <select name="receiver_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select Teacher...</option>
                        @foreach($teachers ?? [] as $teacher)
                            @if($teacher->user_id)
                                <option value="{{ $teacher->user_id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                    <input type="text" name="subject" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                    <textarea name="body" rows="6" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors">Send Message</button>
            </div>
        </form>
    </div>
</div>
@endsection
