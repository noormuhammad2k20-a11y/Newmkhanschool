<?php
namespace App\Http\Controllers\ParentPortal;

use App\Http\Controllers\Controller;
use App\Models\ParentStudent;

abstract class BaseParentController extends Controller
{
    protected function parentOwnsStudent(int $student_id): bool
    {
        return ParentStudent::where('parent_user_id', auth()->id())
            ->where('student_id', $student_id)
            ->exists();
    }

    protected function getLinkedStudentIds(): \Illuminate\Support\Collection
    {
        return ParentStudent::where('parent_user_id', auth()->id())
            ->pluck('student_id');
    }
}
