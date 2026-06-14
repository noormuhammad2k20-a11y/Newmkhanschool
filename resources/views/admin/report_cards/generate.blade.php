@extends('layouts.app')

@section('title', 'Generate Report Cards')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 rounded-full bg-surface-container flex items-center justify-center text-secondary hover:bg-surface-container-high transition-colors">
                <span class="material-symbols-outlined">arrow_back</span>
            </a>
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Report Card Generation</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Generate report cards for a class and exam type.</p>
            </div>
        </div>

        <!-- Generation Form Card -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-primary-fixed flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-[22px]">note_add</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Generate Report Cards</h3>
            </div>
            <form action="{{ route('admin.reportcards.generate') }}" method="POST" class="p-md" data-ajax-form>
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label for="rc-class-id" class="block text-label-md font-label-md text-secondary mb-2">Class</label>
                        <select name="class_id" id="rc-class-id" class="input-field" required>
                            <option value="">Select Class</option>
                            @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label for="rc-exam-type" class="block text-label-md font-label-md text-secondary mb-2">Exam Type</label>
                        <select name="exam_type_id" id="rc-exam-type" class="input-field" required>
                            <option value="">Select Exam Type</option>
                            @foreach($examTypes as $et)
                                <option value="{{ $et->id }}">{{ $et->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn-primary gap-2 w-full md:w-auto">
                            <span class="material-symbols-outlined text-[18px]">auto_awesome</span>
                            Generate Report Cards
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Recent Report Cards Table -->
        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col overflow-hidden">
            <div class="p-md border-b border-outline-variant bg-surface-bright flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-secondary-container flex items-center justify-center text-on-secondary-container">
                    <span class="material-symbols-outlined text-[22px]">description</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface">Generated Report Cards</h3>
            </div>
            <div class="overflow-x-auto">
                @if($recentCards->count() > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low text-label-md font-label-md text-secondary border-b border-outline-variant">
                            <th class="py-3 px-4 font-semibold">#</th>
                            <th class="py-3 px-4 font-semibold">Student</th>
                            <th class="py-3 px-4 font-semibold">Adm No</th>
                            <th class="py-3 px-4 font-semibold">Exam Type</th>
                            <th class="py-3 px-4 font-semibold">Percentage</th>
                            <th class="py-3 px-4 font-semibold">Grade</th>
                            <th class="py-3 px-4 font-semibold">Rank</th>
                            <th class="py-3 px-4 font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="text-body-md font-body-md">
                        @foreach($recentCards as $i => $card)
                        <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors">
                            <td class="py-3 px-4 text-secondary">{{ $i + 1 }}</td>
                            <td class="py-3 px-4 font-medium text-on-surface">{{ $card->student->first_name ?? '' }} {{ $card->student->last_name ?? '' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $card->student->admission_no ?? '—' }}</td>
                            <td class="py-3 px-4 text-secondary">{{ $card->examType->name ?? '—' }}</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-surface-variant rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $card->total_percentage >= 50 ? 'bg-emerald-500' : 'bg-error' }}" style="width: {{ min($card->total_percentage, 100) }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold">{{ $card->total_percentage }}%</span>
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ $card->grade == 'A+' || $card->grade == 'A' ? 'bg-emerald-100 text-emerald-700' :
                                       ($card->grade == 'B' ? 'bg-blue-100 text-blue-700' :
                                       ($card->grade == 'C' ? 'bg-amber-100 text-amber-700' :
                                       ($card->grade == 'D' ? 'bg-orange-100 text-orange-700' : 'bg-red-100 text-red-700'))) }}">
                                    {{ $card->grade }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-on-surface font-medium">#{{ $card->rank }}</td>
                            <td class="py-3 px-4">
                                <a href="{{ route('admin.reportcards.pdf', $card->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 bg-error/10 text-error rounded-lg text-xs font-semibold hover:bg-error/20 transition-colors">
                                    <span class="material-symbols-outlined text-[14px]">picture_as_pdf</span>
                                    PDF
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <div class="text-center py-16">
                    <span class="material-symbols-outlined text-[56px] text-secondary opacity-40">description</span>
                    <p class="text-body-lg font-body-lg text-secondary mt-4">No report cards generated yet.</p>
                    <p class="text-body-md font-body-md text-secondary mt-1">Use the form above to generate report cards.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection
