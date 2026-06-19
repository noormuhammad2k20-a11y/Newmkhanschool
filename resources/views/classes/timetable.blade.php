@extends('layouts.app')

@section('title', 'Class Timetable')

@section('content')
    <main class="flex-1 p-margin-desktop max-w-[1440px] mx-auto min-h-[calc(100vh-64px)] w-full min-w-0">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-lg gap-4">
            <div>
                <h1 class="text-headline-lg font-headline-lg font-semibold text-on-surface mb-2">Class Timetable</h1>
                <p class="font-body-lg text-body-lg text-secondary">Manage weekly schedules for Grade 10 - Section A</p>
            </div>
            <div class="flex flex-row items-center gap-2 sm:gap-3 overflow-x-auto pb-2 md:pb-0 w-full md:w-auto custom-scrollbar">
                <button id="printBtn" class="flex-shrink-0 bg-surface-container-lowest text-primary border border-outline-variant hover:bg-surface-container-high px-4 py-2 rounded font-label-md text-label-md flex items-center gap-2 transition-colors">
                    <span class="material-symbols-rounded text-[20px]">print</span>
                    Print Timetable
                </button>
                <button id="exportPdfBtn" class="flex-shrink-0 bg-surface-container-lowest text-primary border border-outline-variant hover:bg-surface-container-high px-4 py-2 rounded font-label-md text-label-md flex items-center gap-2 transition-colors">
                    <span class="material-symbols-rounded text-[20px]">picture_as_pdf</span>
                    Export PDF
                </button>
                <button class="flex-shrink-0 bg-primary text-on-primary px-4 py-2 rounded font-label-md text-label-md flex items-center gap-2 hover:opacity-90 transition-opacity shadow-sm">
                    <span class="material-symbols-rounded text-[20px]">edit</span>
                    Edit Schedule
                </button>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="bg-surface-container-lowest p-md border border-outline-variant rounded mb-lg flex flex-wrap gap-4 items-end shadow-sm">
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-md text-label-md text-secondary mb-1">Class Grade</label>
                <div class="relative">
                    <select id="classFilter" class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-3 pr-10 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md transition-colors cursor-pointer">
                        <option value="">Select Class</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">expand_more</span>
                </div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-md text-label-md text-secondary mb-1">Section</label>
                <div class="relative">
                    <select id="sectionFilter" class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-3 pr-10 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md transition-colors cursor-pointer">
                        <option value="">Select Section</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">expand_more</span>
                </div>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block font-label-md text-label-md text-secondary mb-1">Academic Session</label>
                <div class="relative">
                    <select id="sessionFilter" class="w-full appearance-none bg-surface-container-lowest border border-outline-variant text-on-surface py-2 pl-3 pr-10 rounded focus:border-primary focus:ring-1 focus:ring-primary outline-none font-body-md text-body-md transition-colors cursor-pointer">
                        <option value="">Active Session</option>
                    </select>
                    <span class="material-symbols-rounded absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none text-secondary">expand_more</span>
                </div>
            </div>
        </div>
        
        <!-- Timetable Grid -->
        <div id="printableTimetable" class="bg-surface-container-lowest border border-outline-variant rounded overflow-hidden">
            <!-- Print/PDF Header (Hidden on screen) -->
            <div id="printHeader" class="print-only-header p-6 border-b border-outline-variant bg-white">
                <div class="grid grid-cols-3 gap-4 text-sm bg-surface-container-lowest p-4 rounded border border-outline-variant">
                    <div><span class="text-secondary">Class Grade:</span> <strong id="headerClass" class="text-on-surface">-</strong></div>
                    <div><span class="text-secondary">Section:</span> <strong id="headerSection" class="text-on-surface">-</strong></div>
                    <div><span class="text-secondary">Academic Session:</span> <strong id="headerSession" class="text-on-surface">-</strong></div>
                </div>
            </div>

            <div class="overflow-x-auto w-full custom-scrollbar">
                <table class="w-full min-w-max border-collapse relative">
                    <thead>
                        <tr class="bg-surface-container text-on-surface-variant font-label-md text-label-md">
                            <th class="border-b border-r border-outline-variant p-3 text-left w-24 min-w-[100px] whitespace-nowrap sticky left-0 bg-surface-container z-20">Time / Day</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32 min-w-[150px] whitespace-nowrap">Monday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32 min-w-[150px] whitespace-nowrap">Tuesday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32 min-w-[150px] whitespace-nowrap">Wednesday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32 min-w-[150px] whitespace-nowrap">Thursday</th>
                            <th class="border-b border-r border-outline-variant p-3 text-center w-32 min-w-[150px] whitespace-nowrap">Friday</th>
                            <th class="border-b border-outline-variant p-3 text-center w-32 min-w-[150px] whitespace-nowrap saturday-col">Saturday</th>
                        </tr>
                    </thead>
                    <tbody id="timetable-tbody" class="font-body-md text-body-md">
                        <tr><td colspan="7" class="p-8 text-center text-secondary">Loading schedule...</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

<style>
/* Optional custom scrollbar to make it look nicer */
.custom-scrollbar::-webkit-scrollbar {
    height: 8px;
    width: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background-color: #cbd5e1;
    border-radius: 20px;
}

.print-only-header {
    display: none;
}

@media print {
    .print-only-header {
        display: block !important;
    }

    @page {
        size: landscape;
        margin: 10mm;
    }
    
    body * {
        visibility: hidden;
    }
    
    #printableTimetable, #printableTimetable * {
        visibility: visible;
    }
    
    #printableTimetable {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    
    .overflow-x-auto {
        overflow: visible !important;
    }
    
    /* Override fixed tailwind widths so it scales to fit the page */
    #printableTimetable table {
        width: 100% !important;
        table-layout: fixed !important;
        min-width: 0 !important;
    }
    
    th, td {
        min-width: 0 !important;
        word-wrap: break-word !important;
        padding: 4px !important;
    }
    
    th {
        font-size: 12px !important;
    }
    
    td div.font-semibold {
        font-size: 11px !important;
    }
    
    td div.text-sm {
        font-size: 10px !important;
    }
    
    td div.text-xs {
        font-size: 9px !important;
    }
    
    /* Hide add buttons and icons for clean print */
    .material-symbols-rounded {
        display: none !important;
    }
    
    .border-dashed {
        border-color: transparent !important;
    }
    
    /* Ensure colors are printed */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}

/* PDF Export Styles (Applied temporarily during html2pdf capture) */
.pdf-export-mode {
    width: 1200px !important;
    max-width: none !important;
    background: white !important;
}
.pdf-export-mode table {
    width: 100% !important;
    table-layout: fixed !important;
    min-width: 0 !important;
}
.pdf-export-mode th, .pdf-export-mode td {
    min-width: 0 !important;
    word-wrap: break-word !important;
    white-space: normal !important;
    padding: 6px !important;
}
.pdf-export-mode th { font-size: 14px !important; }
.pdf-export-mode td div.font-semibold { font-size: 13px !important; }
.pdf-export-mode td div.text-sm { font-size: 11px !important; }
.pdf-export-mode td div.text-xs { font-size: 10px !important; }
.pdf-export-mode .material-symbols-rounded { display: none !important; }
.pdf-export-mode .border-dashed { border-color: transparent !important; }
.pdf-export-mode .overflow-x-auto { overflow: visible !important; }
.pdf-export-mode .print-only-header { display: block !important; }

</style>

<!-- Add html2pdf library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('timetable-tbody');
        const classFilter = document.getElementById('classFilter');
        const sectionFilter = document.getElementById('sectionFilter');
        const sessionFilter = document.getElementById('sessionFilter');
        
        let allSections = [];

        function updatePrintHeader() {
            const className = classFilter.options[classFilter.selectedIndex]?.text || '-';
            const sectionName = sectionFilter.options[sectionFilter.selectedIndex]?.text || '-';
            const sessionName = sessionFilter.options[sessionFilter.selectedIndex]?.text || '-';
            
            document.getElementById('headerClass').textContent = className;
            document.getElementById('headerSection').textContent = sectionName;
            document.getElementById('headerSession').textContent = sessionName;
        }

        // 1. Fetch Filters Data
        fetch("{{ route('api.classes.filters') }}")
            .then(res => res.json())
            .then(res => {
                if(res.status === 'success') {
                    // Populate Classes
                    res.data.classes.forEach(c => {
                        classFilter.innerHTML += `<option value="${c.id}">${c.name}</option>`;
                    });
                    
                    // Populate Sessions
                    res.data.sessions.forEach(s => {
                        sessionFilter.innerHTML += `<option value="${s.id}">${s.year}</option>`;
                    });

                    allSections = res.data.sections;

                    // Automatically load first class if available
                    if (res.data.classes.length > 0) {
                        classFilter.value = res.data.classes[0].id;
                        updateSections();
                        loadTimetable();
                        updatePrintHeader();
                    }
                }
            });

        // Event Listeners for Filters
        classFilter.addEventListener('change', () => {
            updateSections();
            loadTimetable();
            updatePrintHeader();
        });

        sectionFilter.addEventListener('change', () => {
            loadTimetable();
            updatePrintHeader();
        });
        
        sessionFilter.addEventListener('change', () => {
            loadTimetable();
            updatePrintHeader();
        });

        // Print Functionality
        document.getElementById('printBtn').addEventListener('click', function() {
            updatePrintHeader(); // Ensure header is updated right before print
            window.print();
        });

        // Export PDF Functionality
        document.getElementById('exportPdfBtn').addEventListener('click', function() {
            const element = document.getElementById('printableTimetable');
            
            const className = classFilter.options[classFilter.selectedIndex]?.text || 'Class';
            let sectionName = sectionFilter.options[sectionFilter.selectedIndex]?.text || '';
            if (sectionName === 'All Sections' || sectionName === 'Select Section') sectionName = '';
            
            const filename = `Timetable_${className}_${sectionName}`.replace(/[^a-z0-9]/gi, '_').replace(/_+/g, '_').replace(/_$/, '') + '.pdf';
            
            updatePrintHeader(); // Ensure header is updated right before export

            const opt = {
                margin:       0.3,
                filename:     filename,
                image:        { type: 'jpeg', quality: 0.98 },
                html2canvas:  { scale: 2, useCORS: true },
                jsPDF:        { unit: 'in', format: 'a4', orientation: 'landscape' }
            };
            
            // Change button state
            const btn = this;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<span class="material-symbols-rounded text-[20px] animate-spin">refresh</span> Exporting...';
            btn.disabled = true;

            // Apply PDF mode class temporarily to override bounds
            element.classList.add('pdf-export-mode');

            html2pdf().set(opt).from(element).save().then(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
                element.classList.remove('pdf-export-mode');
            });
        });

        function updateSections() {
            const classId = classFilter.value;
            sectionFilter.innerHTML = '<option value="">All Sections</option>';
            
            const filteredSections = allSections.filter(s => s.class_id == classId);
            filteredSections.forEach(s => {
                sectionFilter.innerHTML += `<option value="${s.id}">${s.name}</option>`;
            });
            if(filteredSections.length > 0) {
                sectionFilter.value = filteredSections[0].id;
            }
        }

        function loadTimetable() {
            tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-secondary">Loading schedule...</td></tr>';
            
            const classId = classFilter.value;
            const sectionId = sectionFilter.value;
            const sessionId = sessionFilter.value;

            if (!classId) return;

            const params = new URLSearchParams();
            params.append('class_id', classId);
            if (sectionId) params.append('section_id', sectionId);
            if (sessionId) params.append('session_id', sessionId);

            let url = `/api/classes/timetable?${params.toString()}`;

            fetch(url)
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        if (response.data.length === 0) {
                            tbody.innerHTML = '<tr><td colspan="7" class="p-8 text-center text-secondary">No approved timetable found for these filters.</td></tr>';
                        } else {
                            renderTimetable(response.data);
                        }
                    }
                });
        }

        function renderTimetable(data) {
            let html = '';
            
            data.forEach((slot, index) => {
                const bgClass = index % 2 === 0 ? 'bg-surface-container-lowest' : 'bg-surface';
                
                if (slot.is_break) {
                    html += `
                    <tr class="border-b border-outline-variant bg-surface-container-high text-on-surface-variant text-center font-label-md text-label-md">
                        <td class="border-r border-outline-variant p-2 text-secondary font-medium whitespace-nowrap sticky left-0 bg-surface-container-high z-10">${slot.time}<br /><span class="text-xs">${slot.time_end}</span>
                        </td>
                        <td class="p-2 uppercase tracking-widest text-secondary min-w-[150px] break-label-col" colspan="6">${slot.label}</td>
                    </tr>`;
                    return;
                }
                
                let daysHtml = '';
                const days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
                
                days.forEach((day, dIdx) => {
                    const classData = slot.days[day];
                    const isLast = dIdx === days.length - 1;
                    let borderClass = isLast ? 'p-2 align-top min-w-[150px]' : 'border-r border-outline-variant p-2 align-top min-w-[150px]';
                    if (day === 'saturday') borderClass += ' saturday-col';
                    
                    if (!classData) {
                        daysHtml += `
                        <td class="${borderClass}">
                            <div class="border border-dashed border-outline-variant rounded p-2 h-full flex flex-col items-center justify-center text-secondary hover:bg-surface-container hover:text-primary cursor-pointer transition-colors group min-h-[80px]">
                                <div class="flex flex-col items-center gap-1 opacity-50 group-hover:opacity-100">
                                    <span class="material-symbols-rounded text-[20px]">add_circle</span>
                                    <span class="text-xs">Add Class</span>
                                </div>
                            </div>
                        </td>`;
                    } else if (classData.conflict) {
                        daysHtml += `
                        <td class="${borderClass}">
                            <div class="bg-error-container border border-error rounded p-2 h-full relative min-h-[80px] flex flex-col justify-center">
                                <span class="absolute -top-2 -right-2 bg-error text-on-error rounded-full w-5 h-5 flex items-center justify-center text-[10px]" title="Conflict"><span class="material-symbols-rounded text-[12px]">warning</span></span>
                                <div class="font-semibold text-on-surface">${classData.subject}</div>
                                <div class="text-error text-sm mt-1 font-medium">${classData.teacher}</div>
                                <div class="text-on-surface-variant text-xs mt-1 flex items-center gap-1"><span class="material-symbols-rounded text-[14px]">room</span>${classData.room}</div>
                            </div>
                        </td>`;
                    } else {
                        daysHtml += `
                        <td class="${borderClass}">
                            <div class="bg-surface-container-low border border-outline-variant rounded p-2 h-full min-h-[80px] flex flex-col justify-center">
                                <div class="font-semibold text-on-surface">${classData.subject}</div>
                                <div class="text-secondary text-sm mt-1">${classData.teacher}</div>
                                <div class="text-secondary text-xs mt-1 flex items-center gap-1"><span class="material-symbols-rounded text-[14px]">room</span>${classData.room}</div>
                            </div>
                        </td>`;
                    }
                });
                
                html += `
                <tr class="border-b border-outline-variant hover:bg-surface-container-low transition-colors ${bgClass}">
                    <td class="border-r border-outline-variant p-3 text-secondary font-medium whitespace-nowrap align-top sticky left-0 ${bgClass} z-10">${slot.time}<br /><span class="text-xs text-outline">${slot.time_end}</span>
                    </td>${daysHtml}
                </tr>`;
            });
            
            tbody.innerHTML = html;
        }
    });
</script>
@endsection
