<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;

class MessageController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        $messages = DB::table('messages')
            ->where('receiver_id', $userId)
            ->orWhere('sender_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $parent = auth()->user();
        $students = Student::whereHas('user.linkedStudents', function($q) use ($parent) {
            $q->where('parent_user_id', $parent->id);
        })->get();
        
        $classIds = $students->pluck('current_class_id')->unique();
        
        $teachers = DB::table('teachers')
            ->join('timetables', 'teachers.id', '=', 'timetables.teacher_id')
            ->whereIn('timetables.class_id', $classIds)
            ->select('teachers.id', 'teachers.full_name as first_name', DB::raw('"" as last_name'), 'teachers.user_id')
            ->distinct()
            ->get();

        return view('parent.messages', compact('messages', 'teachers'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|integer',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        DB::table('messages')->insert([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject,
            'body' => $request->body,
            'status' => 'Unread',
            'created_at' => now(),
        ]);

        return back()->with('success', 'Message sent successfully.');
    }
}
