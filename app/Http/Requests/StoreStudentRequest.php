<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name'        => 'required|string|max:100',
            'last_name'         => 'required|string|max:100',
            'gender'            => 'required|in:Male,Female,Other',
            'dob'               => 'required|date|before:today',
            'admission_no'      => 'required|string|max:100|unique:students,admission_no',
            'current_class_id'  => 'required|exists:classes,id',
            'current_section_id'=> 'required|exists:sections,id',
            'mobile_number'     => 'nullable|string|max:20',
            'photo'             => 'nullable|image|max:2048',
        ];
    }
}
