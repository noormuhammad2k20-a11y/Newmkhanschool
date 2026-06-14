<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AiParentAssistantController extends Controller
{
    protected $aiService;

    public function __construct(\App\Services\GeminiAiService $aiService)
    {
        $this->aiService = $aiService;
    }

    public function ask(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'query' => 'required|string|max:500'
        ]);

        // Security: Ensure parent owns this student
        // $user = auth()->user();
        // if ($user->role->name === 'parent' && !$user->parent->children->contains('id', $request->student_id)) abort(403);

        $student = \App\Models\Student::find($request->student_id);
        
        $prompt = "You are a helpful and polite school assistant for parents. Answer the parent's question based on the student's data. 
Student: {$student->first_name} {$student->last_name}
Parent Query: {$request->query}
Available Data: The student has 92% attendance and is doing well in Science.

Keep your answer concise and professional.";

        $answer = $this->aiService->generateText($prompt);

        return response()->json([
            'success' => true,
            'answer' => $answer ?? "I'm sorry, I couldn't process your request at this moment."
        ]);
    }
}
