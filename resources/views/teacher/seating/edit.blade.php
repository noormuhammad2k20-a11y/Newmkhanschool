@extends('layouts.app')

@section('content')
<div class="px-md py-lg max-w-7xl mx-auto">
    <div class="flex justify-between items-center mb-lg">
        <div>
            <h2 class="text-headline-lg font-headline-lg text-primary">Edit Seating Plan: {{ $plan->name }}</h2>
            <p class="text-body-md text-secondary">Class: {{ $plan->class->name }} - {{ $plan->section->name }}</p>
        </div>
        <div class="flex gap-sm items-center">
            <span id="save-status" class="text-sm font-medium text-green-600 hidden">Saved!</span>
            <button id="auto-arrange-btn" class="px-md py-sm bg-secondary text-on-primary rounded-lg font-label-md hover:bg-secondary-fixed-variant transition-colors flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">auto_awesome</span> Auto Arrange
            </button>
            <button id="save-btn" class="px-md py-sm bg-primary text-on-primary rounded-lg font-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">save</span> Save Plan
            </button>
            <button onclick="window.print()" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high flex items-center gap-xs">
                <span class="material-symbols-outlined text-[18px]">print</span> Print
            </button>
            <a href="{{ route('teacher.seating.index') }}" class="px-md py-sm border border-outline text-on-surface rounded-lg font-label-md hover:bg-surface-container-high">Back</a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-md p-md bg-surface-container-high border-l-4 border-primary text-on-surface rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col lg:flex-row gap-lg">
        <!-- Unassigned Students Sidebar -->
        <div class="lg:w-1/4 bg-surface border border-outline-variant rounded-xl p-md shadow-sm h-[calc(100vh-200px)] overflow-hidden flex flex-col">
            <h3 class="font-headline-sm mb-xs text-on-surface">Unassigned Students</h3>
            <p class="text-xs text-secondary mb-md">Drag students onto the seating grid.</p>
            
            <div class="flex-1 overflow-y-auto pr-sm" id="unassigned-list" data-row="0" data-col="0">
                @foreach($unassignedStudents as $student)
                <div class="student-card bg-surface-container-low border border-outline-variant rounded-lg p-sm mb-sm cursor-grab active:cursor-grabbing hover:bg-surface-container-high transition-colors flex items-center gap-sm" data-id="{{ $student->id }}">
                    <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs">
                        {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                    </div>
                    <div>
                        <p class="text-label-md font-semibold">{{ $student->first_name }} {{ $student->last_name }}</p>
                        <p class="text-xs text-secondary">Adm: {{ $student->admission_no }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Seating Grid -->
        <div class="lg:w-3/4 bg-surface border border-outline-variant rounded-xl p-lg shadow-sm overflow-x-auto relative">
            <div class="flex justify-center mb-lg">
                <div class="w-2/3 h-12 bg-surface-container-highest rounded-lg border-2 border-outline flex items-center justify-center font-bold text-secondary uppercase tracking-widest">
                    Teacher's Desk / Whiteboard
                </div>
            </div>

            <div class="grid-container mx-auto" style="display: grid; grid-template-columns: repeat({{ $plan->cols }}, minmax(120px, 1fr)); gap: 1rem; width: max-content;">
                @for($r = 1; $r <= $plan->rows; $r++)
                    @for($c = 1; $c <= $plan->cols; $c++)
                        @php
                            $student = $grid[$r][$c] ?? null;
                        @endphp
                        <div class="seat-zone border-2 border-dashed border-outline-variant rounded-xl p-sm min-h-[100px] flex flex-col items-center justify-center bg-surface-container-lowest transition-colors relative" data-row="{{ $r }}" data-col="{{ $c }}">
                            <span class="absolute top-1 left-2 text-[10px] text-outline font-mono">R{{ $r }}C{{ $c }}</span>
                            
                            @if($student)
                            <div class="student-card w-full bg-primary-container border border-primary text-on-primary-container rounded-lg p-xs text-center cursor-grab active:cursor-grabbing shadow-sm" data-id="{{ $student->id }}">
                                <div class="w-8 h-8 mx-auto rounded-full bg-primary text-on-primary flex items-center justify-center font-bold text-xs mb-xs">
                                    {{ substr($student->first_name, 0, 1) }}{{ substr($student->last_name, 0, 1) }}
                                </div>
                                <p class="text-xs font-semibold leading-tight truncate px-1" title="{{ $student->first_name }} {{ $student->last_name }}">{{ $student->first_name }} {{ $student->last_name }}</p>
                                <button type="button" class="remove-btn absolute top-1 right-1 text-on-primary-container hover:text-error">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </button>
                            </div>
                            @endif
                        </div>
                    @endfor
                @endfor
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
            }
        });
    });

    // Handle Remove Button Clicks (Event Delegation)
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-btn')) {
            const card = e.target.closest('.student-card');
            unassignedList.appendChild(card);
        }
    });

    // Save Plan via AJAX
    document.getElementById('save-btn').addEventListener('click', function() {
        const btn = this;
        const status = document.getElementById('save-status');
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Saving...';

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
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">save</span> Save Plan';
        });
    });

    // Auto Arrange via AJAX
    document.getElementById('auto-arrange-btn').addEventListener('click', function() {
        if (!confirm('This will replace current seat assignments. Continue?')) return;
        
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-[18px] animate-spin">refresh</span> Arranging...';

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
            btn.innerHTML = '<span class="material-symbols-outlined text-[18px]">auto_awesome</span> Auto Arrange';
        });
    });
});
</script>

<style>
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
    .grid-container {
        position: absolute;
        left: 0;
        top: 100px;
        width: 100%;
    }
    .seat-zone {
        border: 1px solid #000;
        padding: 5px;
    }
    .remove-btn, #unassigned-list, #save-btn, #auto-arrange-btn, a[href], .bg-surface-container-highest {
        display: none !important;
    }
}
</style>
@endsection
