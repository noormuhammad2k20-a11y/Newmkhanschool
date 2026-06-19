@extends('layouts.app')

@section('content')
<div class="container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 h-full flex flex-col">
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Edit Seating Plan: {{ $plan->name }}</h2>
            <p class="text-body-md text-secondary mt-1">Class: <strong class="text-on-surface">{{ $plan->class->name }} - {{ $plan->section->name }}</strong></p>
        </div>
        <div class="flex flex-wrap gap-sm items-center w-full lg:w-auto">
            <span id="save-status" class="text-sm font-medium text-green-600 bg-green-50 px-3 py-1 rounded-full border border-green-200 hidden transition-all">Saved!</span>
            <button id="auto-arrange-btn" class="flex-1 lg:flex-none justify-center px-md py-sm bg-secondary text-white rounded-lg font-label-md hover:bg-secondary-fixed-variant transition-colors flex items-center gap-xs shadow-sm">
                <span class="material-symbols-rounded text-[18px]">auto_awesome</span> Auto Arrange
            </button>
            <button id="save-btn" class="flex-1 lg:flex-none justify-center px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-xs shadow-sm">
                <span class="material-symbols-rounded text-[18px]">save</span> Save Plan
            </button>
            <button onclick="window.print()" class="flex-1 lg:flex-none justify-center px-md py-sm bg-surface-container-high border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-highest flex items-center gap-xs transition-colors">
                <span class="material-symbols-rounded text-[18px]">print</span> Print
            </button>
            <a href="{{ route('teacher.seating.index') }}" class="flex-1 lg:flex-none text-center px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high transition-colors">Back</a>
        </div>
    </div>

<div class="flex flex-col lg:flex-row gap-lg flex-1 min-h-0">
        <!-- Unassigned Students Sidebar -->
        <div class="w-full lg:w-80 flex-shrink-0 bg-surface border border-outline-variant rounded-2xl p-md shadow-sm h-[400px] lg:h-[calc(100vh-220px)] flex flex-col">
            <h3 class="text-title-md font-semibold mb-1 text-on-surface flex items-center gap-xs">
                <span class="material-symbols-rounded text-[20px] text-primary">group</span>
                Unassigned Students
            </h3>
            <p class="text-xs text-secondary mb-md pb-md border-b border-outline-variant">Drag students onto the seating grid.</p>
            
            <div class="flex-1 overflow-y-auto pr-2 custom-scrollbar" id="unassigned-list" data-row="0" data-col="0">
                @if($unassignedStudents->isEmpty())
                    <div class="h-full flex flex-col items-center justify-center text-secondary text-center opacity-70">
                        <span class="material-symbols-rounded text-[48px] mb-2">check_circle</span>
                        <p class="text-sm">All students assigned</p>
                    </div>
                @else
                    @foreach($unassignedStudents as $student)
                    <div class="student-card bg-surface-container-lowest border border-outline-variant rounded-xl p-sm mb-sm cursor-grab active:cursor-grabbing hover:border-primary hover:shadow-md transition-all flex items-center gap-sm group" data-id="{{ $student->id }}">
                        <div class="w-10 h-10 flex-shrink-0 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-sm">
                            {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-label-md font-semibold truncate">{{ $student->first_name }} {{ $student->last_name }}</p>
                            <p class="text-xs text-secondary truncate">Adm: {{ $student->admission_no }}</p>
                        </div>
                        <div class="material-symbols-rounded text-outline-variant group-hover:text-primary transition-colors cursor-grab">drag_indicator</div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>

        <!-- Seating Grid Wrapper -->
        <div class="flex-1 min-w-0 bg-surface border border-outline-variant rounded-2xl p-md lg:p-xl shadow-sm flex flex-col relative h-[600px] lg:h-[calc(100vh-220px)]">
            <div class="flex justify-center mb-lg shrink-0">
                <div class="w-full max-w-lg h-12 bg-surface-container-highest rounded-xl border border-outline-variant flex items-center justify-center font-bold text-secondary uppercase tracking-widest text-sm">
                    <span class="material-symbols-rounded mr-2">cast_for_education</span>
                    Teacher's Desk / Whiteboard
                </div>
            </div>

            <!-- Scrollable Grid Area -->
            <div class="flex-1 overflow-auto custom-scrollbar w-full rounded-lg border border-outline-variant bg-surface-container-lowest p-md lg:p-lg relative" id="grid-scroll-area">
                <div class="grid-container mx-auto" style="display: grid; grid-template-columns: repeat({{ $plan->cols }}, minmax(100px, 1fr)); gap: 1rem; min-width: max-content;">
                    @for($r = 1; $r <= $plan->rows; $r++)
                        @for($c = 1; $c <= $plan->cols; $c++)
                            @php
                                $student = $grid[$r][$c] ?? null;
                            @endphp
                            <div class="seat-zone border-2 border-dashed border-outline-variant rounded-xl p-xs min-h-[120px] flex flex-col items-center justify-center bg-surface transition-colors relative hover:border-primary-container" data-row="{{ $r }}" data-col="{{ $c }}">
                                <span class="absolute top-1 left-2 text-[10px] text-outline font-mono font-medium">R{{ $r }}C{{ $c }}</span>
                                
                                @if($student)
                                <div class="student-card w-full h-full flex flex-col justify-center items-center bg-primary-container border border-primary text-on-primary-container rounded-lg p-xs text-center cursor-grab active:cursor-grabbing shadow-sm mt-4 hover:shadow-md transition-shadow relative group" data-id="{{ $student->id }}">
                                    <div class="w-10 h-10 mx-auto rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-sm mb-1">
                                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                    </div>
                                    <p class="text-xs font-semibold leading-tight line-clamp-2 px-1 w-full" title="{{ $student->first_name }} {{ $student->last_name }}">{{ $student->first_name }} {{ $student->last_name }}</p>
                                    <button type="button" class="remove-btn absolute -top-2 -right-2 w-6 h-6 rounded-full bg-error text-white opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-md hover:bg-error-container hover:text-error">
                                        <span class="material-symbols-rounded text-[14px]">close</span>
                                    </button>
                                </div>
                                @else
                                <div class="text-outline-variant flex flex-col items-center justify-center pointer-events-none mt-2">
                                    <span class="material-symbols-rounded text-[24px] opacity-50">event_seat</span>
                                </div>
                                @endif
                            </div>
                        @endfor
                    @endfor
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include SortableJS for drag and drop -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const unassignedList = document.getElementById('unassigned-list');
    const seats = document.querySelectorAll('.seat-zone');
    
    // Initialize Sortable for Unassigned List
    new Sortable(unassignedList, {
        group: 'shared',
        animation: 150,
        ghostClass: 'opacity-50',
    });

    // Initialize Sortable for each seat
    seats.forEach(seat => {
        new Sortable(seat, {
            group: 'shared',
            animation: 150,
            ghostClass: 'opacity-50',
            onAdd: function (evt) {
                // Remove existing student if dropping into an occupied seat
                const itemEl = evt.item;  // The dragged item
                const targetSeat = evt.to; // The seat container
                
                // If there is more than 1 student card in this seat, move the old one back to unassigned
                const cards = targetSeat.querySelectorAll('.student-card');
                if (cards.length > 1) {
                    cards.forEach(card => {
                        if (card !== itemEl) {
                            unassignedList.appendChild(card);
                        }
                    });
                }
                
                // Style adjustments when dropped into grid
                itemEl.classList.remove('bg-surface-container-lowest', 'flex-row', 'mb-sm');
                itemEl.classList.add('bg-primary-container', 'border-primary', 'flex-col', 'h-full', 'mt-4');
                
                // Ensure close button exists
                if(!itemEl.querySelector('.remove-btn')) {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'remove-btn absolute -top-2 -right-2 w-6 h-6 rounded-full bg-error text-white opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center shadow-md hover:bg-error-container hover:text-error';
                    btn.innerHTML = '<span class="material-symbols-rounded text-[14px]">close</span>';
                    itemEl.appendChild(btn);
                    itemEl.classList.add('group');
                }
            },
            onRemove: function(evt) {
                // Style adjustments when moved back to unassigned
                if(evt.to === unassignedList) {
                    const itemEl = evt.item;
                    itemEl.classList.add('bg-surface-container-lowest', 'flex-row', 'mb-sm');
                    itemEl.classList.remove('bg-primary-container', 'border-primary', 'flex-col', 'h-full', 'mt-4');
                    
                    const btn = itemEl.querySelector('.remove-btn');
                    if(btn) btn.remove();
                }
            }
        });
    });

    // Handle Remove Button Clicks (Event Delegation)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) {
            const card = e.target.closest('.student-card');
            
            // Format back for list
            card.classList.add('bg-surface-container-lowest', 'flex-row', 'mb-sm');
            card.classList.remove('bg-primary-container', 'border-primary', 'flex-col', 'h-full', 'mt-4');
            
            const btn = card.querySelector('.remove-btn');
            if(btn) btn.remove();
            
            unassignedList.appendChild(card);
        }
    });

    // Save Plan via AJAX
    document.getElementById('save-btn').addEventListener('click', function() {
        const btn = this;
        const status = document.getElementById('save-status');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-rounded text-[18px] animate-spin">refresh</span> Saving...';

        const assignments = [];
        seats.forEach(seat => {
            const card = seat.querySelector('.student-card');
            if (card) {
                assignments.push({
                    row: parseInt(seat.dataset.row),
                    col: parseInt(seat.dataset.col),
                    student_id: parseInt(card.dataset.id)
                });
            }
        });

        fetch('{{ route('teacher.seating.update-grid', $plan->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ assignments: assignments })
        })
        .then(response => response.json())
        .then(data => {
            status.classList.remove('hidden');
            setTimeout(() => status.classList.add('hidden'), 3000);
        })
        .catch(error => {
            console.error('Error saving plan:', error);
            alert('Failed to save seating plan.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-rounded text-[18px]">save</span> Save Plan';
        });
    });

    // Auto Arrange via AJAX
    document.getElementById('auto-arrange-btn').addEventListener('click', async function() {
        const isConfirmed = await window.UI.confirm('Confirm Action', 'This will replace current seat assignments. Continue?', 'Confirm', 'error');
        if (!isConfirmed) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-rounded text-[18px] animate-spin">refresh</span> Arranging...';

        fetch('{{ route('teacher.seating.auto-arrange', $plan->id) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                alert(data.error);
            } else {
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error auto arranging:', error);
            alert('Failed to auto arrange seating plan.');
        })
        .finally(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-rounded text-[18px]">auto_awesome</span> Auto Arrange';
        });
    });
});
</script>

<style>
/* Custom Scrollbar for better appearance */
.custom-scrollbar::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: rgba(156, 163, 175, 0.5);
    border-radius: 4px;
}
.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background-color: rgba(107, 114, 128, 0.8);
}

@media print {
    body * {
        visibility: hidden;
    }
    .grid-container, .grid-container *, h2, p {
        visibility: visible;
    }
    h2, p {
        position: relative;
    }
    #grid-scroll-area {
        overflow: visible !important;
        border: none !important;
        background: transparent !important;
    }
    .grid-container {
        position: absolute;
        left: 0;
        top: 100px;
        width: 100%;
        gap: 2px !important;
    }
    .seat-zone {
        border: 1px solid #000;
        padding: 5px;
        min-height: 80px !important;
    }
    .student-card {
        border: 1px solid #000 !important;
        background: transparent !important;
        color: #000 !important;
    }
    .remove-btn, #unassigned-list, #save-btn, #auto-arrange-btn, a[href], .bg-surface-container-highest {
        display: none !important;
    }
}
</style>
@endsection
