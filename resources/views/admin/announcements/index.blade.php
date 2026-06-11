@extends('layouts.app')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface p-lg">
    <div class="max-w-max-width mx-auto">
        
        <div class="flex justify-between items-end mb-lg">
            <div>
                <h2 class="font-headline-xl text-headline-xl font-bold text-on-surface">Announcements</h2>
                <p class="font-body-lg text-body-lg text-on-surface-variant mt-sm">Manage broadcast messages and notices for your school.</p>
            </div>
            <div>
                <button onclick="document.getElementById('create-modal').classList.remove('hidden')" class="bg-primary hover:bg-primary-container text-on-primary font-label-md py-2 px-4 rounded-full shadow transition-colors flex items-center gap-2">
                    <span class="material-symbols-outlined">add</span>
                    New Announcement
                </button>
            </div>
        </div>

<div class="bg-surface-container-lowest rounded-xl shadow-sm border border-outline-variant overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-secondary font-label-md uppercase tracking-wider border-b border-outline-variant">
                            <th class="p-md font-semibold">Title</th>
                            <th class="p-md font-semibold">Content</th>
                            <th class="p-md font-semibold">Target Audience</th>
                            <th class="p-md font-semibold">Author</th>
                            <th class="p-md font-semibold">Date</th>
                            <th class="p-md font-semibold text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant font-body-md">
                        @forelse($announcements as $ann)
                        <tr class="hover:bg-surface-container-lowest transition-colors">
                            <td class="p-md font-semibold text-on-surface">{{ $ann->title }}</td>
                            <td class="p-md text-on-surface-variant max-w-xs truncate" title="{{ $ann->content }}">{{ $ann->content }}</td>
                            <td class="p-md">
                                <span class="bg-surface-container px-2 py-1 rounded text-label-sm uppercase">{{ $ann->target_role }}</span>
                            </td>
                            <td class="p-md text-secondary">{{ $ann->author_name }}</td>
                            <td class="p-md text-secondary">{{ \Carbon\Carbon::parse($ann->created_at)->format('M d, Y') }}</td>
                            <td class="p-md text-right">
                                <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST" data-confirm="Are you sure you want to delete this announcement?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-error hover:text-[#93000a] p-1 rounded transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-xl text-center text-secondary">
                                <span class="material-symbols-outlined text-[48px] mb-sm opacity-50">campaign</span>
                                <p class="font-body-lg">No announcements found.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($announcements->hasPages())
            <div class="p-md border-t border-outline-variant bg-surface-container-lowest">
                {{ $announcements->links('pagination::tailwind') }}
            </div>
            @endif
        </div>

    </div>
</main>

<!-- Create Modal -->
<div id="create-modal" class="fixed inset-0 z-50 hidden bg-black/50 flex items-center justify-center">
    <div class="bg-surface-container-lowest w-full max-w-lg rounded-2xl shadow-lg flex flex-col max-h-[90vh]">
        <div class="px-lg py-md border-b border-outline-variant flex justify-between items-center">
            <h3 class="font-headline-sm font-bold text-on-surface">New Announcement</h3>
            <button onclick="document.getElementById('create-modal').classList.add('hidden')" class="text-secondary hover:text-on-surface cursor-pointer">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>
        <form action="{{ route('admin.announcements.store') }}" method="POST" class="p-lg overflow-y-auto">
            @csrf
            <div class="space-y-md">
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Title <span class="text-error">*</span></label>
                    <input type="text" name="title" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Target Audience <span class="text-error">*</span></label>
                    <select name="target_role" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                        <option value="all">All Users</option>
                        <option value="teacher">Teachers Only</option>
                        <option value="student">Students Only</option>
                        <option value="parent">Parents Only</option>
                    </select>
                </div>
                <div>
                    <label class="block font-label-md text-on-surface mb-xs">Content <span class="text-error">*</span></label>
                    <textarea name="content" rows="4" required class="w-full bg-surface-container p-sm rounded-lg border border-outline focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
                </div>
            </div>
            <div class="mt-lg flex justify-end gap-sm">
                <button type="button" onclick="document.getElementById('create-modal').classList.add('hidden')" class="px-4 py-2 rounded-full font-label-md text-secondary hover:bg-surface-container-high transition-colors">Cancel</button>
                <button type="submit" class="bg-primary hover:bg-primary-container text-on-primary px-4 py-2 rounded-full font-label-md shadow transition-colors">Publish</button>
            </div>
        </form>
    </div>
</div>
@endsection
