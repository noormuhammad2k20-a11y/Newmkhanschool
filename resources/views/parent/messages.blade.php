@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex items-center gap-2 text-label-md font-label-md text-secondary mb-2">
                    <a href="{{ route('parent.dashboard') }}" class="hover:text-primary transition-colors">Dashboard</a>
                    <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    <span class="text-on-surface">Messages</span>
                </nav>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Messages</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Communicate directly with your children's teachers.</p>
            </div>
            <div class="flex items-center gap-3">
                <button type="button" onclick="document.getElementById('messageModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2 bg-primary text-on-primary rounded-lg font-label-md hover:bg-primary/90 transition-colors shadow-sm">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Compose Message
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 text-emerald-800 p-4 rounded-xl border border-emerald-200 font-body-md text-body-md flex items-center gap-3">
                <span class="material-symbols-outlined">check_circle</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-xl h-[calc(100vh-18rem)] min-h-[600px]">
            <!-- Inbox List -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex flex-col shadow-sm">
                <div class="p-md border-b border-outline-variant bg-surface-bright flex justify-between items-center">
                    <h3 class="text-headline-md font-headline-md text-on-surface">Inbox</h3>
                    <span class="bg-secondary-container text-on-secondary-container px-2 py-1 rounded-full text-label-sm font-label-sm">{{ count($messages ?? []) }}</span>
                </div>
                <div class="overflow-y-auto flex-1 divide-y divide-outline-variant">
                    @forelse($messages as $msg)
                        <a href="#" class="block p-4 hover:bg-surface-container-low transition-colors group {{ $msg->status === 'Unread' && $msg->receiver_id == auth()->id() ? 'bg-primary-container/20' : '' }}">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-primary text-xs font-bold">
                                        <span class="material-symbols-outlined text-[16px]">person</span>
                                    </div>
                                    <span class="font-label-md text-label-md text-on-surface group-hover:text-primary transition-colors">
                                        {{ $msg->sender_id == auth()->id() ? 'To: ' . (collect($teachers)->firstWhere('user_id', $msg->receiver_id)->first_name ?? 'Teacher') : 'From: Teacher' }}
                                    </span>
                                </div>
                                <span class="font-label-sm text-label-sm text-secondary">{{ \Carbon\Carbon::parse($msg->created_at)->format('M d') }}</span>
                            </div>
                            <div class="font-title-md text-title-md text-on-surface truncate {{ $msg->status === 'Unread' && $msg->receiver_id == auth()->id() ? 'font-bold' : '' }}">
                                {{ $msg->subject }}
                            </div>
                            <div class="font-body-md text-body-md text-secondary truncate mt-1 line-clamp-2 white-space-normal">
                                {{ $msg->body }}
                            </div>
                        </a>
                    @empty
                        <div class="p-xl text-center flex flex-col items-center justify-center h-full text-secondary">
                            <span class="material-symbols-outlined text-[48px] mb-4 opacity-50">inbox</span>
                            <p class="font-body-lg text-body-lg text-on-surface mb-1">Your inbox is empty</p>
                            <p class="font-body-md text-body-md">You don't have any messages yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Message Content (Placeholder) -->
            <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden p-0 flex items-center justify-center shadow-sm">
                <div class="text-center text-secondary max-w-sm px-6">
                    <div class="w-20 h-20 mx-auto rounded-full bg-surface-container-low flex items-center justify-center mb-6">
                        <span class="material-symbols-outlined text-[40px] text-outline">forum</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-2">No Message Selected</h3>
                    <p class="text-body-lg font-body-lg text-secondary">Select a message from your inbox to view the conversation details or compose a new message.</p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Compose Modal -->
<div id="messageModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm z-[10000] flex items-center justify-center p-4 transition-opacity">
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-lg w-full max-w-2xl transform transition-all">
        <div class="flex items-center justify-between p-xl border-b border-outline-variant bg-surface-bright">
            <h3 class="text-headline-md font-headline-md text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">edit_square</span>
                Compose Message
            </h3>
            <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-secondary hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('parent.messages.send') }}" method="POST" class="p-xl">
            @csrf
            <div class="space-y-6">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">To (Teacher) <span class="text-error">*</span></label>
                    <div class="relative">
                        <select name="receiver_id" required class="w-full bg-surface border border-outline-variant rounded-lg py-3 pl-10 pr-4 text-body-lg font-body-lg focus:border-primary focus:ring-1 focus:ring-primary text-on-surface appearance-none transition-colors">
                            <option value="">Select a teacher...</option>
                            @foreach($teachers ?? [] as $teacher)
                                @if($teacher->user_id)
                                    <option value="{{ $teacher->user_id }}">{{ $teacher->first_name }} {{ $teacher->last_name }} ({{ $teacher->department ?? 'General' }})</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">person_search</span>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-secondary text-[20px] pointer-events-none">arrow_drop_down</span>
                    </div>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Subject <span class="text-error">*</span></label>
                    <input type="text" name="subject" placeholder="What is this message about?" required class="w-full bg-surface border border-outline-variant rounded-lg py-3 px-4 text-body-lg font-body-lg focus:border-primary focus:ring-1 focus:ring-primary text-on-surface transition-colors">
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Message <span class="text-error">*</span></label>
                    <textarea name="body" rows="8" placeholder="Type your message here..." required class="w-full bg-surface border border-outline-variant rounded-lg py-3 px-4 text-body-lg font-body-lg focus:border-primary focus:ring-1 focus:ring-primary text-on-surface resize-y transition-colors"></textarea>
                </div>
            </div>
            <div class="mt-8 pt-6 border-t border-outline-variant flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="px-6 py-2.5 border border-outline-variant rounded-lg text-on-surface hover:bg-surface-container-low font-label-lg transition-colors">Cancel</button>
                <button type="submit" class="bg-primary text-on-primary px-6 py-2.5 rounded-lg font-label-lg hover:bg-primary/90 transition-colors flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined text-[18px] mr-2">send</span>
                    Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
