<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        // Optional: fetch teachers for the student to send messages to
        $student = auth()->user()->student;
        $teachers = DB::table('teachers')
            ->join('timetables', 'teachers.id', '=', 'timetables.teacher_id')
            ->where('timetables.class_id', $student->current_class_id)
            ->select('teachers.id', 'teachers.full_name', 'teachers.user_id')
            ->distinct()
            ->get();

        return view('student.messages', compact('messages', 'teachers'));
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
