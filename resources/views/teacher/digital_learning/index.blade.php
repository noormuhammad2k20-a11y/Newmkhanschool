@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-max-width mx-auto">
        
        <div class="flex justify-between items-end mb-lg">
            <div>
                <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">Digital Learning Hub</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-sm">Manage your class notes, study materials, and interactive quizzes.</p>
            </div>
            <div class="flex gap-sm">
                <button onclick="document.getElementById('upload-note-modal').classList.remove('hidden')" class="bg-primary hover:bg-primary-container text-on-primary font-label-md py-2 px-4 rounded-full shadow transition-colors flex items-center gap-2">
                    <span class="material-symbols-rounded">upload_file</span>
                    Upload Note
                </button>
                <button onclick="document.getElementById('create-quiz-modal').classList.remove('hidden')" class="bg-surface-container-high hover:bg-surface-container-highest text-on-surface font-label-md py-2 px-4 rounded-full shadow transition-colors flex items-center gap-2 border border-outline">
                    <span class="material-symbols-rounded">quiz</span>
                    Create Quiz
                </button>
            </div>
        </div>

@if ($errors->any())
            <div class="bg-error-container text-error px-4 py-3 rounded relative mb-4 shadow-sm" role="alert">
                <ul class="list-disc pl-5 font-body-md">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
            <!-- Digital Notes -->
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex flex-col">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                    <h3 class="font-headline-md text-headline-md font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-primary">menu_book</span>
                        Recent Notes
                    </h3>
                </div>
                <div class="p-lg flex-1 overflow-y-auto">
                    @forelse($notes as $note)
                    <div class="bg-surface rounded-lg p-md mb-md border border-outline-variant hover:shadow-sm transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-headline-sm text-on-surface">{{ $note->title }}</h4>
                                <p class="text-secondary font-body-sm mb-sm">{{ $note->class->name ?? 'Class' }} - {{ $note->subject->name ?? 'Subject' }}</p>
                            </div>
                            <span class="bg-primary-container text-on-primary-container px-2 py-1 rounded text-label-sm font-semibold uppercase">
                                {{ $note->file_type }}
                            </span>
                        </div>
                        <p class="text-on-surface-variant font-body-md mb-sm line-clamp-2">{{ $note->description }}</p>
                        <div class="flex justify-between items-center text-label-md">
                            <span class="text-secondary flex items-center gap-1">
                                <span class="material-symbols-rounded text-[16px]">download</span> {{ $note->download_count }} downloads
                            </span>
                            <span class="text-secondary">{{ $note->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-xl text-secondary">
                        <span class="material-symbols-rounded text-[48px] mb-sm opacity-50">note_stack</span>
                        <p class="font-body-lg">No digital notes uploaded yet.</p>
                        <button onclick="document.getElementById('upload-note-modal').classList.remove('hidden')" class="text-primary font-semibold mt-sm hover:underline">Upload your first note</button>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Quizzes -->
            <div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden flex flex-col">
                <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                    <h3 class="font-headline-md text-headline-md font-bold text-on-surface flex items-center gap-2">
                        <span class="material-symbols-rounded text-[#b26b00]">quiz</span>
                        Active Quizzes
                    </h3>
                </div>
                <div class="p-lg flex-1 overflow-y-auto">
                    @forelse($quizzes as $quiz)
                    <div class="bg-surface rounded-lg p-md mb-md border border-outline-variant hover:shadow-sm transition-shadow">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="font-headline-sm text-on-surface">{{ $quiz->title }}</h4>
                                <p class="text-secondary font-body-sm mb-sm">{{ $quiz->class->name ?? 'Class' }} - {{ $quiz->subject->name ?? 'Subject' }}</p>
                            </div>
                            @if($quiz->is_active)
                                <span class="bg-[#006e1c]/10 text-[#006e1c] px-2 py-1 rounded text-label-sm font-semibold uppercase flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-[#006e1c]"></span> Active
                                </span>
                            @else
                                <span class="bg-surface-container-high text-secondary px-2 py-1 rounded text-label-sm font-semibold uppercase">
                                    Draft
                                </span>
                            @endif
                        </div>
                        <p class="text-on-surface-variant font-body-md mb-sm line-clamp-2">{{ $quiz->description }}</p>
                        <div class="flex justify-between items-center text-label-md">
                            <span class="text-secondary flex items-center gap-1">
                                <span class="material-symbols-rounded text-[16px]">timer</span> {{ $quiz->duration_minutes }} mins
                            </span>
                            <span class="text-secondary flex items-center gap-1">
                                <span class="material-symbols-rounded text-[16px]">grade</span> {{ $quiz->total_marks }} marks
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-xl text-secondary">
                        <span class="material-symbols-rounded text-[48px] mb-sm opacity-50">help_center</span>
                        <p class="font-body-lg">No quizzes created yet.</p>
                        <button onclick="document.getElementById('create-quiz-modal').classList.remove('hidden')" class="text-primary font-semibold mt-sm hover:underline">Create your first quiz</button>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Upload Note Modal -->
<div id="upload-note-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-lg flex flex-col max-h-[90vh]">
        <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="font-headline-sm font-bold text-on-surface">Upload Digital Note</h3>
            <button onclick="document.getElementById('upload-note-modal').classList.add('hidden')" class="text-secondary hover:text-on-surface cursor-pointer">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('teacher.digital_learning.notes.store') }}" method="POST" enctype="multipart/form-data" class="p-lg overflow-y-auto">
            @csrf
            <div class="space-y-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Title <span class="text-error">*</span></label>
                    <input type="text" name="title" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Class <span class="text-error">*</span></label>
                        <select name="class_id" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Subject <span class="text-error">*</span></label>
                        <select name="subject_id" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Description</label>
                    <textarea name="description" rows="3" class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">File Upload (PDF, DOCX, PPT)</label>
                    <input type="file" name="file" class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-container file:text-primary hover:file:bg-primary-container-hover">
                </div>
                <div class="text-center font-label-sm text-secondary">OR</div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">External Link (YouTube, Drive)</label>
                    <input type="url" name="external_url" placeholder="https://" class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
            </div>
            <div class="mt-lg flex justify-end gap-sm">
                <button type="button" onclick="document.getElementById('upload-note-modal').classList.add('hidden')" class="px-4 py-2 rounded-full font-label-md text-secondary hover:bg-surface-container-high transition-colors">Cancel</button>
                <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-full font-label-md shadow transition-colors">Upload</button>
            </div>
        </form>
    </div>
