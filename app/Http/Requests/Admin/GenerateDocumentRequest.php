<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GenerateDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Authorize logic handled by middleware
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'template_id' => 'required|exists:document_templates,id',
            'purpose' => 'nullable|string|max:255',
            'academic_year' => 'nullable|string|max:50',
            'ai_enhance' => 'nullable|boolean',
            'manual_content' => 'nullable|string'
        ];
    }
}
