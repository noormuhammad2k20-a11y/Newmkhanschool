<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::where('status', 'published')
            ->whereIn('role_visibility', ['all', 'student'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('student.announcements', compact('announcements'));
    }
}
