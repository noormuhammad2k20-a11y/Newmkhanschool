@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-headline-xl font-headline-xl text-on-surface">Messages</h1>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Communicate with your children's teachers</p>
            </div>
            <button type="button" onclick="document.getElementById('messageModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:opacity-90 transition-opacity shadow-sm">
                <span class="material-symbols-outlined text-[20px]">edit</span>
                Compose
            </button>
        </div>

        @if(session('success'))
        <div class="mb-4 bg-emerald-100 text-emerald-800 p-4 rounded-lg border border-emerald-200 text-body-md font-body-md">
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-md h-[calc(100vh-16rem)] min-h-[500px]">
            <!-- Inbox List -->
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                    <h2 class="text-headline-md font-headline-md text-on-surface">Inbox</h2>
                </div>
                <div class="overflow-y-auto flex-1 p-2 space-y-1">
                    @forelse($messages as $msg)
                        <a href="#" class="block p-3 rounded-lg hover:bg-surface-container-low transition-colors {{ $msg->status === 'Unread' && $msg->receiver_id == auth()->id() ? 'bg-primary-fixed text-on-primary-fixed' : 'text-on-surface' }}">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-label-md text-label-md">
                                    {{ $msg->sender_id == auth()->id() ? 'To: ' . (collect($teachers)->firstWhere('user_id', $msg->receiver_id)->first_name ?? 'Teacher') : 'From: Teacher' }}
                                </span>
                                <span class="text-xs text-secondary">{{ \Carbon\Carbon::parse($msg->created_at)->format('M d') }}</span>
                            </div>
                            <div class="text-body-md font-body-md font-medium truncate">{{ $msg->subject }}</div>
                            <div class="text-body-md font-body-md text-secondary truncate mt-1">{{ $msg->body }}</div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-secondary">
                            <span class="material-symbols-outlined text-3xl mb-2">inbox</span>
                            <p class="text-body-md font-body-md mt-2">No messages</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Message Content (Placeholder) -->
            <div class="lg:col-span-2 bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex items-center justify-center bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-surface-bright to-surface-container-lowest">
                <div class="text-center text-outline">
                    <span class="material-symbols-outlined text-[48px] mb-2 opacity-50">forum</span>
                    <p class="text-body-lg font-body-lg text-secondary">Select a message to view details</p>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Compose Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[10000] flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-xl shadow-lg w-full max-w-lg border border-outline-variant overflow-hidden transform transition-all">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant bg-surface-bright">
            <h3 class="text-headline-md font-headline-md text-on-surface">New Message</h3>
            <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="text-secondary hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('parent.messages.send') }}" method="POST" class="p-6 bg-surface-container-lowest">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">To (Teacher)</label>
                    <select name="receiver_id" required class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface shadow-sm focus:border-primary focus:ring focus:ring-primary/20 px-3 py-2 outline-none transition-all font-body-md text-body-md">
                        <option value="">Select Teacher...</option>
                        @foreach($teachers ?? [] as $teacher)
                            @if($teacher->user_id)
                                <option value="{{ $teacher->user_id }}">{{ $teacher->first_name }} {{ $teacher->last_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Subject</label>
                    <input type="text" name="subject" required class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface shadow-sm focus:border-primary focus:ring focus:ring-primary/20 px-3 py-2 outline-none transition-all font-body-md text-body-md">
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Message</label>
                    <textarea name="body" rows="6" required class="w-full rounded-lg border border-outline-variant bg-surface text-on-surface shadow-sm focus:border-primary focus:ring focus:ring-primary/20 px-3 py-2 outline-none transition-all font-body-md text-body-md"></textarea>
                </div>
            </div>
            <div class="mt-8 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="px-4 py-2 rounded-lg font-label-md text-label-md text-secondary hover:bg-surface-container transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md text-label-md hover:opacity-90 transition-opacity shadow-sm">Send Message</button>
            </div>
        </form>
    </div>
</div>
@endsection
