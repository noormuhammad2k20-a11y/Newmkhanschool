@extends('layouts.app')

@section('title', 'Messaging')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Inbox</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Communicate with administration and staff.</p>
            </div>
            <button onclick="document.getElementById('createModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-dark flex items-center gap-2">
                <span class="material-symbols-outlined">edit</span> Compose
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
        @endif

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden flex" style="min-height: 600px;">
            <!-- Mail List -->
            <div class="w-1/3 border-r border-outline-variant flex flex-col">
                <div class="p-4 border-b border-outline-variant bg-surface-bright">
                    <input type="text" placeholder="Search messages..." class="w-full bg-surface-container-lowest border border-outline-variant rounded-full px-4 py-2 text-sm focus:outline-none focus:border-primary">
                </div>
                <div class="overflow-y-auto flex-1">
                    @forelse($messages as $msg)
                    <div class="p-4 border-b border-outline-variant hover:bg-surface-container-low cursor-pointer transition-colors {{ $loop->first ? 'bg-primary-fixed-dim' : '' }}">
                        <div class="flex justify-between items-baseline mb-1">
                            <span class="font-bold text-on-surface text-sm">{{ $msg->sender->name ?? 'Unknown' }}</span>
                            <span class="text-xs text-secondary">{{ \Carbon\Carbon::parse($msg->created_at)->format('M d') }}</span>
                        </div>
                        <p class="font-medium text-on-surface text-sm mb-1 truncate">{{ $msg->subject }}</p>
                        <p class="text-xs text-secondary truncate">{{ $msg->body }}</p>
                    </div>
                    @empty
                    <div class="p-8 text-center text-secondary">
                        <p>Inbox is empty.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <!-- Message View Area -->
            <div class="flex-1 flex flex-col bg-surface-bright">
                @if(count($messages) > 0)
                <div class="p-6 border-b border-outline-variant">
                    <h3 class="text-headline-sm font-headline-sm text-on-surface mb-2">{{ $messages->first()->subject }}</h3>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary font-bold">
                            {{ substr($messages->first()->sender->name ?? 'U', 0, 1) }}
                        </div>
                        <div>
                            <p class="font-medium text-on-surface text-sm">{{ $messages->first()->sender->name ?? 'Unknown' }}</p>
                            <p class="text-xs text-secondary">{{ \Carbon\Carbon::parse($messages->first()->created_at)->format('d M Y, h:i A') }}</p>
                        </div>
                    </div>
                </div>
                <div class="p-6 flex-1 overflow-y-auto text-body-md text-on-surface whitespace-pre-wrap">
{{ $messages->first()->body }}
                </div>
                @else
                <div class="flex-1 flex items-center justify-center text-secondary flex-col">
                    <span class="material-symbols-outlined text-6xl mb-4 opacity-50">mail</span>
                    <p>Select a message to read</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Compose Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-xl max-w-2xl w-full">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-xl">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">New Message</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('teacher.messages.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <div>
                    <label class="block text-label-md text-on-surface mb-1">To</label>
                    <select name="receiver_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                        <option value="">Select Recipient</option>
                        @foreach($users as $u)
                            <option value="{{ $u->id }}">{{ $u->name }} {{ $u->role_id == 1 ? '(Admin)' : '' }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-label-md text-on-surface mb-1">Subject</label>
                    <input type="text" name="subject" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                </div>

                <div>
                    <label class="block text-label-md text-on-surface mb-1">Message</label>
                    <textarea name="body" rows="8" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface"></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Discard</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark flex items-center gap-2 transition-colors">
                        <span class="material-symbols-outlined text-[18px]">send</span> Send
                    </button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
