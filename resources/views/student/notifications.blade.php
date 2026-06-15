@extends('layouts.student')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title">My Notifications</h4>
                <button class="btn btn-sm btn-primary" onclick="markAllRead()">Mark All as Read</button>
            </div>
            <div class="card-body">
                @if($notifications->isEmpty())
                    <div class="alert alert-info">You have no notifications at this time.</div>
                @else
                    <div class="list-group">
                        @foreach($notifications as $notification)
                            <div class="list-group-item list-group-item-action {{ $notification->is_read ? '' : 'list-group-item-warning' }}" id="notif-{{ $notification->id }}">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">{{ $notification->title }}</h5>
                                    <small>{{ $notification->created_at->diffForHumans() }}</small>
                                </div>
                                <p class="mb-1">{{ $notification->message }}</p>
                                @if(!$notification->is_read)
                                    <button class="btn btn-xs btn-outline-secondary mt-2" onclick="markRead({{ $notification->id }})">Mark as Read</button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                    <div class="mt-3">
                        {{ $notifications->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function markRead(id) {
        $.post('{{ url("student/notifications") }}/' + id + '/read', {
            _token: '{{ csrf_token() }}'
        }).done(function() {
            $('#notif-' + id).removeClass('list-group-item-warning');
            $('#notif-' + id).find('button').remove();
            updateUnreadCount();
        });
    }

    function markAllRead() {
        $.post('{{ route("student.notifications.read-all") }}', {
            _token: '{{ csrf_token() }}'
        }).done(function(res) {
            $('.list-group-item-warning').removeClass('list-group-item-warning').find('button').remove();
            updateUnreadCount();
            alert(res.message);
        });
    }

    function updateUnreadCount() {
        $.get('{{ route("student.notifications.count") }}', function(data) {
            if(data.count > 0) {
                $('.badge-notifications').text(data.count).show();
            } else {
                $('.badge-notifications').hide();
            }
        });
    }
</script>
@endsection
