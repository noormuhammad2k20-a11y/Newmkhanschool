<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('status', 'published')
            ->whereIn('role_visibility', ['all', 'parent'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('parent.announcements', compact('announcements'));
    }
}
