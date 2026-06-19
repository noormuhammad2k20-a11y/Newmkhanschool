<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && in_array(auth()->user()->role_id, [1]); // Super Admin only
    }

    public function rules(): array
    {
        return [
            'key'   => 'required|string|max:191',
            'value' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'key.required' => 'Setting key is required.',
        ];
    }
}
