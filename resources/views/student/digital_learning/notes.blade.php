@extends('layouts.app')
@section('title', 'Digital Notes')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-headline-xl font-headline-xl text-on-surface">Digital Notes</h2>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Access your learning materials and study resources.</p>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary text-[20px]">search</span>
                    <input type="text" placeholder="Search notes..." class="pl-10 pr-4 py-2 border border-outline-variant rounded-xl bg-surface-container-lowest text-on-surface focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all w-64">
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-md">
            {{-- Total Notes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Total Notes</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-fixed flex items-center justify-center text-primary">
                        <span class="material-symbols-outlined text-[18px]">library_books</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $totalNotes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-primary-fixed rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Downloaded Notes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Downloaded</h3>
                    <div class="w-8 h-8 rounded-lg bg-emerald-100 flex items-center justify-center text-emerald-700">
                        <span class="material-symbols-outlined text-[18px]">download_done</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $downloadedNotes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-emerald-100 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Pending Notes Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Pending</h3>
                    <div class="w-8 h-8 rounded-lg bg-error-container flex items-center justify-center text-error">
                        <span class="material-symbols-outlined text-[18px]">pending_actions</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $pendingNotes }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-error-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            {{-- Subjects Covered Card --}}
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md flex flex-col relative overflow-hidden group hover:border-primary transition-colors cursor-default">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-label-md font-label-md text-secondary uppercase tracking-wider">Subjects Covered</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">category</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="text-headline-xl font-headline-xl text-on-surface">{{ $subjectsCovered }}</span>
                </div>
                <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="flex items-center gap-4 border-b border-outline-variant pb-4 overflow-x-auto hide-scrollbar">
            <button class="px-4 py-1.5 rounded-full bg-primary text-on-primary text-label-md font-bold whitespace-nowrap">All Subjects</button>
            @php $uniqueSubjects = $notes->pluck('subject.name')->unique()->filter(); @endphp
            @foreach($uniqueSubjects as $subjName)
                <button class="px-4 py-1.5 rounded-full border border-outline-variant bg-surface-container-lowest text-secondary hover:bg-surface-container hover:text-on-surface transition-colors text-label-md font-bold whitespace-nowrap">{{ $subjName }}</button>
            @endforeach
        </div>

        <!-- Notes Grid -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-headline-md font-headline-md text-on-surface">Recent Uploads</h3>
                <div class="flex items-center gap-2">
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-primary-fixed text-primary"><span class="material-symbols-outlined text-[20px]">grid_view</span></button>
                    <button class="w-8 h-8 flex items-center justify-center rounded bg-surface-container hover:bg-surface-container-high text-secondary transition-colors"><span class="material-symbols-outlined text-[20px]">view_list</span></button>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-md">
                @forelse($notes as $note)
                    <div class="bg-surface-container-lowest rounded-xl border border-outline-variant hover:border-primary transition-all duration-300 flex flex-col group overflow-hidden shadow-sm hover:shadow-md relative">
                        <div class="p-5 flex-1 flex flex-col">
                            <div class="flex justify-between items-start mb-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center
                                    @if($note->file_type == 'pdf') bg-red-100 text-red-600
                                    @elseif(in_array($note->file_type, ['doc', 'docx', 'text'])) bg-blue-100 text-blue-600
                                    @elseif($note->file_type == 'ppt') bg-orange-100 text-orange-600
                                    @elseif($note->file_type == 'image') bg-purple-100 text-purple-600
                                    @else bg-surface-variant text-on-surface-variant @endif
                                ">
                                    <span class="material-symbols-outlined text-[24px]">
                                        @if($note->file_type == 'pdf') picture_as_pdf
                                        @elseif(in_array($note->file_type, ['doc', 'docx', 'text'])) description
                                        @elseif($note->file_type == 'ppt') slides
                                        @elseif($note->file_type == 'image') image
                                        @else link @endif
                                    </span>
                                </div>
                                <button class="text-outline hover:text-primary transition-colors">
                                    <span class="material-symbols-outlined text-[20px]">bookmark_border</span>
                                </button>
                            </div>
                            
                            <div class="mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-primary bg-primary-fixed px-2 py-0.5 rounded-full">{{ $note->subject->name ?? 'General' }}</span>
                            </div>
                            
                            <h4 class="font-bold text-body-lg text-on-surface line-clamp-2 mb-1 group-hover:text-primary transition-colors" title="{{ $note->title }}">{{ $note->title }}</h4>
                            
                            @if($note->description)
                                <p class="text-label-md text-secondary line-clamp-2 mb-4">{{ $note->description }}</p>
                            @else
                                <div class="mb-4"></div>
                            @endif

                            <div class="mt-auto pt-4 border-t border-outline-variant border-dashed space-y-2">
                                <div class="flex items-center gap-2 text-xs text-secondary">
                                    <span class="material-symbols-outlined text-[14px]">person</span>
                                    <span class="truncate">{{ $note->uploader->name ?? 'Teacher' }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs text-secondary">
                                    <div class="flex items-center gap-1">
                                        <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                        <span>{{ $note->created_at->format('M d, Y') }}</span>
                                    </div>
                                    <span class="font-bold uppercase">{{ $note->file_type }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-surface-bright border-t border-outline-variant p-3 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity absolute bottom-0 left-0 right-0 translate-y-full group-hover:translate-y-0 duration-300">
                            @if($note->file_path)
                                <a href="{{ Storage::url($note->file_path) }}" target="_blank" class="flex-1 py-2 bg-primary text-on-primary rounded-lg text-center font-bold text-label-md hover:bg-primary/90 transition-colors flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">download</span> Download
                                </a>
                            @elseif($note->external_url)
                                <a href="{{ $note->external_url }}" target="_blank" class="flex-1 py-2 bg-primary text-on-primary rounded-lg text-center font-bold text-label-md hover:bg-primary/90 transition-colors flex items-center justify-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">open_in_new</span> Open Link
                                </a>
                            @endif
                            <button class="w-10 h-10 flex items-center justify-center border border-outline-variant rounded-lg text-secondary hover:bg-surface-container hover:text-primary transition-colors">
                                <span class="material-symbols-outlined text-[20px]">done_all</span>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-16 flex flex-col items-center justify-center bg-surface-container-lowest border border-outline-variant border-dashed rounded-xl">
                        <div class="w-16 h-16 bg-surface-variant rounded-full flex items-center justify-center text-secondary mb-4">
                            <span class="material-symbols-outlined text-[32px]">folder_off</span>
                        </div>
                        <h4 class="text-headline-md font-headline-md text-on-surface mb-1">No Notes Available</h4>
                        <p class="text-body-md text-secondary text-center max-w-md">There are currently no digital notes uploaded for your classes. Check back later.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>
@endsection
