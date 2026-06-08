@extends('layouts.app')

@section('title', 'Academic Calendar & Planner')

@section('content')
        <main class="flex-1 p-margin-mobile md:p-margin-desktop w-full max-w-max-width mx-auto">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-md mb-xl">
                <div>
                    <h1 class="font-headline-xl text-headline-xl text-on-surface">Academic Calendar &amp; Planner</h1>
                    <p class="font-body-md text-body-md text-secondary mt-xs">Manage institutional events, holidays, and academic milestones for the 2024-2025 session.</p>
                </div>
                <div class="flex gap-sm">
                    <button class="px-md py-sm border border-outline-variant text-primary font-label-md text-label-md rounded-lg hover:bg-surface-container-lowest bg-surface transition-colors flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]" data-icon="download">download</span> Export PDF
                    </button>
                    <button class="px-md py-sm bg-primary text-on-primary font-label-md text-label-md rounded-lg hover:bg-primary-fixed-variant transition-colors flex items-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span> New Event
                    </button>
                </div>
            </div>
            <!-- Dashboard Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-lg">
                <!-- Main Calendar View (Left Column) -->
                <div class="lg:col-span-8 flex flex-col gap-lg">
                    <!-- Calendar Card -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden flex flex-col">
                        <!-- Calendar Header -->
                        <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright">
                            <h3 class="font-headline-lg text-headline-lg text-on-surface">October 2024</h3>
                            <div class="flex gap-xs">
                                <button class="p-xs border border-outline-variant rounded hover:bg-surface-container-low text-secondary">
                                    <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
                                </button>
                                <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-low text-secondary font-label-md text-label-md">
                                    Today
                                </button>
                                <button class="p-xs border border-outline-variant rounded hover:bg-surface-container-low text-secondary">
                                    <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
                                </button>
                            </div>
                        </div>
                        <!-- Days Header -->
                        <div class="grid grid-cols-7 border-b border-outline-variant bg-surface-container">
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">MON</div>
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">TUE</div>
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">WED</div>
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">THU</div>
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">FRI</div>
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">SAT</div>
                            <div class="p-sm text-center font-label-md text-label-md text-secondary">SUN</div>
                        </div>
                        <!-- Calendar Grid -->
                        <div class="grid grid-cols-7 auto-rows-[120px] bg-outline-variant gap-px">
                            <!-- Padding Days -->
                            <div class="bg-surface-container-lowest p-sm bg-opacity-50 text-outline"></div>
                            <div class="bg-surface-container-lowest p-sm bg-opacity-50 text-outline flex justify-end font-body-md text-body-md">1</div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">2</div>
                                <div class="px-xs py-[2px] bg-secondary-container text-on-secondary-container rounded font-label-md text-[10px] truncate">Gandhi Jayanti</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">3</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">4</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-secondary">5</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-secondary">6</div>
                            </div>
                            <!-- Week 2 -->
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">7</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">8</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">9</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">10</div>
                                <div class="px-xs py-[2px] bg-error-container text-on-error-container rounded font-label-md text-[10px] truncate">Mid-Term Exams Start</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">11</div>
                                <div class="px-xs py-[2px] bg-error-container text-on-error-container rounded font-label-md text-[10px] truncate">Mathematics Exam</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-secondary">12</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs bg-surface-container-low">
                                <div class="flex justify-end font-body-md text-body-md text-primary font-bold">13</div>
                            </div>
                            <!-- Week 3 -->
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">14</div>
                                <div class="px-xs py-[2px] bg-error-container text-on-error-container rounded font-label-md text-[10px] truncate">Science Exam</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">15</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">16</div>
                                <div class="px-xs py-[2px] bg-tertiary-container text-on-tertiary-container rounded font-label-md text-[10px] truncate">Inter-School Sports</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">17</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-on-surface">18</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-secondary">19</div>
                                <div class="px-xs py-[2px] bg-primary-container text-on-primary-container rounded font-label-md text-[10px] truncate">PTM (Grades 1-5)</div>
                            </div>
                            <div class="bg-surface-container-lowest p-sm flex flex-col gap-xs">
                                <div class="flex justify-end font-body-md text-body-md text-secondary">20</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Side Panels (Right Column) -->
                <div class="lg:col-span-4 flex flex-col gap-lg">
                    <!-- Quick Add Event -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md">
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-md border-b border-outline-variant pb-xs">Add New Event</h3>
                        <form class="flex flex-col gap-sm" onsubmit="event.preventDefault(); alert('Event added successfully!');">
                            <div>
                                <label class="block font-label-md text-label-md text-on-surface mb-xs">Event Title</label>
                                <input class="w-full border border-outline-variant rounded p-sm font-body-md text-body-md focus:border-primary focus:ring-1 focus:ring-primary outline-none" placeholder="e.g., Annual Science Fair" type="text" />
                            </div>
                            <div class="grid grid-cols-2 gap-sm">
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Date</label>
                                    <input class="w-full border border-outline-variant rounded p-sm font-body-md text-body-md focus:border-primary outline-none" type="date" />
                                </div>
                                <div>
                                    <label class="block font-label-md text-label-md text-on-surface mb-xs">Type</label>
                                    <select class="w-full border border-outline-variant rounded p-sm font-body-md text-body-md focus:border-primary outline-none bg-surface-container-lowest">
                                        <option>Academic</option>
                                        <option>Holiday</option>
                                        <option>Sports</option>
                                        <option>Meeting</option>
                                    </select>
                                </div>
                            </div>
                            <button class="mt-sm w-full py-sm bg-surface border border-outline-variant text-primary font-label-md text-label-md rounded hover:bg-surface-container-low transition-colors" type="submit">
                                Add to Calendar
                            </button>
                        </form>
                    </div>
                    <!-- Upcoming Gazetted Holidays -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md">
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-md border-b border-outline-variant pb-xs flex items-center gap-sm">
                            <span class="material-symbols-outlined text-secondary" data-icon="celebration">celebration</span>
                            Upcoming Holidays
                        </h3>
                        <ul class="flex flex-col gap-sm font-body-md text-body-md">
                            <li class="flex justify-between items-center py-xs border-b border-outline-variant border-opacity-50 last:border-0">
                                <div>
                                    <span class="block text-on-surface font-medium">Gandhi Jayanti</span>
                                    <span class="block text-secondary font-label-md text-label-md">National Holiday</span>
                                </div>
                                <span class="text-on-surface font-medium">Oct 2</span>
                            </li>
                            <li class="flex justify-between items-center py-xs border-b border-outline-variant border-opacity-50 last:border-0">
                                <div>
                                    <span class="block text-on-surface font-medium">Dussehra</span>
                                    <span class="block text-secondary font-label-md text-label-md">Restricted Holiday</span>
                                </div>
                                <span class="text-on-surface font-medium">Oct 12</span>
                            </li>
                            <li class="flex justify-between items-center py-xs border-b border-outline-variant border-opacity-50 last:border-0">
                                <div>
                                    <span class="block text-on-surface font-medium">Diwali</span>
                                    <span class="block text-secondary font-label-md text-label-md">Gazetted Holiday</span>
                                </div>
                                <span class="text-on-surface font-medium">Oct 31</span>
                            </li>
                        </ul>
                    </div>
                    <!-- Term Milestones -->
                    <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md">
                        <h3 class="font-headline-md text-headline-md text-on-surface mb-md border-b border-outline-variant pb-xs flex items-center gap-sm">
                            <span class="material-symbols-outlined text-secondary" data-icon="flag">flag</span>
                            Term 1 Milestones
                        </h3>
                        <div class="relative pl-md border-l-2 border-outline-variant ml-sm flex flex-col gap-md py-sm">
                            <div class="relative">
                                <div class="absolute w-3 h-3 bg-surface border-2 border-outline-variant rounded-full -left-[23px] top-1"></div>
                                <p class="font-label-md text-label-md text-secondary">April 1, 2024</p>
                                <p class="font-body-md text-body-md text-on-surface font-medium">Academic Session Begins</p>
                            </div>
                            <div class="relative">
                                <div class="absolute w-3 h-3 bg-primary border-2 border-primary rounded-full -left-[23px] top-1"></div>
                                <p class="font-label-md text-label-md text-primary font-bold">Oct 10 - Oct 20, 2024</p>
                                <p class="font-body-md text-body-md text-on-surface font-medium">Mid-Term Examinations</p>
                            </div>
                            <div class="relative">
                                <div class="absolute w-3 h-3 bg-surface border-2 border-outline-variant rounded-full -left-[23px] top-1"></div>
                                <p class="font-label-md text-label-md text-secondary">Nov 15, 2024</p>
                                <p class="font-body-md text-body-md text-on-surface font-medium">Term 1 Results Declaration</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
@endsection
