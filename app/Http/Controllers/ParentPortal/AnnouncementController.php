<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::whereIn('target_role', ['all', 'parent'])
            ->latest()
            ->paginate(15);

        return view('parent.announcements', compact('announcements'));
    }
}
