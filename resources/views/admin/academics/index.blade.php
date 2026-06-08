@extends('layouts.app')

@section('title', 'Classes & Subjects Management')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-on-surface">Classes & Subjects</h2>
            <p class="text-body-md font-body-md text-secondary mt-1">Manage academic classes and their associated subjects.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-[#e8f5e9] text-[#2e7d32] px-4 py-3 rounded-lg mb-lg font-body-md border border-[#c8e6c9]">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-error-container text-on-error-container px-4 py-3 rounded-lg mb-lg font-body-md border border-error">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-lg">
        
        <!-- Classes Section -->
        <div class="flex flex-col gap-md">
            <!-- Add Class Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm">
                <h3 class="text-headline-md font-headline-md text-on-surface mb-md">Add New Class</h3>
                <form action="{{ route('admin.academics.classes.store') }}" method="POST" class="flex gap-sm items-end">
                    @csrf
                    <div class="flex-1">
                        <label class="block text-label-md font-label-md text-secondary mb-1">Class Name</label>
                        <input name="name" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. Grade 10" type="text" required />
                    </div>
                    <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center justify-center h-[38px]">
                        <span class="material-symbols-outlined text-[18px] mr-1" data-icon="add">add</span> Add
                    </button>
                </form>
            </div>

            <!-- Classes List -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm flex-1 flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-container-low">
                    <h3 class="text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Existing Classes</h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface border-b border-outline-variant">
                                <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Class Name</th>
                                <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse($classes as $class)
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="py-3 px-4 text-body-md font-body-md text-on-surface font-medium">{{ $class->name }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <form action="{{ route('admin.academics.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Are you sure? This will delete all subjects linked to this class.');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error hover:text-on-error-container transition-colors p-1.5 rounded-md hover:bg-error-container flex items-center justify-center" title="Delete Class">
                                                <span class="material-symbols-outlined text-[20px]" data-icon="delete">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="py-8 text-center text-secondary text-body-md">No classes found. Add one above.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Subjects Section -->
        <div class="flex flex-col gap-md">
            <!-- Add Subject Card -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md shadow-sm">
                <h3 class="text-headline-md font-headline-md text-on-surface mb-md">Add New Subject</h3>
                <form action="{{ route('admin.academics.subjects.store') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-sm items-end">
                    @csrf
                    <div>
                        <label class="block text-label-md font-label-md text-secondary mb-1">Subject Name</label>
                        <input name="name" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. Mathematics" type="text" required />
                    </div>
                    <div>
                        <label class="block text-label-md font-label-md text-secondary mb-1">Code (Optional)</label>
                        <input name="code" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="e.g. MTH101" type="text" />
                    </div>
                    <div>
                        <label class="block text-label-md font-label-md text-secondary mb-1">Assign to Class</label>
                        <select name="class_id" class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface" required>
                            <option value="">Select Class...</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="md:col-span-3 flex justify-end mt-2">
                        <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px] mr-1" data-icon="add">add</span> Add Subject
                        </button>
                    </div>
                </form>
            </div>

            <!-- Subjects List -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm flex-1 flex flex-col">
                <div class="p-md border-b border-outline-variant bg-surface-container-low">
                    <h3 class="text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Existing Subjects</h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface border-b border-outline-variant">
                                <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Subject</th>
                                <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Code</th>
                                <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium">Class</th>
                                <th class="py-2 px-4 text-label-md font-label-md text-secondary font-medium text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse($subjects as $subject)
                                <tr class="hover:bg-surface-container-low transition-colors">
                                    <td class="py-3 px-4 text-body-md font-body-md text-on-surface font-medium">{{ $subject->name }}</td>
                                    <td class="py-3 px-4 text-body-md font-body-md text-secondary">{{ $subject->code ?: '-' }}</td>
                                    <td class="py-3 px-4 text-body-md font-body-md text-primary">{{ $subject->class_name }}</td>
                                    <td class="py-3 px-4 text-right">
                                        <form action="{{ route('admin.academics.subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subject?');" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-error hover:text-on-error-container transition-colors p-1.5 rounded-md hover:bg-error-container flex items-center justify-center" title="Delete Subject">
                                                <span class="material-symbols-outlined text-[20px]" data-icon="delete">delete</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-secondary text-body-md">No subjects found. Add one above.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</main>
@endsection
