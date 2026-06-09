@extends('layouts.app')

@section('title', 'Digital Notes & Resources')

@section('content')
<main class="flex-1 overflow-y-auto p-margin-desktop bg-background">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        
        {{-- Header & Search --}}
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-xl">
            <div class="flex-1">
                <h1 class="text-headline-xl font-headline-xl font-bold text-on-surface">Digital Learning Hub</h1>
                <p class="text-body-lg font-body-lg text-secondary mt-1">Access study materials, lecture notes, and resources uploaded by your teachers.</p>
            </div>
            
            <form method="GET" action="{{ route('student.digital_notes') }}" class="w-full md:w-96 flex relative shadow-sm rounded-xl overflow-hidden border border-outline-variant focus-within:border-primary transition-colors bg-surface-container-lowest">
                <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-secondary">search</span>
                <input type="text" name="search" placeholder="Search notes, subjects..." class="w-full bg-transparent py-3 pl-10 pr-4 text-on-surface text-body-md font-body-md focus:outline-none" value="{{ request('search') }}">
                <button type="submit" class="bg-surface-container border-l border-outline-variant text-on-surface px-6 text-label-md font-label-md font-bold hover:bg-surface-container-high transition-colors">Search</button>
            </form>
        </div>

        @if(session('error'))
            <div class="bg-error-container text-error p-md rounded-xl flex items-center gap-2 border border-error/20 shadow-sm">
                <span class="material-symbols-outlined">error</span>
                <span class="text-body-md font-body-md">{{ session('error') }}</span>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-primary transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Total Resources</h3>
                    <div class="w-8 h-8 rounded-lg bg-primary-container flex items-center justify-center text-on-primary-container">
                        <span class="material-symbols-outlined text-[18px]">library_books</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ method_exists($notes, 'total') ? $notes->total() : $notes->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-secondary">
                    <span class="material-symbols-outlined text-[14px]">auto_awesome</span>
                    <span>Available materials</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-primary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
            
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-[#e53935] transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">PDF Notes</h3>
                    <div class="w-8 h-8 rounded-lg bg-[#e53935]/10 flex items-center justify-center text-[#e53935]">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ collect($notes->items() ?? $notes)->where('file_type', 'pdf')->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-secondary">
                    <span>In current view</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-[#e53935]/10 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-[#1e88e5] transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">Documents</h3>
                    <div class="w-8 h-8 rounded-lg bg-[#1e88e5]/10 flex items-center justify-center text-[#1e88e5]">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ collect($notes->items() ?? $notes)->whereIn('file_type', ['doc', 'docx'])->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-secondary">
                    <span>In current view</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-[#1e88e5]/10 rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>

            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-4 flex flex-col relative overflow-hidden group hover:border-secondary transition-colors">
                <div class="flex justify-between items-start mb-2">
                    <h3 class="text-label-sm font-label-sm text-secondary uppercase tracking-wider">External Links</h3>
                    <div class="w-8 h-8 rounded-lg bg-secondary-container flex items-center justify-center text-on-secondary-container">
                        <span class="material-symbols-outlined text-[18px]">link</span>
                    </div>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-display-sm font-display-sm text-on-surface">{{ collect($notes->items() ?? $notes)->where('file_type', 'link')->count() }}</span>
                </div>
                <div class="mt-2 flex items-center gap-1.5 text-label-md font-label-md text-secondary">
                    <span>In current view</span>
                </div>
                <div class="absolute -bottom-8 -right-8 w-24 h-24 bg-secondary-container rounded-full opacity-20 group-hover:scale-150 transition-transform duration-500"></div>
            </div>
        </div>

        {{-- Category/Filter Pills --}}
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('student.digital_notes') }}" class="px-5 py-2 rounded-xl text-label-md font-label-md font-bold transition-colors {{ !request('type') ? 'bg-primary text-on-primary shadow-sm' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-low border border-outline-variant' }}">All Resources</a>
            <a href="{{ route('student.digital_notes', ['type' => 'pdf']) }}" class="px-5 py-2 rounded-xl text-label-md font-label-md font-bold transition-colors {{ request('type') == 'pdf' ? 'bg-[#e53935] text-white shadow-sm' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-low border border-outline-variant' }}">PDFs</a>
            <a href="{{ route('student.digital_notes', ['type' => 'doc']) }}" class="px-5 py-2 rounded-xl text-label-md font-label-md font-bold transition-colors {{ request('type') == 'doc' ? 'bg-[#1e88e5] text-white shadow-sm' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-low border border-outline-variant' }}">Documents</a>
            <a href="{{ route('student.digital_notes', ['type' => 'link']) }}" class="px-5 py-2 rounded-xl text-label-md font-label-md font-bold transition-colors {{ request('type') == 'link' ? 'bg-secondary text-on-secondary shadow-sm' : 'bg-surface-container-lowest text-secondary hover:bg-surface-container-low border border-outline-variant' }}">External Links</a>
        </div>

        {{-- Notes Grid --}}
        @if($notes->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-md">
                @foreach($notes as $note)
                <div class="bg-surface-container-lowest border border-outline-variant rounded-xl flex flex-col hover:-translate-y-1 hover:shadow-md transition-all duration-300 group overflow-hidden">
                    
                    {{-- Decorative Top Border based on File Type --}}
                    @php
                        $borderColor = match($note->file_type) {
                            'pdf' => 'bg-[#e53935]',
                            'doc', 'docx' => 'bg-[#1e88e5]',
                            'ppt', 'pptx' => 'bg-[#ff9800]',
                            'link' => 'bg-secondary',
                            default => 'bg-primary'
                        };
                    @endphp
                    <div class="h-1 w-full {{ $borderColor }}"></div>
                    
                    <div class="p-xl flex-1 flex flex-col">
                        <div class="flex justify-between items-start mb-sm">
                            <div class="w-12 h-12 rounded-xl {{ str_replace('bg-', 'bg-', $borderColor) }}/10 text-{{ str_replace('bg-', '', $borderColor) }} flex items-center justify-center">
                                @if($note->file_type === 'pdf')
                                    <span class="material-symbols-outlined text-[24px]" style="color: #e53935">picture_as_pdf</span>
                                @elseif(in_array($note->file_type, ['doc', 'docx']))
                                    <span class="material-symbols-outlined text-[24px]" style="color: #1e88e5">description</span>
                                @elseif(in_array($note->file_type, ['ppt', 'pptx']))
                                    <span class="material-symbols-outlined text-[24px]" style="color: #ff9800">slideshow</span>
                                @elseif($note->file_type === 'link')
                                    <span class="material-symbols-outlined text-[24px] text-secondary">link</span>
                                @else
                                    <span class="material-symbols-outlined text-[24px] text-primary">insert_drive_file</span>
                                @endif
                            </div>
                            <span class="bg-surface-container text-secondary text-[10px] uppercase font-bold px-2.5 py-1 rounded-md">{{ strtoupper($note->file_type) }}</span>
                        </div>

                        <h3 class="text-title-lg font-title-lg font-bold text-on-surface mb-1 group-hover:text-primary transition-colors line-clamp-1">{{ $note->title }}</h3>
                        <p class="text-label-md font-label-md text-primary font-bold mb-md">{{ $note->subject->name ?? 'General Subject' }}</p>
                        
                        <p class="text-body-md font-body-md text-secondary line-clamp-2 flex-1 mb-md">{{ $note->description }}</p>

                        <div class="flex items-center gap-3 mb-md bg-surface p-3 rounded-xl border border-outline-variant/50">
                            <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm">
                                {{ substr($note->uploader->name ?? 'T', 0, 1) }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <span class="block text-label-md font-label-md font-bold text-on-surface truncate">{{ $note->uploader->name ?? 'Teacher' }}</span>
                                <span class="block text-body-sm font-body-sm text-secondary truncate">Uploaded {{ $note->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="p-md border-t border-outline-variant bg-surface-bright flex gap-2">
                        <a href="{{ route('student.digital_notes.download', $note->id) }}" class="flex-1 py-2 px-4 bg-primary text-on-primary rounded-lg text-label-md font-label-md font-bold text-center hover:opacity-90 transition-opacity flex items-center justify-center gap-2" {{ $note->file_type == 'link' ? 'target="_blank"' : '' }}>
                            <span class="material-symbols-outlined text-[18px]">{{ $note->file_type == 'link' ? 'open_in_new' : 'download' }}</span>
                            {{ $note->file_type == 'link' ? 'Open' : 'Download' }}
                        </a>
                        @if($note->file_type != 'link')
                            <a href="{{ route('student.digital_notes.download', $note->id) }}" class="py-2 px-4 bg-surface-container-low border border-outline-variant text-on-surface rounded-lg text-label-md font-label-md font-bold hover:bg-surface-container-high transition-colors flex items-center justify-center" title="View">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            @if(method_exists($notes, 'hasPages') && $notes->hasPages())
                <div class="mt-xl flex justify-center">
                    {{ $notes->links('pagination::tailwind') }}
                </div>
            @endif

        @else
            <div class="bg-surface-container-lowest border border-dashed border-outline-variant rounded-xl p-2xl text-center flex flex-col items-center justify-center min-h-[400px]">
                <div class="w-24 h-24 bg-surface-container rounded-full flex items-center justify-center mb-md">
                    <span class="material-symbols-outlined text-[48px] text-secondary opacity-50">menu_book</span>
                </div>
                <h3 class="text-headline-md font-headline-md text-on-surface font-bold">No Resources Found</h3>
                <p class="text-body-lg font-body-lg text-secondary mt-sm max-w-md">There are no digital notes or study materials available right now. Check back later or clear your search filters.</p>
                @if(request('search') || request('type'))
                    <a href="{{ route('student.digital_notes') }}" class="mt-lg px-6 py-2 border border-outline-variant rounded-xl text-label-md font-label-md font-bold hover:bg-surface-container-low transition-colors">Clear Filters</a>
                @endif
            </div>
        @endif

    </div>
</main>
@endsection
