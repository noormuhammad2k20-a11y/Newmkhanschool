@extends('layouts.app')

@section('title', 'Online Exams')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
            <div>
                <h2 class="text-headline-lg font-headline-lg text-on-surface">Online Exams</h2>
                <p class="text-body-md font-body-md text-secondary mt-1">Viewing exam attempts for {{ $student->first_name }} {{ $student->last_name }}</p>
            </div>
            <a href="{{ route('parent.children') }}" class="bg-surface border border-outline-variant text-on-surface px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[18px] mr-1">arrow_back</span>
                Back to Children
            </a>
        </div>

        <div class="bg-surface-container-lowest border border-outline-variant rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col mb-lg">
            <div class="p-6 border-b border-outline-variant bg-surface-bright">
                <h3 class="text-title-lg font-title-lg text-on-surface flex items-center gap-2">
                    <span class="material-symbols-outlined text-primary">history</span>
                    Exam Attempts History
                </h3>
            </div>
            @if(isset($attempts) && count($attempts) > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-surface border-b border-outline-variant">
                            <tr>
                                <th class="py-3 px-6 text-label-md font-label-md text-secondary font-medium">Exam Title</th>
                                <th class="py-3 px-6 text-label-md font-label-md text-secondary font-medium">Subject</th>
                                <th class="py-3 px-6 text-label-md font-label-md text-secondary font-medium">Score / Total</th>
                                <th class="py-3 px-6 text-label-md font-label-md text-secondary font-medium">Percentage</th>
                                <th class="py-3 px-6 text-label-md font-label-md text-secondary font-medium">Submitted At</th>
                                <th class="py-3 px-6 text-label-md font-label-md text-secondary font-medium text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @foreach($attempts as $attempt)
                            <tr class="hover:bg-surface-container-low transition-colors">
                                <td class="py-4 px-6 text-body-md font-body-md font-medium text-on-surface">{{ $attempt->onlineExam->title }}</td>
                                <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">{{ $attempt->onlineExam->subject->name ?? 'N/A' }}</td>
                                <td class="py-4 px-6 text-body-md font-body-md font-semibold text-on-surface">{{ $attempt->score }} / {{ $attempt->total_marks }}</td>
                                <td class="py-4 px-6">
                                    @if($attempt->total_marks > 0)
                                        @php $pct = round(($attempt->score / $attempt->total_marks) * 100); @endphp
                                        <div class="flex items-center gap-2">
                                            <div class="w-full bg-surface-container h-2 rounded-full overflow-hidden">
                                                <div class="h-full rounded-full {{ $pct >= 50 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $pct }}%"></div>
                                            </div>
                                            <span class="text-label-sm font-label-sm font-medium {{ $pct >= 50 ? 'text-emerald-700' : 'text-red-700' }}">{{ $pct }}%</span>
                                        </div>
                                    @else
                                        <span class="text-body-md font-body-md text-on-surface-variant">N/A</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-body-md font-body-md text-on-surface-variant">{{ \Carbon\Carbon::parse($attempt->submitted_at)->format('d M Y, h:i A') }}</td>
                                <td class="py-4 px-6 text-right">
                                    <a href="{{ route('parent.child.online-exams.result', [$student->id, $attempt->online_exam_id]) }}" class="inline-flex items-center justify-center gap-1 px-3 py-1.5 bg-primary hover:bg-primary/90 text-on-primary rounded-md text-sm font-medium transition-colors shadow-sm">
                                        <span class="material-symbols-outlined text-[16px]">visibility</span>
                                        View Result
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-xl text-center shadow-sm m-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-surface-container-low mb-4 text-secondary">
                        <span class="material-symbols-outlined text-3xl">assignment_off</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md text-on-surface">No Exam Attempts</h3>
                    <p class="text-body-md font-body-md text-secondary mt-1">There are no online exam attempts recorded yet.</p>
                </div>
            @endif
        </div>
    </div>
</main>
@endsection
