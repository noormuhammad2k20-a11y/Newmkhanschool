<?php

namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Http\Traits\AjaxResponseTrait;

class MessageController extends BaseParentController
{
    use AjaxResponseTrait;
    public function index()
    {
        $userId = auth()->id();
        $messages = DB::table('messages')
            ->where('receiver_id', $userId)
            ->orWhere('sender_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        $studentIds = \App\Models\ParentStudent::where('parent_user_id', auth()->id())->pluck('student_id');
        $students = Student::whereIn('id', $studentIds)->get();
        
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

        return $this->ajaxSuccess($request, 'Message sent successfully.');
    }
}
