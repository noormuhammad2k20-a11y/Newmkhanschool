@extends('layouts.app')

@section('title', 'Student Lists')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div>
            <h2 class="text-headline-xl font-headline-xl text-on-surface">Student Lists</h2>
            <p class="text-body-lg font-body-lg text-secondary mt-1">View and manage students in your assigned classes.</p>
        </div>

        <!-- Filter Form -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md">
            <form action="{{ route('teacher.students') }}" method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-md items-end">
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Filter by Class</label>
                    <select name="class_id" class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface">
                        <option value="">-- All Assigned Classes --</option>
                        @foreach($classes as $c)
                            <option value="{{ $c->id }}" {{ $selectedClass == $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-label-md font-label-md text-on-surface mb-2">Search Student</label>
                    <input type="text" name="search" value="{{ $search }}" placeholder="Name or Roll No..." class="w-full bg-surface-bright border border-outline-variant rounded-lg p-2 text-body-md text-on-surface">
                </div>
                <div>
                    <button type="submit" class="w-full bg-primary text-on-primary rounded-lg py-2 text-label-md font-label-md hover:bg-primary-dark transition-colors">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Students Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">Roll Number</th>
                            <th class="py-3 px-4 font-semibold">Name</th>
                            <th class="py-3 px-4 font-semibold">Gender</th>
                            <th class="py-3 px-4 font-semibold">Status</th>
                            <th class="py-3 px-4 font-semibold text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @forelse($students as $student)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 text-secondary">{{ $student->admission_no }}</td>
                            <td class="py-3 px-4 font-medium text-on-surface flex items-center gap-3">
                                @if($student->photo)
                                    <img src="{{ asset('storage/' . $student->photo) }}" class="w-8 h-8 rounded-full object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-full bg-surface-variant flex items-center justify-center text-on-surface-variant font-bold text-xs">
                                        {{ substr($student->first_name, 0, 1) }}
                                    </div>
                                @endif
                                {{ $student->first_name }} {{ $student->last_name }}
                            </td>
                            <td class="py-3 px-4 text-secondary">{{ $student->gender }}</td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-800 rounded text-xs font-medium">{{ $student->status }}</span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <button class="text-primary hover:text-primary-dark" title="View Profile (Demo)">
                                    <span class="material-symbols-rounded text-[20px]">visibility</span>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-secondary">
                                No students found.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
@endsection
