<?php

namespace App\Http\Controllers\ParentPortal;

use App\Models\Notification;
use Illuminate\Http\Request;
use App\Http\Traits\AjaxResponseTrait;

class NotificationController extends BaseParentController
{
    use AjaxResponseTrait;
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);
            
        return view('parent.notifications.index', compact('notifications'));
    }

    public function markRead($id)
    {
        $notification = Notification::where('user_id', auth()->id())->findOrFail($id);
        $notification->update(['is_read' => 1]);
        return response()->json(['success' => true]);
    }

    public function markAllRead(Request $request)
    {
        Notification::where('user_id', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);
            
        return $this->ajaxSuccess($request, 'All notifications marked as read.');
    }

    public function unreadCount()
    {
        $count = Notification::where('user_id', auth()->id())
            ->where('is_read', 0)
            ->count();
            
        return response()->json(['count' => $count]);
    }
}
