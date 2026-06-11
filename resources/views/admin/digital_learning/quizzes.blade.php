@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Quizzes</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Manage digital quizzes and assessments.</p>
        </div>
        <button onclick="document.getElementById('createQuizModal').classList.remove('hidden')" class="flex items-center gap-sm px-md py-sm bg-primary text-on-primary rounded-full hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span class="font-label-md font-semibold">Create Quiz</span>
        </button>
    </div>

<div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-md font-label-md text-on-surface-variant">Title</th>
                        <th class="p-md font-label-md text-on-surface-variant">Class & Section</th>
                        <th class="p-md font-label-md text-on-surface-variant">Subject</th>
                        <th class="p-md font-label-md text-on-surface-variant">Details</th>
                        <th class="p-md font-label-md text-on-surface-variant">Submissions</th>
                        <th class="p-md font-label-md text-on-surface-variant">Status</th>
                        <th class="p-md font-label-md text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($quizzes as $quiz)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-md font-body-md text-on-surface font-semibold">{{ $quiz->title }}</td>
                            <td class="p-md font-body-md text-on-surface">
                                {{ $quiz->class->name ?? 'N/A' }}
                                @if($quiz->section) - {{ $quiz->section->name }} @endif
                            </td>
                            <td class="p-md font-body-md text-on-surface">{{ $quiz->subject->name ?? 'N/A' }}</td>
                            <td class="p-md font-body-md text-on-surface text-sm">
                                <div>Duration: {{ $quiz->duration_minutes }} min</div>
                                <div>Total: {{ $quiz->total_marks }} | Pass: {{ $quiz->passing_marks }}</div>
                            </td>
                            <td class="p-md font-body-md text-on-surface">
                                <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs font-semibold">
                                    {{ $quiz->attempts_count }} Attempts
                                </span>
                            </td>
                            <td class="p-md font-body-md">
                                @if($quiz->is_active)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Published</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Draft</span>
                                @endif
                            </td>
                            <td class="p-md">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.digital_learning.quizzes.questions', $quiz->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-100 hover:border-blue-200 rounded-lg transition-colors text-sm font-medium shadow-sm" title="Manage Questions">
                                        <span class="material-symbols-outlined text-[18px]">quiz</span>
                                        <span>Questions</span>
                                    </a>
                                    <a href="{{ route('admin.digital_learning.quizzes.results', $quiz->id) }}" class="flex items-center gap-1.5 px-3 py-1.5 bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-100 hover:border-purple-200 rounded-lg transition-colors text-sm font-medium shadow-sm" title="View Results">
                                        <span class="material-symbols-outlined text-[18px]">analytics</span>
                                        <span>Results</span>
                                    </a>
                                    <form action="{{ route('admin.digital_learning.quizzes.destroy', $quiz->id) }}" method="POST" class="inline" data-confirm="Delete this quiz?">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="flex items-center justify-center w-8 h-8 bg-red-50 text-red-600 hover:bg-red-100 border border-red-100 hover:border-red-200 rounded-lg transition-colors shadow-sm" title="Delete Quiz">
                                            <span class="material-symbols-outlined text-[18px]">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-md text-center text-on-surface-variant py-8">
                                No quizzes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Quiz Modal -->
<div id="createQuizModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center">
            <h2 class="font-headline-md text-on-surface">Create Quiz</h2>
            <button onclick="document.getElementById('createQuizModal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.digital_learning.quizzes.store') }}" method="POST" class="p-6 space-y-4">
            @csrf
            <div>
                <label class="block font-label-md text-on-surface mb-1">Title <span class="text-error">*</span></label>
                <input type="text" name="title" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Class <span class="text-error">*</span></label>
                    <select name="class_id" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="">Select Class</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Section (Optional)</label>
                    <select name="section_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="">All Sections</option>
                        @foreach($sections as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Subject <span class="text-error">*</span></label>
                    <select name="subject_id" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="">Select Subject</option>
                        @foreach($subjects as $sub)
                            <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Academic Year (Optional)</label>
                    <select name="academic_year_id" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                        <option value="">Select Year</option>
                        @foreach($academicYears as $ay)
                            <option value="{{ $ay->id }}">{{ $ay->year }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Duration (Minutes) <span class="text-error">*</span></label>
                    <input type="number" name="duration_minutes" min="1" value="30" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">Passing Marks <span class="text-error">*</span></label>
                    <input type="number" name="passing_marks" min="0" value="0" required class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
            </div>

            <div>
                <label class="block font-label-md text-on-surface mb-1">Description / Instructions</label>
                <textarea name="description" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="is_active" id="is_active" value="1" class="rounded border-outline-variant text-primary focus:ring-primary">
                <label for="is_active" class="font-body-md text-on-surface">Publish immediately</label>
            </div>

            <div class="flex justify-end gap-sm mt-6">
                <button type="button" onclick="document.getElementById('createQuizModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 font-label-md bg-primary text-on-primary hover:bg-primary/90 rounded-lg transition-colors">Create Quiz</button>
            </div>
        </form>
    </div>
</div>
@endsection
