@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Notifications</h2>
            </div>
            <div class="flex items-center gap-3">
                <form action="{{ route('parent.notifications.read-all') }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-surface-container-high text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest transition-colors">
                        <span class="material-symbols-outlined text-[18px]">done_all</span>
                        Mark All Read
                    </button>
                </form>
            </div>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm">
            <div class="divide-y divide-outline-variant">
                @forelse($notifications as $notification)
                    <div class="p-4 {{ $notification->is_read ? 'bg-surface-container-lowest' : 'bg-surface-container-low' }} hover:bg-surface-container-high transition-colors flex gap-4 items-start" id="notif-{{ $notification->id }}">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $notification->is_read ? 'bg-surface-container text-secondary' : 'bg-primary-fixed text-primary' }}">
                            <span class="material-symbols-outlined">
                                {{ $notification->type === 'attendance' ? 'event_busy' : ($notification->type === 'fee_overdue' ? 'account_balance_wallet' : ($notification->type === 'leave_update' ? 'event_available' : 'notifications')) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-1">
                                <h4 class="text-title-md font-title-md {{ $notification->is_read ? 'text-on-surface' : 'text-primary font-bold' }}">
                                    @if($notification->action_url)
                                        <a href="{{ $notification->action_url }}" class="hover:underline" onclick="markAsRead({{ $notification->id }})">{{ $notification->title }}</a>
                                    @else
                                        <span class="cursor-pointer hover:underline" onclick="markAsRead({{ $notification->id }})">{{ $notification->title }}</span>
                                    @endif
                                </h4>
                                <span class="text-label-sm font-label-sm text-secondary">{{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}</span>
                            </div>
                            <p class="text-body-md font-body-md text-secondary">{{ $notification->body }}</p>
                        </div>
                        @if(!$notification->is_read)
                            <div class="w-2 h-2 rounded-full bg-error mt-2"></div>
                        @endif
                    </div>
                @empty
                    <div class="p-8 text-center text-secondary">
                        <span class="material-symbols-outlined text-[48px] mb-4 opacity-50">notifications_off</span>
                        <p class="text-title-md font-title-md">No Notifications</p>
                        <p class="text-body-md font-body-md mt-1">You're all caught up!</p>
                    </div>
                @endforelse
            </div>
            @if($notifications->hasPages())
                <div class="p-4 border-t border-outline-variant bg-surface-lowest">
                    {{ $notifications->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</main>

<script>
function markAsRead(id) {
    fetch(`/parent/notifications/${id}/read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    }).then(res => res.json())
    .then(data => {
        if(data.success) {
            let el = document.getElementById('notif-'+id);
            if(el) {
                el.classList.remove('bg-surface-container-low');
                el.classList.add('bg-surface-container-lowest');
                let dot = el.querySelector('.bg-error');
                if(dot) dot.remove();
                let icon = el.querySelector('.bg-primary-fixed');
                if(icon) {
                    icon.classList.remove('bg-primary-fixed', 'text-primary');
                    icon.classList.add('bg-surface-container', 'text-secondary');
                }
                let title = el.querySelector('h4');
                if(title) {
                    title.classList.remove('text-primary', 'font-bold');
                    title.classList.add('text-on-surface');
                }
            }
        }
    });
}
</script>
@endsection
