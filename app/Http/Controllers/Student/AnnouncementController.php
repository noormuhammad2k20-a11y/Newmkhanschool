<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Announcement;

class AnnouncementController extends Controller
{
    public function index()
    {
        $announcements = Announcement::whereIn('target_role', ['all', 'student'])
            ->latest()
            ->paginate(15);

        return view('student.announcements', compact('announcements'));
    }
}
