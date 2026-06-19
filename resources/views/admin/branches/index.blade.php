@extends('layouts.app')

@section('content')
<div class="px-md py-lg">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Multi-Branch Management</h2>
            <p class="text-body-md text-secondary">Manage and switch between school branches.</p>
        </div>
        <div>
            <a href="{{ route('admin.branches.create') }}" class="inline-flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors">
                <span class="material-symbols-rounded" data-icon="add">add</span>
                Add Branch
            </a>
        </div>
    </div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-lg">
        <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
            <h3 class="text-label-md text-secondary uppercase tracking-wider mb-xs">Total Students</h3>
            <p class="text-display-sm font-display-sm text-on-surface">{{ number_format($totalStudents) }}</p>
            <p class="text-xs text-secondary mt-xs">Across all branches</p>
        </div>
        <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
            <h3 class="text-label-md text-secondary uppercase tracking-wider mb-xs">Total Teachers</h3>
            <p class="text-display-sm font-display-sm text-on-surface">{{ number_format($totalTeachers) }}</p>
            <p class="text-xs text-secondary mt-xs">Across all branches</p>
        </div>
        <div class="bg-surface border border-outline-variant rounded-xl p-md shadow-sm">
            <h3 class="text-label-md text-secondary uppercase tracking-wider mb-xs">Total Revenue</h3>
            <p class="text-display-sm font-display-sm text-on-surface">{{ number_format($totalRevenue, 2) }}</p>
            <p class="text-xs text-secondary mt-xs">Paid fees across all branches</p>
        </div>
    </div>

    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
            <h3 class="text-headline-md font-headline-md text-on-surface">Branch List</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                        <th class="py-3 px-4 font-semibold">Branch Code</th>
                        <th class="py-3 px-4 font-semibold">Name</th>
                        <th class="py-3 px-4 font-semibold">Students</th>
                        <th class="py-3 px-4 font-semibold">Teachers</th>
                        <th class="py-3 px-4 font-semibold">Principal</th>
                        <th class="py-3 px-4 font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-body-md font-body-md text-on-surface">
                    @forelse($branches as $branch)
                    <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors {{ session('active_branch_id') == $branch->id ? 'bg-primary-container/20' : '' }}">
                        <td class="py-3 px-4 font-mono text-sm">{{ $branch->branch_code ?? 'N/A' }}</td>
                        <td class="py-3 px-4 font-semibold">
                            {{ $branch->name }}
                            @if(session('active_branch_id') == $branch->id)
                                <span class="ml-sm text-xs bg-primary text-on-primary px-2 py-0.5 rounded">Active</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">{{ $branch->students_count }}</td>
                        <td class="py-3 px-4">{{ $branch->teachers_count }}</td>
                        <td class="py-3 px-4">{{ $branch->principal_name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 flex items-center gap-sm">
                            <form method="POST" action="{{ route('admin.branches.switch') }}" class="inline">
                                @csrf
                                <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                                <button type="submit" class="text-primary hover:text-on-primary-fixed-variant" title="Switch to this branch context">
                                    <span class="material-symbols-rounded text-[20px]">swap_horiz</span>
                                </button>
                            </form>
                            <a href="{{ route('admin.branches.show', $branch->id) }}" class="text-secondary hover:text-on-surface" title="View"><span class="material-symbols-rounded text-[20px]">visibility</span></a>
                            <a href="{{ route('admin.branches.edit', $branch->id) }}" class="text-secondary hover:text-on-surface" title="Edit"><span class="material-symbols-rounded text-[20px]">edit</span></a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-4 text-center text-secondary">No branches found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
