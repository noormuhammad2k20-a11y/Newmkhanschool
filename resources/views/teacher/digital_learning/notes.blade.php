@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6 md:p-8 space-y-6">
    <div class="flex justify-between items-center">
        <div>
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Digital Notes</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Manage digital learning materials for students.</p>
        </div>
        <button onclick="document.getElementById('createNoteModal').classList.remove('hidden')" class="flex items-center gap-sm px-md py-sm bg-primary text-on-primary rounded-full hover:bg-primary/90 transition-colors">
            <span class="material-symbols-outlined text-[20px]">add</span>
            <span class="font-label-md font-semibold">Upload Note</span>
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 bg-green-100 text-green-800 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="p-md font-label-md text-on-surface-variant">Title</th>
                        <th class="p-md font-label-md text-on-surface-variant">Class & Section</th>
                        <th class="p-md font-label-md text-on-surface-variant">Subject</th>
                        <th class="p-md font-label-md text-on-surface-variant">Uploaded By</th>
                        <th class="p-md font-label-md text-on-surface-variant">Status</th>
                        <th class="p-md font-label-md text-on-surface-variant text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant">
                    @forelse($notes as $note)
                        <tr class="hover:bg-surface-container-low/50 transition-colors">
                            <td class="p-md font-body-md text-on-surface">
                                <div class="font-semibold">{{ $note->title }}</div>
                                <div class="text-sm text-on-surface-variant">{{ $note->file_type }}</div>
                            </td>
                            <td class="p-md font-body-md text-on-surface">
                                {{ $note->class->name ?? 'N/A' }} 
                                @if($note->section) - {{ $note->section->name }} @endif
                            </td>
                            <td class="p-md font-body-md text-on-surface">{{ $note->subject->name ?? 'N/A' }}</td>
                            <td class="p-md font-body-md text-on-surface">{{ $note->uploader->name ?? 'N/A' }}</td>
                            <td class="p-md font-body-md">
                                @if($note->is_public)
                                    <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Published</span>
                                @else
                                    <span class="px-2 py-1 bg-yellow-100 text-yellow-800 text-xs rounded-full">Draft</span>
                                @endif
                            </td>
                            <td class="p-md text-right">
                                <button onclick="openEditNoteModal({{ $note->id }})" class="text-primary hover:text-primary/80 mr-2">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </button>
                                <form action="{{ route('teacher.digital_notes.destroy', $note->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this note?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-error hover:text-error/80">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="p-md text-center text-on-surface-variant py-8">
                                No digital notes found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Note Modal -->
<div id="createNoteModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-surface-container-lowest rounded-2xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="p-6 border-b border-outline-variant flex justify-between items-center">
            <h2 class="font-headline-md text-on-surface">Upload Digital Note</h2>
            <button onclick="document.getElementById('createNoteModal').classList.add('hidden')" class="text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('teacher.digital_notes.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
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

            <div>
                <label class="block font-label-md text-on-surface mb-1">Description</label>
                <textarea name="description" rows="3" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-label-md text-on-surface mb-1">File Upload</label>
                    <input type="file" name="file" class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-1">OR External URL</label>
                    <input type="url" name="external_url" placeholder="https://..." class="w-full bg-surface-container-low border border-outline-variant rounded-lg px-4 py-2 text-on-surface focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary">
                </div>
            </div>

            <div class="flex items-center gap-2 mt-4">
                <input type="checkbox" name="is_public" id="is_public" value="1" class="rounded border-outline-variant text-primary focus:ring-primary">
                <label for="is_public" class="font-body-md text-on-surface">Publish immediately (Students can view)</label>
            </div>

            <div class="flex justify-end gap-sm mt-6">
                <button type="button" onclick="document.getElementById('createNoteModal').classList.add('hidden')" class="px-4 py-2 font-label-md text-on-surface-variant hover:bg-surface-container-low rounded-lg transition-colors">Cancel</button>
                <button type="submit" class="px-4 py-2 font-label-md bg-primary text-on-primary hover:bg-primary/90 rounded-lg transition-colors">Upload Note</button>
            </div>
        </form>
    </div>
</div>

<script>
    function openEditNoteModal(id) {
        // Implement edit logic if necessary via a full page or JS populated modal
        alert('Edit functionality can be implemented using a similar modal populated via JS fetch or redirecting to an edit view.');
    }
</script>
@endsection
