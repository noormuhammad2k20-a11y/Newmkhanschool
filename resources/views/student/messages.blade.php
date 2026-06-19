@extends('layouts.app')

@section('title', 'Messages')

@section('content')
<!-- Main Canvas -->
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Messages</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Communicate with your teachers</p>
            </div>
            <button type="button" onclick="document.getElementById('messageModal').classList.remove('hidden')" class="inline-flex items-center gap-2 px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container text-label-md font-label-md rounded-lg transition-colors shadow-sm">
                <span class="material-symbols-rounded text-[20px]">edit</span>
                Compose
            </button>
        </div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-md h-[calc(100vh-16rem)] min-h-[600px]">
            <!-- Inbox List -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex flex-col">
                <div class="p-4 border-b border-outline-variant bg-surface-bright">
                    <div class="flex gap-4 border-b border-outline-variant">
                        <button class="px-4 py-2 text-primary border-b-2 border-primary font-bold text-sm">Inbox</button>
                        <button class="px-4 py-2 text-secondary hover:text-on-surface transition-colors text-sm font-medium">Sent</button>
                    </div>
                </div>
                <div class="overflow-y-auto flex-1 p-2 space-y-1">
                    @forelse($messages as $msg)
                        @php
                            $isUnread = $msg->status === 'Unread' && $msg->receiver_id == auth()->id();
                        @endphp
                        <a href="#" class="block p-4 rounded-xl hover:bg-surface-container transition-colors {{ $isUnread ? 'bg-primary-fixed/30 border border-primary/20' : 'border border-transparent' }} relative group">
                            @if($isUnread)
                                <div class="absolute top-1/2 -translate-y-1/2 left-2 w-2 h-2 rounded-full bg-primary"></div>
                            @endif
                            <div class="flex justify-between items-start mb-1 pl-3">
                                <span class="text-label-md font-bold {{ $isUnread ? 'text-primary' : 'text-on-surface' }}">
                                    {{ $msg->sender_id == auth()->id() ? 'To: ' . (collect($teachers)->firstWhere('user_id', $msg->receiver_id)->full_name ?? 'Teacher') : (collect($teachers)->firstWhere('user_id', $msg->sender_id)->full_name ?? 'Teacher') }}
                                </span>
                                <span class="text-[10px] text-secondary whitespace-nowrap ml-2">{{ \Carbon\Carbon::parse($msg->created_at)->format('M d') }}</span>
                            </div>
                            <div class="text-body-md font-bold text-on-surface truncate pl-3">{{ $msg->subject }}</div>
                            <div class="text-body-sm text-secondary truncate mt-0.5 pl-3">{{ Str::limit($msg->body, 50) }}</div>
                        </a>
                    @empty
                        <div class="p-8 text-center text-secondary h-full flex flex-col items-center justify-center">
                            <span class="material-symbols-rounded text-[40px] mb-3 opacity-30">inbox</span>
                            <p class="text-body-lg font-bold text-on-surface mb-1">Your inbox is empty</p>
                            <p class="text-body-sm">You have no messages at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Message Content -->
            <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-xl shadow-sm overflow-hidden flex flex-col relative">
                @if(isset($messages) && count($messages) > 0)
                    @php $activeMsg = $messages[0]; @endphp
                    <!-- Thread Header -->
                    <div class="p-6 border-b border-outline-variant bg-surface-bright flex justify-between items-start">
                        <div>
                            <h2 class="text-headline-sm font-bold text-on-surface mb-1">{{ $activeMsg->subject }}</h2>
                            <div class="flex items-center gap-2 text-sm text-secondary">
                                <span class="material-symbols-rounded text-[16px]">account_circle</span>
                                <span>{{ $activeMsg->sender_id == auth()->id() ? 'You' : (collect($teachers)->firstWhere('user_id', $activeMsg->sender_id)->full_name ?? 'Teacher') }}</span>
                                <span>•</span>
                                <span>{{ \Carbon\Carbon::parse($activeMsg->created_at)->format('M d, Y, g:i A') }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <button class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-secondary transition-colors" title="Reply">
                                <span class="material-symbols-rounded text-[20px]">reply</span>
                            </button>
                            <button class="w-8 h-8 rounded-full hover:bg-surface-container flex items-center justify-center text-secondary transition-colors" title="Mark as unread">
                                <span class="material-symbols-rounded text-[20px]">mark_email_unread</span>
                            </button>
                        </div>
                    </div>
                    <!-- Thread Body -->
                    <div class="p-6 flex-1 overflow-y-auto">
                        <div class="bg-surface-container p-4 rounded-xl rounded-tl-sm max-w-[85%] border border-outline-variant">
                            <p class="text-body-lg text-on-surface whitespace-pre-wrap">{{ $activeMsg->body }}</p>
                        </div>
                    </div>
                    <!-- Reply Box -->
                    <div class="p-4 border-t border-outline-variant bg-surface-bright">
                        <div class="relative">
                            <textarea rows="3" placeholder="Type your reply here..." class="w-full bg-surface-container border border-outline-variant rounded-xl p-3 pr-12 focus:border-primary focus:ring-primary text-sm"></textarea>
                            <button class="absolute bottom-3 right-3 w-8 h-8 bg-primary text-on-primary rounded-lg flex items-center justify-center hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm">
                                <span class="material-symbols-rounded text-[18px]">send</span>
                            </button>
                        </div>
                    </div>
                @else
                    <div class="flex-1 flex flex-col items-center justify-center text-center text-secondary p-8">
                        <span class="material-symbols-rounded text-[64px] mb-4 opacity-20">forum</span>
                        <h3 class="text-headline-md font-bold text-on-surface mb-2">No conversation selected</h3>
                        <p class="text-body-lg text-secondary max-w-sm">Choose a conversation from the list to view details or start a new message.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<!-- Compose Modal -->
<div id="messageModal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
    <!-- Backdrop -->
    <div class="absolute inset-0 bg-black/40 backdrop-blur-sm transition-opacity" onclick="document.getElementById('messageModal').classList.add('hidden')"></div>

    <div class="bg-surface-container-lowest rounded-xl max-w-lg w-full shadow-lg border border-outline-variant relative z-10 transform scale-100 transition-transform duration-200">
        <div class="flex items-center justify-between p-6 border-b border-outline-variant">
            <h3 class="text-headline-md font-headline-md font-bold text-on-surface">New Message</h3>
            <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="text-secondary hover:bg-surface-container w-8 h-8 rounded-full flex items-center justify-center transition-colors">
                <span class="material-symbols-rounded text-[20px]">close</span>
            </button>
        </div>
        <form action="{{ route('student.messages.send') }}" method="POST" class="p-6">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">To (Teacher)</label>
                    <select name="receiver_id" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm h-10 px-3">
                        <option value="">Select Teacher...</option>
                        @foreach($teachers ?? [] as $teacher)
                            @if($teacher->user_id)
                                <option value="{{ $teacher->user_id }}">{{ $teacher->full_name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Subject</label>
                    <input type="text" name="subject" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm h-10 px-3">
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface-variant mb-1">Message</label>
                    <textarea name="body" rows="6" required class="w-full rounded-lg border-outline-variant bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-primary shadow-sm p-3"></textarea>
                </div>
            </div>
            <div class="mt-6 flex justify-end gap-3">
                <button type="button" onclick="document.getElementById('messageModal').classList.add('hidden')" class="px-4 py-2.5 rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2.5 bg-primary hover:bg-primary-container text-on-primary hover:text-on-primary-container text-label-md font-label-md rounded-lg transition-colors shadow-sm">Send Message</button>
            </div>
        </form>
    </div>
</div>
@endsection
