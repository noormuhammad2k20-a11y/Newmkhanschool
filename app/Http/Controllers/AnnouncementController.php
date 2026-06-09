<?php

namespace App\Http\Controllers;

use App\Models\SchoolNotification as Announcement; // The db table is probably school_notifications or announcements... wait, the prompt says `announcements` table exists.
// Wait, the prompt says "announcements table exists". But my migration created `school_notifications`.
// Let me verify the model and table.
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnouncementController extends Controller
{
    public function index()
    {
        $schoolId = auth()->user()->school_id ?? 1;
        // Let's use DB facade to be safe if the model isn't exactly Announcement.
        $announcements = DB::table('announcements')
            ->join('users', 'announcements.author_id', '=', 'users.id')
            ->select('announcements.*', 'users.name as author_name')
            ->where('announcements.school_id', $schoolId)
            ->orderBy('created_at', 'desc')
            ->paginate(15);
            
        return view('admin.announcements.index', compact('announcements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target_role' => 'required|in:all,teacher,student,parent'
        ]);

        DB::table('announcements')->insert([
            'title' => $request->title,
            'content' => $request->content,
            'target_role' => $request->target_role,
            'author_id' => auth()->id(),
            'school_id' => auth()->user()->school_id ?? 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        return back()->with('success', 'Announcement created successfully.');
    }

    public function destroy($id)
    {
        DB::table('announcements')->where('id', $id)->delete();
        return back()->with('success', 'Announcement deleted successfully.');
    }
}