</div>

<!-- Create Quiz Modal -->
<div id="create-quiz-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <div class="bg-surface-container-lowest w-full max-w-md rounded-2xl shadow-lg flex flex-col max-h-[90vh]">
        <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="font-headline-sm font-bold text-on-surface">Create New Quiz</h3>
            <button onclick="document.getElementById('create-quiz-modal').classList.add('hidden')" class="text-secondary hover:text-on-surface cursor-pointer">
                <span class="material-symbols-rounded">close</span>
            </button>
        </div>
        <form action="{{ route('teacher.digital_learning.quizzes.store') }}" method="POST" class="p-lg overflow-y-auto">
            @csrf
            <div class="space-y-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Quiz Title <span class="text-error">*</span></label>
                    <input type="text" name="title" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Class <span class="text-error">*</span></label>
                        <select name="class_id" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Subject <span class="text-error">*</span></label>
                        <select name="subject_id" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Description / Instructions</label>
                    <textarea name="description" rows="3" class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-sm">
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Duration (mins) <span class="text-error">*</span></label>
                        <input type="number" name="duration_minutes" value="30" min="5" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                    <div>
                        <label class="block font-label-md text-on-surface mb-xs">Total Marks</label>
                        <input type="number" name="total_marks" value="10" min="1" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                    </div>
                </div>
                <div>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_active" class="w-4 h-4 text-primary bg-surface-container border-outline rounded focus:ring-primary" checked>
                        <span class="font-body-md text-on-surface">Active immediately</span>
                    </label>
                </div>
            </div>
            <div class="mt-lg flex justify-end gap-sm">
                <button type="button" onclick="document.getElementById('create-quiz-modal').classList.add('hidden')" class="px-4 py-2 rounded-full font-label-md text-secondary hover:bg-surface-container-high transition-colors">Cancel</button>
                <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-full font-label-md shadow transition-colors">Create</button>
            </div>
        </form>
    </div>
</div>
@endsection
