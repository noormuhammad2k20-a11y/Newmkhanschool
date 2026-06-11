@extends('layouts.app')

@section('title', 'Assignments')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex justify-between items-end">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Assignments</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Manage class assignments and track submissions.</p>
            </div>
            <button onclick="document.getElementById('createModal').classList.remove('hidden'); document.body.style.overflow = 'hidden';" class="bg-primary text-on-primary px-4 py-2 rounded-lg text-label-md font-label-md hover:bg-primary-dark flex items-center gap-2">
                <span class="material-symbols-outlined">add</span> Create
            </button>
        </div>

        @if(session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50" role="alert">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50" role="alert">
            {{ session('error') }}
        </div>
        @endif

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Title</th>
                            <th class="py-3 px-4 font-semibold">Class</th>
                            <th class="py-3 px-4 font-semibold">Subject</th>
                            <th class="py-3 px-4 font-semibold">Due Date</th>
                            <th class="py-3 px-4 font-semibold text-center">Submissions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($assignments as $assignment)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 font-medium text-on-surface">{{ $assignment->title }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $assignment->class_->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $assignment->subject->name ?? 'N/A' }}</td>
                            <td class="py-3 px-4">
                                @if(\Carbon\Carbon::parse($assignment->due_date)->isPast())
                                    <span class="text-error font-medium">{{ \Carbon\Carbon::parse($assignment->due_date)->format('d M, Y') }}</span>
                                @else
                                    <span class="text-secondary">{{ \Carbon\Carbon::parse($assignment->due_date)->format('d M, Y') }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <a href="{{ route('teacher.assignments.submissions', $assignment->id) }}" class="bg-primary-container text-on-primary-container hover:bg-primary hover:text-on-primary px-3 py-1.5 rounded-lg text-xs font-medium transition-colors inline-flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">visibility</span> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-secondary">
                                No assignments created yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div id="createModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-surface-container-lowest rounded-xl max-w-lg w-full">
            <div class="p-6 border-b border-outline-variant flex justify-between items-center">
                <h3 class="text-headline-sm font-headline-sm text-on-surface">Create Assignment</h3>
                <button onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="text-secondary hover:text-on-surface">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            <form action="{{ route('teacher.assignments.store') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="type" value="assignment">
                
                <div>
                    <label class="block text-label-md text-on-surface mb-1">Title</label>
                    <input type="text" name="title" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                </div>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Class</label>
                        <select name="class_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Select Class</option>
                            @foreach($classes as $c)
                                <option value="{{ $c->id }}">{{ $c->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-md text-on-surface mb-1">Subject</label>
                        <select name="subject_id" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                            <option value="">Select Subject</option>
                            @foreach($subjects as $s)
                                <option value="{{ $s->id }}">{{ $s->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-label-md text-on-surface mb-1">Due Date</label>
                    <input type="date" name="due_date" required class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface">
                </div>

                <div>
                    <label class="block text-label-md text-on-surface mb-1">Description / Instructions</label>
                    <textarea name="description" rows="4" class="w-full bg-surface-bright border border-outline-variant rounded p-2 text-body-md text-on-surface"></textarea>
                </div>

                <div class="pt-4 flex justify-end gap-2">
                    <button type="button" onclick="document.getElementById('createModal').classList.add('hidden'); document.body.style.overflow = '';" class="px-4 py-2 border border-outline-variant rounded text-on-surface hover:bg-surface-container-low transition-colors">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-primary text-on-primary rounded hover:bg-primary-dark transition-colors">Create</button>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection
