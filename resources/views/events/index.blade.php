@extends('layouts.app')

@section('title', 'School Events & News')

@section('content')
        <main class="flex-1 p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
            <!-- Page Header & Actions -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
                <div>
                    <h1 class="text-headline-lg font-headline-lg font-semibold text-on-surface">School Events &amp; News</h1>
                    <p class="font-body-md text-body-md text-secondary mt-xs">Manage district announcements, academic
                        calendars, and school highlights.</p>
                </div>
                <button
                    class="bg-primary text-on-primary hover:bg-on-primary-fixed-variant transition-colors rounded-lg px-lg py-sm font-label-md text-label-md flex items-center gap-sm w-fit" onclick="openAddEventModal()">
                    <span class="material-symbols-outlined" style="font-size: 18px;">add_circle</span>
                    Post New Announcement
                </button>
            </div>
            
            <div id="events-container" class="bento-grid">
                <!-- Data will be loaded here dynamically via AJAX -->
                <div class="text-center w-full py-10 bento-item-large">
                    <p class="text-secondary">Loading events...</p>
                </div>
            </div>
        </main>
        
        <!-- Add Event Modal (Hidden by default) -->
        <div id="addEventModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center">
            <div class="bg-surface-container-lowest p-lg rounded-xl w-full max-w-md">
                <h3 class="font-headline-lg mb-4 text-on-surface">Post New Event/News</h3>
                <form id="addEventForm">
                    @csrf
                    <div class="mb-4">
                        <label class="block font-label-md text-secondary mb-1">Title</label>
                        <input type="text" name="title" required class="w-full border-outline-variant rounded-md px-3 py-2" />
                    </div>
                    <div class="mb-4">
                        <label class="block font-label-md text-secondary mb-1">Type</label>
                        <select name="type" class="w-full border-outline-variant rounded-md px-3 py-2">
                            <option value="Event">Event</option>
                            <option value="News">News</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block font-label-md text-secondary mb-1">Start Date</label>
                        <input type="date" name="start_date" required class="w-full border-outline-variant rounded-md px-3 py-2" />
                    </div>
                    <div class="mb-4">
                        <label class="block font-label-md text-secondary mb-1">Description</label>
                        <textarea name="description" rows="3" class="w-full border-outline-variant rounded-md px-3 py-2"></textarea>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="button" onclick="closeAddEventModal()" class="px-4 py-2 border border-outline-variant rounded-md text-secondary">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-primary text-white rounded-md">Save</button>
                    </div>
                </form>
            </div>
        </div>

<script>
    function openAddEventModal() {
        document.getElementById('addEventModal').classList.remove('hidden');
    }
    
    function closeAddEventModal() {
        document.getElementById('addEventModal').classList.add('hidden');
        document.getElementById('addEventForm').reset();
    }

    document.getElementById('addEventForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const data = Object.fromEntries(formData.entries());
        
        fetch('/api/events', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(response => {
            if (response.status === 'success') {
                closeAddEventModal();
                loadEvents();
            } else {
                UI.showToast('Error: ' + response.message, 'error');
            }
        });
    });

    function loadEvents() {
        fetch('/api/events')
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    renderEvents(response.data);
                }
            });
    }

    function renderEvents(events) {
        const container = document.getElementById('events-container');
        if (events.length === 0) {
            container.innerHTML = '<div class="text-center w-full py-10 bento-item-large"><p class="text-secondary">No events found.</p></div>';
            return;
        }

        // Just rendering a simple list for demonstration (matching the original visual structure roughly)
        let html = '';
        
        // Highlight Event (first event)
        if(events[0]) {
            html += `
            <div class="bento-item-large bg-surface-container-lowest rounded-xl border border-outline-variant overflow-hidden flex flex-col md:flex-row relative">
                <div class="md:w-1/2 p-lg flex flex-col justify-center z-10 bg-surface-container-lowest md:bg-transparent md:bg-gradient-to-r md:from-surface-container-lowest md:via-surface-container-lowest md:to-transparent">
                    <span class="bg-secondary-container text-primary font-label-md text-label-md px-sm py-xs rounded-full w-fit mb-md border border-primary-fixed-dim">Featured ${events[0].type}</span>
                    <h3 class="font-headline-lg text-headline-lg text-on-surface mb-sm">${events[0].title}</h3>
                    <p class="font-body-md text-body-md text-secondary mb-lg max-w-md">${events[0].description || ''}</p>
                    <div class="flex items-center gap-md font-label-md text-label-md text-secondary">
                        <div class="flex items-center gap-xs"><span class="material-symbols-outlined" style="font-size: 16px;">calendar_today</span> ${events[0].start_date}</div>
                        ${events[0].location ? `<div class="flex items-center gap-xs"><span class="material-symbols-outlined" style="font-size: 16px;">location_on</span> ${events[0].location}</div>` : ''}
                    </div>
                </div>
                <div class="md:w-1/2 h-64 md:h-auto absolute inset-0 md:relative z-0" style="background-image: url('${events[0].image_url || 'https://lh3.googleusercontent.com/aida-public/AB6AXuCck7gLRp59TQXrEOhATjQDOpgqS-kodIk6Y1EjM2S8pjPnsdriMxcWKXiH-S7l8B61gHN2qv1X6eL2GoZAuSnMXRFhvaZvsa0mudsbcn4y8duMsAIykUvW_1hDhG6H4R1PVSgdtuMD8_DAIh4PEO83hAiFDKWnShV09Jd6RdI5-W-iFDOAppEQ_1sumaDtZMmQR4wjFd9hDCtvT3sg0nb0-RUhUlGlB0V-FUTsjxAn2JbFUsn5FGfWFsehOIQ8M4blJyZHbDI-'}'); background-size: cover; background-position: center;">
                </div>
            </div>`;
        }

        html += `
        <div class="bento-item-main flex flex-col gap-md">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md">
                <div class="flex items-center justify-between border-b border-outline-variant pb-sm mb-md">
                    <h3 class="font-headline-md text-headline-md text-on-surface">News Feed</h3>
                </div>
                <div class="space-y-md">`;
        
        events.slice(1).forEach(event => {
            if(event.type === 'News') {
                html += `
                <div class="flex gap-md p-sm hover:bg-surface-bright rounded-lg transition-colors border border-transparent hover:border-outline-variant cursor-pointer">
                    <div class="w-12 h-12 rounded bg-error-container text-on-error-container flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined">campaign</span>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md text-on-surface">${event.title}</h4>
                        <p class="font-body-md text-body-md text-secondary mt-xs line-clamp-2">${event.description || ''}</p>
                        <span class="font-label-md text-label-md text-outline mt-sm block">${event.start_date}</span>
                    </div>
                </div>`;
            }
        });
        
        html += `</div></div></div>
        <div class="bento-item-side flex flex-col gap-md">
            <div class="bg-surface-container-lowest rounded-xl border border-outline-variant p-md">
                <div class="flex items-center justify-between border-b border-outline-variant pb-sm mb-md">
                    <h3 class="font-headline-md text-headline-md text-on-surface">Upcoming Events</h3>
                </div>
                <div class="space-y-sm">`;
                
        events.slice(1).forEach(event => {
            if(event.type === 'Event') {
                let dateParts = event.start_date.split('-');
                let month = new Date(event.start_date).toLocaleString('default', { month: 'short' });
                let day = dateParts[2];
                html += `
                <div class="flex gap-sm items-start p-sm rounded-lg border border-outline-variant bg-surface-bright">
                    <div class="w-10 h-10 rounded bg-primary-container text-on-primary-container flex flex-col items-center justify-center flex-shrink-0">
                        <span class="text-[10px] font-bold uppercase leading-none">${month}</span>
                        <span class="font-bold text-lg leading-none">${day}</span>
                    </div>
                    <div>
                        <h4 class="font-label-md text-label-md text-on-surface">${event.title}</h4>
                        ${event.location ? `<p class="text-[11px] text-secondary mt-xs flex items-center gap-1"><span class="material-symbols-outlined" style="font-size: 12px;">location_on</span> ${event.location}</p>` : ''}
                    </div>
                </div>`;
            }
        });

        html += `</div></div></div>`;
        container.innerHTML = html;
    }

    // Load events on page load
    document.addEventListener('DOMContentLoaded', loadEvents);
</script>
@endsection
