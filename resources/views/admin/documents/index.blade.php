@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Issued Documents</h2>
            <p class="text-body-md text-secondary">Manage and view all documents generated for students.</p>
        </div>
        <div>
            <a href="{{ route('admin.documents.create') }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                <span class="material-symbols-outlined" data-icon="add">add</span>
                Generate Document
            </a>
        </div>
    </div>



<div class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant font-label-md">
                        <th class="p-md border-b border-outline-variant">Document No</th>
                        <th class="p-md border-b border-outline-variant">Student</th>
                        <th class="p-md border-b border-outline-variant">Template Type</th>
                        <th class="p-md border-b border-outline-variant">Purpose</th>
                        <th class="p-md border-b border-outline-variant">Issued At</th>
                        <th class="p-md border-b border-outline-variant">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-body-md text-on-surface divide-y divide-outline-variant">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-surface-container-lowest transition-colors">
                        <td class="p-md">{{ $doc->document_no }}</td>
                        <td class="p-md">
                            {{ $doc->student->first_name }} {{ $doc->student->last_name }}
                            <div class="text-label-md text-secondary">{{ $doc->student->admission_no }}</div>
                        </td>
                        <td class="p-md">
                            <span class="px-2 py-1 bg-secondary-container text-on-secondary-container rounded-full text-xs">
                                {{ $doc->template->name }}
                            </span>
                        </td>
                        <td class="p-md">{{ Str::limit($doc->purpose, 30) }}</td>
                        <td class="p-md">{{ $doc->issued_at->format('d M Y') }}</td>
                        <td class="p-md">
                            <a href="{{ route('admin.documents.download', $doc->id) }}" class="text-primary hover:underline flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[18px]">download</span> Download
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="p-md text-center text-secondary">No documents issued yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-md bg-surface-container-lowest border-t border-outline-variant">
            {{ $documents->links() }}
        </div>
    </div>
</div>
@endsection
