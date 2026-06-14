<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduGov Management</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-container-lowest": "#ffffff",
                        "surface-bright": "#f8f9fa",
                        "on-tertiary-container": "#999897",
                        "surface-dim": "#d9dadb",
                        "on-tertiary-fixed-variant": "#474747",
                        "on-surface-variant": "#454652",
                        "surface-tint": "#4c56af",
                        "secondary-fixed": "#d6e5ef",
                        "on-tertiary-fixed": "#1b1c1c",
                        "primary": "#000666",
                        "on-primary-fixed-variant": "#343d96",
                        "on-secondary-container": "#56656e",
                        "on-tertiary": "#ffffff",
                        "surface-container-highest": "#e1e3e4",
                        "inverse-primary": "#bdc2ff",
                        "primary-container": "#1a237e",
                        "error-container": "#ffdad6",
                        "outline-variant": "#c6c5d4",
                        "outline": "#767683",
                        "surface-container-high": "#e7e8e9",
                        "tertiary-fixed-dim": "#c8c6c6",
                        "surface-container": "#edeeef",
                        "secondary-container": "#d3e2ed",
                        "primary-fixed": "#e0e0ff",
                        "primary-fixed-dim": "#bdc2ff",
                        "surface": "#f8f9fa",
                        "on-primary-fixed": "#000767",
                        "on-primary-container": "#8690ee",
                        "secondary": "#526069",
                        "on-background": "#191c1d",
                        "inverse-on-surface": "#f0f1f2",
                        "secondary-fixed-dim": "#bac9d3",
                        "on-secondary-fixed": "#0f1d25",
                        "on-secondary": "#ffffff",
                        "error": "#ba1a1a",
                        "tertiary": "#1b1b1b",
                        "on-error": "#ffffff",
                        "on-primary": "#ffffff",
                        "surface-container-low": "#f3f4f5",
                        "tertiary-container": "#303030",
                        "inverse-surface": "#2e3132",
                        "tertiary-fixed": "#e4e2e1",
                        "on-surface": "#191c1d",
                        "on-secondary-fixed-variant": "#3b4951",
                        "surface-variant": "#e1e3e4",
                        "on-error-container": "#93000a",
                        "background": "#f8f9fa"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "xl": "32px",
                        "xs": "4px",
                        "md": "16px",
                        "margin-mobile": "16px",
                        "sm": "8px",
                        "max-width": "1440px",
                        "base": "4px",
                        "lg": "24px",
                        "margin-desktop": "32px",
                        "gutter": "20px"
                    },
                    "fontFamily": {
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-xl": ["Inter"]
                    },
                    "fontSize": {
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "headline-lg-mobile": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "headline-xl": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }]
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        .material-symbols-outlined, .material-symbols-rounded {
            font-family: 'Material Symbols Outlined';
            font-weight: normal;
            font-style: normal;
            font-size: 24px;
            line-height: 1;
            letter-spacing: normal;
            text-transform: none;
            display: inline-block;
            white-space: nowrap;
            word-wrap: normal;
            direction: ltr;
            -webkit-font-feature-settings: 'liga';
            -webkit-font-smoothing: antialiased;
        }

        .btn-primary {
            @apply inline-flex items-center justify-center bg-primary text-white font-semibold py-2.5 px-5 rounded-lg hover:bg-[#000444] transition-all shadow-sm;
        }

        .btn-outline {
            @apply inline-flex items-center justify-center border border-outline-variant bg-surface text-on-surface font-semibold py-2.5 px-5 rounded-lg hover:bg-surface-container-high transition-all;
        }

        .input-field {
            @apply w-full border border-outline-variant bg-surface-container-lowest rounded-lg px-4 py-2.5 text-on-surface focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary transition-all;
        }

        .bento-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 20px;
        }

        .bento-item-large {
            grid-column: span 12;
        }

        .bento-item-main {
            grid-column: span 12;
        }

        .bento-item-side {
            grid-column: span 12;
        }

        @media (min-width: 1024px) {
            .bento-item-large {
                grid-column: span 12;
            }

            .bento-item-main {
                grid-column: span 8;
            }

            .bento-item-side {
                grid-column: span 4;
            }
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md min-h-screen flex" style="zoom: 0.8;">
    <!-- Mobile Sidebar Overlay -->
    <div id="mobile-sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <!-- SideNavBar -->
    <nav id="sidebar" class="hidden md:flex flex-col bg-surface-container w-64 h-full fixed left-0 top-0 z-50 py-md transition-transform transform md:translate-x-0 -translate-x-full">
        <div class="px-md mb-lg">
            <div class="flex items-center gap-sm mb-sm">
                <div class="w-10 h-10 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center font-headline-md font-bold">
                    SE
                </div>
                <div>
                    <h1 class="font-headline-lg text-headline-lg text-primary">State Education</h1>
                    @php
                        $portalName = 'Portal';
                        if(auth()->check()) {
                            switch(auth()->user()->role_id) {
                                case 1:
                                case 2: $portalName = 'Admin Portal'; break;
                                case 3: $portalName = 'Teacher Portal'; break;
                                case 4: $portalName = 'Student Portal'; break;
                                case 5: $portalName = 'Parent Portal'; break;
                                case 6: $portalName = 'Accountant Portal'; break;
                            }
                        }
                    @endphp
                    <p class="font-label-md text-label-md text-secondary">{{ $portalName }}</p>
                </div>
            </div>
        </div>
        <ul class="flex-1 px-sm space-y-xs overflow-y-auto pb-4">
            @if(auth()->check() && in_array(auth()->user()->role_id, [1, 2]))
                <!-- Admin Links -->
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.dashboard*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                
                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">People Management</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.students*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.students') }}">
                        <span class="material-symbols-outlined" data-icon="school">school</span>
                        <span class="font-label-md text-label-md">Students</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.teachers*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.teachers') }}">
                        <span class="material-symbols-outlined" data-icon="person">person</span>
                        <span class="font-label-md text-label-md">Teachers</span>
                    </a>
                </li>


                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Attendance & Academics</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.attendance.mark*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.attendance.mark') }}">
                        <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                        <span class="font-label-md text-label-md">Student Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.attendance.teacher*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.attendance.teacher') }}">
                        <span class="material-symbols-outlined" data-icon="how_to_reg">how_to_reg</span>
                        <span class="font-label-md text-label-md">Teacher Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.academics*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.academics.index') }}">
                        <span class="material-symbols-outlined" data-icon="library_books">library_books</span>
                        <span class="font-label-md text-label-md">Classes & Subjects</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.classes.timetable*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.classes.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                        <span class="font-label-md text-label-md">Timetable</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.exams*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.exams') }}">
                        <span class="material-symbols-outlined" data-icon="history_edu">history_edu</span>
                        <span class="font-label-md text-label-md">Examination</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.calendar*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.calendar') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
                        <span class="font-label-md text-label-md">Academic Calendar</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.events*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.events') }}">
                        <span class="material-symbols-outlined" data-icon="event">event</span>
                        <span class="font-label-md text-label-md">School Events</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.announcements*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.announcements') }}">
                        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md">Announcements</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Finance & Resources</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.fees*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.fees') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Fee Management</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.payroll*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.payroll') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Staff Payroll</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.inventory*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.inventory') }}">
                        <span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
                        <span class="font-label-md text-label-md">Inventory Management</span>
                    </a>
                </li>


                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Administration</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.branches*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.branches.index') }}">
                        <span class="material-symbols-outlined" data-icon="account_tree">account_tree</span>
                        <span class="font-label-md text-label-md">Branches</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Reports & Analytics</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.documents.index*') || request()->routeIs('admin.documents.create*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.documents.index') }}">
                        <span class="material-symbols-outlined" data-icon="description">description</span>
                        <span class="font-label-md text-label-md">Documents & Certificates</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.documents.templates*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.documents.templates') }}">
                        <span class="material-symbols-outlined" data-icon="design_services">design_services</span>
                        <span class="font-label-md text-label-md">Document Templates</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.documents.signatures*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.documents.signatures') }}">
                        <span class="material-symbols-outlined" data-icon="draw">draw</span>
                        <span class="font-label-md text-label-md">Digital Signatures</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.reports*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.reports') }}">
                        <span class="material-symbols-outlined" data-icon="summarize">summarize</span>
                        <span class="font-label-md text-label-md">Reports</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.analytics*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.analytics.index') }}">
                        <span class="material-symbols-outlined" data-icon="insert_chart">insert_chart</span>
                        <span class="font-label-md text-label-md">Analytics</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.promotions*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.promotions.index') }}">
                        <span class="material-symbols-outlined" data-icon="trending_up">trending_up</span>
                        <span class="font-label-md text-label-md">Promotions</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.reportcards*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.reportcards.index') }}">
                        <span class="material-symbols-outlined" data-icon="grading">grading</span>
                        <span class="font-label-md text-label-md">Report Cards</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">AI Modules</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.attendance*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.attendance') }}">
                        <span class="material-symbols-outlined" data-icon="online_prediction">online_prediction</span>
                        <span class="font-label-md text-label-md">Attendance Prediction</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.risk*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.risk') }}">
                        <span class="material-symbols-outlined" data-icon="psychology_alt">psychology_alt</span>
                        <span class="font-label-md text-label-md">Student Risk Analysis</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.timetable*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="smart_toy">smart_toy</span>
                        <span class="font-label-md text-label-md">Timetable Generator</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.reports*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.reports') }}">
                        <span class="material-symbols-outlined" data-icon="document_scanner">document_scanner</span>
                        <span class="font-label-md text-label-md">AI Report Generator</span>
                    </a>
                </li>



                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">System Settings</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.roles*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.roles') }}">
                        <span class="material-symbols-outlined" data-icon="admin_panel_settings">admin_panel_settings</span>
                        <span class="font-label-md text-label-md">Roles & Permissions</span>
                    </a>
                </li>


            @elseif(auth()->check() && auth()->user()->role_id == 6)
                <!-- Accountant Links -->
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.dashboard*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                
                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Fee Management</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.fees*') || request()->routeIs('accountant.transactions*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.fees.index') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Fee Collection</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.fee-structure*') || request()->routeIs('accountant.fee-categories*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.fee-structure.index') }}">
                        <span class="material-symbols-outlined" data-icon="account_tree">account_tree</span>
                        <span class="font-label-md text-label-md">Fee Structure</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.defaulters*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.defaulters.index') }}">
                        <span class="material-symbols-outlined" data-icon="warning">warning</span>
                        <span class="font-label-md text-label-md">Defaulters</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Financial Operations</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.payroll*') || request()->routeIs('accountant.tax-slips*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.payroll.index') }}">
                        <span class="material-symbols-outlined" data-icon="account_balance_wallet">account_balance_wallet</span>
                        <span class="font-label-md text-label-md">Payroll</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.expenses*') || request()->routeIs('accountant.expense-categories*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.expenses.index') }}">
                        <span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
                        <span class="font-label-md text-label-md">Expenses</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.bank-accounts*') || request()->routeIs('accountant.cash-book*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.bank-accounts.index') }}">
                        <span class="material-symbols-outlined" data-icon="account_balance">account_balance</span>
                        <span class="font-label-md text-label-md">Banking & Cash Book</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.inventory-purchases*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.inventory-purchases.index') }}">
                        <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
                        <span class="font-label-md text-label-md">Inventory Purchases</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Reporting</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.reports*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.reports.index') }}">
                        <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                        <span class="font-label-md text-label-md">Reports</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Settings</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('accountant.profile*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('accountant.profile.edit') }}">
                        <span class="material-symbols-outlined" data-icon="manage_accounts">manage_accounts</span>
                        <span class="font-label-md text-label-md">My Profile</span>
                    </a>
                </li>

            @elseif(auth()->check() && auth()->user()->role_id == 3)
                @php
                    $teacherUser = \App\Models\Teacher::where('user_id', auth()->id())->first();
                    $assignedModules = $teacherUser ? \Illuminate\Support\Facades\DB::table('teacher_module_access')->where('teacher_id', $teacherUser->id)->pluck('module_name')->toArray() : [];
                @endphp
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.dashboard*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                <li>
                    <details class="group" {{ request()->routeIs('teacher.digital_learning.*') ? 'open' : '' }}>
                        <summary class="flex items-center justify-between px-md py-sm rounded-lg transition-colors duration-200 ease-in-out cursor-pointer select-none list-none [&::-webkit-details-marker]:hidden {{ request()->routeIs('teacher.digital_learning.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}">
                            <div class="flex items-center gap-md">
                                <span class="material-symbols-outlined" data-icon="auto_stories">auto_stories</span>
                                <span class="font-label-md text-label-md">Digital Learning</span>
                            </div>
                            <span class="material-symbols-outlined transition-transform duration-200 group-open:rotate-180">expand_more</span>
                        </summary>
                        <div class="pl-[44px] pr-2 py-2 space-y-1">
                            <a href="{{ route('teacher.digital_learning.notes') }}" class="flex items-center gap-sm px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.digital_learning.notes') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}">
                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                                <span class="font-label-md text-label-md">Digital Notes</span>
                            </a>
                            <a href="{{ route('teacher.digital_learning.quizzes') }}" class="flex items-center gap-sm px-3 py-2 rounded-lg transition-colors {{ request()->routeIs('teacher.digital_learning.quizzes*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}">
                                <span class="material-symbols-outlined text-[18px]">quiz</span>
                                <span class="font-label-md text-label-md">Quizzes</span>
                            </a>
                        </div>
                    </details>
                </li>
                @if(in_array('attendance', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.attendance*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.attendance') }}">
                        <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                        <span class="font-label-md text-label-md">Student Attendance</span>
                    </a>
                </li>
                @endif
                @if(in_array('classes', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.classes*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.classes') }}">
                        <span class="material-symbols-outlined" data-icon="class">class</span>
                        <span class="font-label-md text-label-md">My Classes</span>
                    </a>
                </li>
                @endif
                @if(in_array('subjects', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.subjects*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.subjects') }}">
                        <span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
                        <span class="font-label-md text-label-md">My Subjects</span>
                    </a>
                </li>
                @endif
                @if(in_array('students', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.students*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.students') }}">
                        <span class="material-symbols-outlined" data-icon="groups">groups</span>
                        <span class="font-label-md text-label-md">Student Lists</span>
                    </a>
                </li>
                @endif
                @if(in_array('marks', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.marks*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.marks') }}">
                        <span class="material-symbols-outlined" data-icon="grade">grade</span>
                        <span class="font-label-md text-label-md">Marks & Grades</span>
                    </a>
                </li>
                @endif
                @if(in_array('assignments', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.assignments*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.assignments') }}">
                        <span class="material-symbols-outlined" data-icon="assignment">assignment</span>
                        <span class="font-label-md text-label-md">Assignments</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.ai-grader*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.ai-grader') }}">
                        <span class="material-symbols-outlined" data-icon="auto_awesome">auto_awesome</span>
                        <span class="font-label-md text-label-md">AI Auto Grader</span>
                    </a>
                </li>
                @endif
                @if(in_array('exams', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.exams*') && !request()->routeIs('teacher.online-exams*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.exams') }}">
                        <span class="material-symbols-outlined" data-icon="history_edu">history_edu</span>
                        <span class="font-label-md text-label-md">Exams & Results</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.online-exams*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.online-exams.index') }}">
                        <span class="material-symbols-outlined" data-icon="quiz">quiz</span>
                        <span class="font-label-md text-label-md">Online Exams</span>
                    </a>
                </li>
                @endif
                @if(in_array('exam_schedule', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.exam-schedule*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.exam-schedule') }}">
                        <span class="material-symbols-outlined" data-icon="event_note">event_note</span>
                        <span class="font-label-md text-label-md">Exam Schedule</span>
                    </a>
                </li>
                @endif
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.seating*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.seating.index') }}">
                        <span class="material-symbols-outlined" data-icon="grid_view">grid_view</span>
                        <span class="font-label-md text-label-md">Seating Plans</span>
                    </a>
                </li>
                @if(in_array('timetable', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.timetable*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_view_week">calendar_view_week</span>
                        <span class="font-label-md text-label-md">Timetable</span>
                    </a>
                </li>
                @endif
                @if(in_array('leaves', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.leaves*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.leaves') }}">
                        <span class="material-symbols-outlined" data-icon="event_busy">event_busy</span>
                        <span class="font-label-md text-label-md">Leave Requests</span>
                    </a>
                </li>
                @endif
                @if(in_array('announcements', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.announcements*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.announcements') }}">
                        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md">Announcements</span>
                    </a>
                </li>
                @endif
                @if(in_array('performance', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.performance*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.performance') }}">
                        <span class="material-symbols-outlined" data-icon="monitoring">monitoring</span>
                        <span class="font-label-md text-label-md">Student Performance</span>
                    </a>
                </li>
                @endif
                @if(in_array('profile', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.profile*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.profile') }}">
                        <span class="material-symbols-outlined" data-icon="manage_accounts">manage_accounts</span>
                        <span class="font-label-md text-label-md">My Profile</span>
                    </a>
                </li>
                @endif
                @if(in_array('messages', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.messages*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.messages') }}">
                        <span class="material-symbols-outlined" data-icon="forum">forum</span>
                        <span class="font-label-md text-label-md">Messaging</span>
                    </a>
                </li>
                @endif
                @if(in_array('reports', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.reports*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.reports') }}">
                        <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                        <span class="font-label-md text-label-md">Reports</span>
                    </a>
                </li>
                @endif
            @elseif(auth()->check() && auth()->user()->role_id == 4)
                <!-- Student Links -->
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.dashboard*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.marks*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.marks') }}">
                        <span class="material-symbols-outlined" data-icon="grade">grade</span>
                        <span class="font-label-md text-label-md">My Marks</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.progress*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.progress') }}">
                        <span class="material-symbols-outlined" data-icon="show_chart">show_chart</span>
                        <span class="font-label-md text-label-md">My Progress</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.fees*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.fees') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Fee Status</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.timetable*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                        <span class="font-label-md text-label-md">Timetable</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.assignments*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.assignments') }}">
                        <span class="material-symbols-outlined" data-icon="assignment">assignment</span>
                        <span class="font-label-md text-label-md">Assignments</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.attendance*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.attendance') }}">
                        <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                        <span class="font-label-md text-label-md">Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.report-card*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.report-card') }}">
                        <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                        <span class="font-label-md text-label-md">Report Card</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.exam-schedule*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.exam-schedule') }}">
                        <span class="material-symbols-outlined" data-icon="event">event</span>
                        <span class="font-label-md text-label-md">Exam Schedule</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.digital_learning.notes*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.digital_learning.notes') }}">
                        <span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
                        <span class="font-label-md text-label-md">Digital Notes</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.digital_learning.quizzes*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.digital_learning.quizzes') }}">
                        <span class="material-symbols-outlined" data-icon="quiz">quiz</span>
                        <span class="font-label-md text-label-md">Quizzes</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.online-exams*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.online-exams.index') }}">
                        <span class="material-symbols-outlined" data-icon="desktop_windows">desktop_windows</span>
                        <span class="font-label-md text-label-md">Online Exams</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.announcements*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.announcements') }}">
                        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md">Announcements</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.quiz-results*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.quiz-results') }}">
                        <span class="material-symbols-outlined" data-icon="assignment_turned_in">assignment_turned_in</span>
                        <span class="font-label-md text-label-md">Quiz Results</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.leave*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.leave.index') }}">
                        <span class="material-symbols-outlined" data-icon="event_busy">event_busy</span>
                        <span class="font-label-md text-label-md">Leave Requests</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.messages*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.messages') }}">
                        <span class="material-symbols-outlined" data-icon="forum">forum</span>
                        <span class="font-label-md text-label-md">Messages</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.profile*') ? 'bg-primary text-on-primary font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.profile') }}">
                        <span class="material-symbols-outlined" data-icon="person">person</span>
                        <span class="font-label-md text-label-md">My Profile</span>
                    </a>
                </li>
            @elseif(auth()->check() && auth()->user()->role_id == 5)
                <!-- Parent Links -->
                @php
                    $firstStudentId = \App\Models\ParentStudent::where('parent_user_id', auth()->id())->first()->student_id ?? 0;
                @endphp
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.dashboard*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.dashboard') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.dashboard*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md tracking-wide">Dashboard</span>
                        @if(request()->routeIs('parent.dashboard*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.children*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.children') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.children*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="family_restroom">family_restroom</span>
                        <span class="font-label-md text-label-md tracking-wide">My Children</span>
                        @if(request()->routeIs('parent.children*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.announcements*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.announcements') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.announcements*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md tracking-wide">Announcements</span>
                        @if(request()->routeIs('parent.announcements*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.messages*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.messages') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.messages*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="forum">forum</span>
                        <span class="font-label-md text-label-md tracking-wide">Messages</span>
                        @if(request()->routeIs('parent.messages*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.exam-schedule*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.child.exam-schedule', $firstStudentId) }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.exam-schedule*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="event_note">event_note</span>
                        <span class="font-label-md text-label-md tracking-wide">Exam Schedule</span>
                        @if(request()->routeIs('parent.child.exam-schedule*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.report-card*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.child.report-card', $firstStudentId) }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.report-card*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="description">description</span>
                        <span class="font-label-md text-label-md tracking-wide">Report Card</span>
                        @if(request()->routeIs('parent.child.report-card*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.leave*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.child.leave', $firstStudentId) }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.leave*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="event_busy">event_busy</span>
                        <span class="font-label-md text-label-md tracking-wide">Leave Application</span>
                        @if(request()->routeIs('parent.child.leave*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.online-exams*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.child.online-exams.index', $firstStudentId) }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.online-exams*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="desktop_windows">desktop_windows</span>
                        <span class="font-label-md text-label-md tracking-wide">Online Exams</span>
                        @if(request()->routeIs('parent.child.online-exams*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.fees*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.child.fees', $firstStudentId) }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.fees*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md tracking-wide">Fee Payment</span>
                        @if(request()->routeIs('parent.child.fees*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.profile*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.profile') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.profile*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="person">person</span>
                        <span class="font-label-md text-label-md tracking-wide">My Profile</span>
                        @if(request()->routeIs('parent.profile*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
            @endif
        </ul>
        <div class="px-sm mt-auto pb-4">
            <div class="px-md py-xs mt-sm">
                <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Account</span>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="m-0" data-no-ajax>
                @csrf
                @php
                    $user = Auth::user();
                    $currentGuard = 'web';
                    if ($user) {
                        if (in_array($user->role_id, [1, 2])) $currentGuard = 'admin';
                        elseif ($user->role_id == 3) $currentGuard = 'teacher';
                        elseif ($user->role_id == 4) $currentGuard = 'student';
                        elseif ($user->role_id == 5) $currentGuard = 'parent';
                        elseif ($user->role_id == 6) $currentGuard = 'accountant';
                    }
                @endphp
                <input type="hidden" name="guard" value="{{ $currentGuard }}">
                <button type="submit" class="w-full flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-high transition-transform duration-200 ease-in-out">
                    <span class="material-symbols-outlined" data-icon="logout">logout</span>
                    <span class="font-label-md text-label-md">Logout</span>
                </button>
            </form>
        </div>
    </nav>
    <!-- Main Content Area -->
    <div class="flex flex-col md:ml-64 w-full md:w-[calc(100%-16rem)] min-h-screen min-w-0">
        <!-- TopNavBar -->
        <header class="bg-surface-container-lowest w-full h-16 border-b border-outline-variant flex justify-between items-center px-lg sticky top-0 z-30">
            <div class="flex items-center gap-md">
                <!-- Mobile Menu Button -->
                <button id="mobile-menu-btn" class="md:hidden text-on-surface-variant hover:bg-surface-container p-sm rounded-full transition-colors cursor-pointer active:opacity-80">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="font-headline-md text-headline-md font-bold text-primary">EduGov Management</h1>
            </div>
            <div class="flex items-center gap-sm">
                @if(auth()->check() && auth()->user()->role_id == 5)
                    <a href="{{ route('parent.notifications.index') }}" class="relative text-secondary hover:bg-surface-container p-sm rounded-full transition-colors cursor-pointer active:opacity-80">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                        <span id="notification-badge" class="absolute top-1 right-1 w-2.5 h-2.5 border-2 border-surface-container-lowest bg-error rounded-full hidden"></span>
                    </a>
                @else
                    <button class="text-secondary hover:bg-surface-container p-sm rounded-full transition-colors cursor-pointer active:opacity-80">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                    </button>
                @endif>
                <button class="text-secondary hover:bg-surface-container p-sm rounded-full transition-colors cursor-pointer active:opacity-80">
                    <span class="material-symbols-outlined" data-icon="help">help</span>
                </button>
                <button class="text-secondary hover:bg-surface-container p-sm rounded-full transition-colors cursor-pointer active:opacity-80">
                    <span class="material-symbols-outlined" data-icon="settings">settings</span>
                </button>
                <div class="w-8 h-8 rounded-full bg-secondary-container overflow-hidden ml-sm cursor-pointer border border-outline-variant">
                    <img alt="Administrator Profile" class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB-yezbc927wY0C322XO39E8Fpj-TKkH1lavHgZS32icKx9cZkfyfH9G1q_BH780q4XWHhALV5X9kZitOylufGihq0Wd6o6gNlkskCLD8qmnDfet-MuX2Icl-w7TiaM7S4SqO5CDug81BiCvDYn6OGBKXtZw3vb2N4OG8DCquSDhDWVCxCCydotcJs0IbKIizYsoQ0nK63Dz3toqIXN5n2aaHPFI-YBQza8tPLwXkPoo5KZdewHH8OnW9C-XtWqdwDgQpT_1p3_" />
                </div>
            </div>
        </header>

        @yield('content')

    </div>

    <!-- Global Toast Notification -->
    <div id="global-toast" class="fixed top-20 right-6 z-[9999] flex flex-col gap-2 pointer-events-none"></div>

    <!-- Global Alert Modal -->
    <div id="global-alert-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-lg border border-outline-variant transform scale-95 transition-transform duration-200">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 rounded-full bg-primary-container text-primary flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">info</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-on-surface" id="global-alert-title">Alert</h3>
                </div>
                <p class="text-body-md font-body-md text-on-surface-variant mb-6" id="global-alert-message"></p>
                <div class="flex items-center justify-end">
                    <button id="global-alert-ok" class="px-4 py-2 rounded-lg text-label-md font-label-md bg-primary text-on-primary hover:bg-primary/90 transition-colors shadow-sm">OK</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Global Confirmation Modal -->
    <div id="global-confirm-modal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 bg-black/40 backdrop-blur-sm transition-opacity opacity-0">
        <div class="bg-surface-container-lowest rounded-xl max-w-md w-full shadow-lg border border-outline-variant transform scale-95 transition-transform duration-200">
            <div class="p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div id="global-confirm-icon-container" class="w-10 h-10 rounded-full bg-error-container text-error flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]" id="global-confirm-icon">warning</span>
                    </div>
                    <h3 class="text-headline-md font-headline-md font-bold text-on-surface" id="global-confirm-title">Confirm Action</h3>
                </div>
                <p class="text-body-md font-body-md text-on-surface-variant mb-6" id="global-confirm-message">Are you sure you want to proceed?</p>
                <div class="flex items-center justify-end gap-3">
                    <button id="global-confirm-cancel" class="px-4 py-2 rounded-lg text-label-md font-label-md text-secondary hover:bg-surface-container transition-colors">Cancel</button>
                    <button id="global-confirm-ok" class="px-4 py-2 rounded-lg text-label-md font-label-md transition-colors shadow-sm">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Global UI Utilities
        window.UI = {
            showToast: function(message, type = 'success') {
                const toastContainer = document.getElementById('global-toast');
                const toast = document.createElement('div');
                
                // Premium glassmorphism design with solid typography
                const isDark = document.documentElement.classList.contains('dark');
                const styleObj = type === 'success' 
                    ? `background-color: ${isDark ? 'rgba(16, 185, 129, 0.15)' : 'rgba(236, 253, 245, 0.9)'}; border-color: rgba(16, 185, 129, 0.3); backdrop-filter: blur(12px);` 
                    : `background-color: ${isDark ? 'rgba(239, 68, 68, 0.15)' : 'rgba(254, 242, 242, 0.9)'}; border-color: rgba(239, 68, 68, 0.3); backdrop-filter: blur(12px);`;
                const icon = type === 'success' ? 'check_circle' : 'error';
                const iconColor = type === 'success' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400';
                const textColor = type === 'success' ? 'text-emerald-800 dark:text-emerald-100' : 'text-red-800 dark:text-red-100';
                
                toast.className = `flex items-center gap-4 p-4 min-w-[320px] rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] border -translate-y-full opacity-0 transition-all duration-500 ease-out pointer-events-auto z-[9999]`;
                toast.style.cssText = styleObj;
                toast.innerHTML = `
                    <div class="flex-shrink-0 flex items-center justify-center w-10 h-10 rounded-full shadow-sm ${type === 'success' ? 'bg-emerald-100 dark:bg-emerald-900/40' : 'bg-red-100 dark:bg-red-900/40'}">
                        <span class="material-symbols-outlined ${iconColor} text-[22px]">${icon}</span>
                    </div>
                    <span class="text-body-md font-semibold ${textColor} flex-1 tracking-wide">${message}</span>
                    <button onclick="this.parentElement.style.opacity='0'; setTimeout(()=>this.parentElement.remove(), 300)" class="ml-auto flex items-center justify-center w-8 h-8 rounded-full hover:bg-black/5 dark:hover:bg-white/10 transition-colors ${textColor}">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                `;
                
                toastContainer.appendChild(toast);
                
                // Animate in from top
                requestAnimationFrame(() => {
                    toast.classList.remove('-translate-y-full', 'opacity-0');
                });
                
                // Auto remove after 3s
                setTimeout(() => {
                    toast.classList.add('opacity-0', '-translate-y-2');
                    setTimeout(() => toast.remove(), 300);
                }, 3000);
            },
            
            confirm: function(title, message, confirmText = 'Confirm', confirmStyle = 'error') {
                return new Promise((resolve) => {
                    const modal = document.getElementById('global-confirm-modal');
                    if (!modal) {
                        alert('Error: global-confirm-modal element not found in HTML!');
                        resolve(false);
                        return;
                    }
                    const modalInner = modal.querySelector('div');
                    const titleEl = document.getElementById('global-confirm-title');
                    const messageEl = document.getElementById('global-confirm-message');
                    const cancelBtn = document.getElementById('global-confirm-cancel');
                    const okBtn = document.getElementById('global-confirm-ok');
                    const iconContainer = document.getElementById('global-confirm-icon-container');
                    const icon = document.getElementById('global-confirm-icon');
                    
                    titleEl.textContent = title;
                    messageEl.textContent = message;
                    okBtn.textContent = confirmText;
                    
                    if (confirmStyle === 'error') {
                        okBtn.className = 'px-4 py-2 rounded-lg text-label-md font-label-md bg-error text-on-error hover:bg-[#93000a] transition-colors shadow-sm';
                        iconContainer.className = 'w-10 h-10 rounded-full bg-error-container text-error flex items-center justify-center';
                        icon.textContent = 'delete_forever';
                    } else if (confirmStyle === 'success') {
                        okBtn.className = 'px-4 py-2 rounded-lg text-label-md font-label-md bg-emerald-600 text-white hover:bg-emerald-700 transition-colors shadow-sm';
                        iconContainer.className = 'w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center';
                        icon.textContent = 'check_circle';
                    } else {
                        okBtn.className = 'px-4 py-2 rounded-lg text-label-md font-label-md bg-primary text-on-primary hover:bg-primary/90 transition-colors shadow-sm';
                        iconContainer.className = 'w-10 h-10 rounded-full bg-primary-container text-primary flex items-center justify-center';
                        icon.textContent = 'help';
                    }
                    
                    // Show modal forcefully
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                    modal.style.opacity = '1';
                    modal.style.pointerEvents = 'auto';
                    
                    if (modalInner) {
                        modalInner.style.transform = 'scale(1)';
                    }
                    
                    const cleanup = () => {
                        modal.style.opacity = '0';
                        modal.style.pointerEvents = 'none';
                        if (modalInner) modalInner.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            modal.classList.add('hidden');
                            modal.style.display = 'none';
                        }, 200);
                        
                        cancelBtn.removeEventListener('click', onCancel);
                        okBtn.removeEventListener('click', onOk);
                    };
                    
                    const onCancel = () => { cleanup(); resolve(false); };
                    const onOk = () => { cleanup(); resolve(true); };
                    
                    cancelBtn.addEventListener('click', onCancel);
                    okBtn.addEventListener('click', onOk);
                });
            },
            
            alert: function(title, message) {
                return new Promise((resolve) => {
                    const modal = document.getElementById('global-alert-modal');
                    if (!modal) {
                        alert(title + '\n\n' + message);
                        resolve(true);
                        return;
                    }
                    const modalInner = modal.querySelector('div');
                    document.getElementById('global-alert-title').textContent = title;
                    document.getElementById('global-alert-message').textContent = message;
                    const okBtn = document.getElementById('global-alert-ok');
                    
                    // Show modal forcefully
                    modal.classList.remove('hidden');
                    modal.style.display = 'flex';
                    modal.style.opacity = '1';
                    modal.style.pointerEvents = 'auto';
                    
                    if (modalInner) {
                        modalInner.style.transform = 'scale(1)';
                    }
                    
                    const cleanup = () => {
                        modal.style.opacity = '0';
                        modal.style.pointerEvents = 'none';
                        if (modalInner) modalInner.style.transform = 'scale(0.95)';
                        setTimeout(() => {
                            modal.classList.add('hidden');
                            modal.style.display = 'none';
                        }, 200);
                        okBtn.removeEventListener('click', onOk);
                    };
                    
                    const onOk = () => { cleanup(); resolve(true); };
                    okBtn.addEventListener('click', onOk);
                });
            }
        };

        // Override native alert globally
        window.alert = function(message) {
            window.UI.alert('Alert', message);
        };

        // ═══════════════════════════════════════════════════════════════════
        // GLOBAL AJAX FORM INTERCEPTOR — No Page Reloads
        // ═══════════════════════════════════════════════════════════════════
        (function() {
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
                             || '{{ csrf_token() }}';

            // Forms to exclude from AJAX handling
            function shouldExclude(form) {
                // Explicit opt-out
                if (form.hasAttribute('data-no-ajax')) return true;
                // Login form
                if (form.action && form.action.includes('/login')) return true;
                // Download / external links
                if (form.target === '_blank') return true;
                // GET forms (search, filter) — let them work normally
                if ((form.method || 'GET').toUpperCase() === 'GET') return true;
                return false;
            }

            // Determine the real HTTP method (respects _method spoofing)
            function getRealMethod(form) {
                const spoofField = form.querySelector('input[name="_method"]');
                if (spoofField) return spoofField.value.toUpperCase();
                return (form.method || 'POST').toUpperCase();
            }

            // Set loading state on submit button
            function setLoading(btn, loading) {
                if (!btn) return;
                if (loading) {
                    btn.dataset.originalText = btn.innerHTML;
                    btn.disabled = true;
                    btn.style.opacity = '0.7';
                    btn.style.pointerEvents = 'none';
                    const spinner = `<svg class="animate-spin inline-block w-4 h-4 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>`;
                    btn.innerHTML = spinner + 'Processing...';
                } else {
                    if (btn.dataset.originalText) {
                        btn.innerHTML = btn.dataset.originalText;
                    }
                    btn.disabled = false;
                    btn.style.opacity = '';
                    btn.style.pointerEvents = '';
                }
            }

            // Clear previous validation errors
            function clearValidationErrors(form) {
                form.querySelectorAll('.ajax-field-error').forEach(el => el.remove());
                form.querySelectorAll('.border-red-500, .border-error').forEach(el => {
                    el.classList.remove('border-red-500', 'border-error');
                });
            }

            // Show validation errors on fields
            function showValidationErrors(form, errors) {
                clearValidationErrors(form);
                for (const [field, messages] of Object.entries(errors)) {
                    const input = form.querySelector(`[name="${field}"], [name="${field}[]"]`);
                    if (input) {
                        input.classList.add('border-red-500');
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'ajax-field-error text-xs text-red-600 mt-1';
                        errorDiv.textContent = Array.isArray(messages) ? messages[0] : messages;
                        input.closest('div')?.appendChild(errorDiv) || input.parentNode.appendChild(errorDiv);
                    }
                }
            }

            // Smart content refresh — re-fetch page, replace main content only
            window.UI.refreshContent = function(callback) {
                const scrollY = window.scrollY;
                fetch(window.location.href, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-Content-Only': '1'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');

                    // Find the main content area in both old and new DOM
                    const newContent = doc.querySelector('main') || doc.querySelector('[role="main"]') || doc.querySelector('.flex-1.overflow-y-auto');
                    const oldContent = document.querySelector('main') || document.querySelector('[role="main"]') || document.querySelector('.flex-1.overflow-y-auto');

                    if (newContent && oldContent) {
                        oldContent.innerHTML = newContent.innerHTML;
                        // Re-run any inline scripts in the new content
                        oldContent.querySelectorAll('script').forEach(oldScript => {
                            const newScript = document.createElement('script');
                            if (oldScript.src) {
                                newScript.src = oldScript.src;
                            } else {
                                newScript.textContent = oldScript.textContent;
                            }
                            oldScript.parentNode.replaceChild(newScript, oldScript);
                        });
                    } else {
                        // Fallback: replace entire body content except sidebar
                        const newBody = doc.querySelector('.flex-1');
                        const oldBody = document.querySelector('.flex-1');
                        if (newBody && oldBody) {
                            oldBody.innerHTML = newBody.innerHTML;
                        }
                    }

                    if (callback) callback();
                })
                .catch(err => {
                    console.error('Content refresh failed:', err);
                    // Silent fallback — the toast already showed success
                });
            };

            // Global form submit interceptor
            document.addEventListener('submit', async function(e) {
                const form = e.target;
                if (!form || form.tagName !== 'FORM') return;
                if (shouldExclude(form)) return;

                // Handle standard form confirm if data-confirm is present
                if (form.hasAttribute('data-confirm')) {
                    if (form.dataset.confirmed !== 'true') {
                        e.preventDefault();
                        const message = form.getAttribute('data-confirm') || 'Are you sure you want to proceed?';
                        const isConfirmed = await window.UI.confirm('Confirm Action', message, 'Confirm', 'error');
                        if (isConfirmed) {
                            form.dataset.confirmed = 'true';
                            form.requestSubmit ? form.requestSubmit() : form.submit();
                        }
                        return;
                    } else {
                        delete form.dataset.confirmed; // Reset for next time
                    }
                }

                // Check if this form is a delete form already being handled by confirm
                // If there's a pending confirm, don't double-intercept
                if (form.dataset.ajaxProcessing === 'true') return;

                e.preventDefault();
                form.dataset.ajaxProcessing = 'true';

                const submitBtn = form.querySelector('button[type="submit"], input[type="submit"], button:not([type])');
                const method = getRealMethod(form);
                const formData = new FormData(form);

                clearValidationErrors(form);
                setLoading(submitBtn, true);

                try {
                    const fetchOptions = {
                        method: 'POST', // Always POST — Laravel uses _method for spoofing
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: formData
                    };

                    // Ensure _method is in the FormData for spoofed methods
                    if (['PUT', 'PATCH', 'DELETE'].includes(method)) {
                        if (!formData.has('_method')) {
                            formData.append('_method', method);
                        }
                    }

                    const response = await fetch(form.action, fetchOptions);
                    const contentType = response.headers.get('content-type') || '';

                    if (contentType.includes('application/json')) {
                        const data = await response.json();

                        if (response.ok && (data.status === 'success' || response.status === 200)) {
                            // Success
                            window.UI.showToast(data.message || 'Operation completed successfully!', 'success');

                            // If the server sends a redirect URL
                            if (data.redirect) {
                                setTimeout(() => { window.location.href = data.redirect; }, 600);
                            } else {
                                // Close any open modals
                                document.querySelectorAll('[id*="modal"]').forEach(modal => {
                                    if (modal.id !== 'global-confirm-modal' && modal.id !== 'global-toast') {
                                        if (!modal.classList.contains('hidden')) {
                                            modal.classList.add('hidden');
                                            modal.classList.remove('flex');
                                        }
                                    }
                                });

                                // Reset form
                                if (method === 'POST' && !['PUT', 'PATCH'].includes(formData.get('_method')?.toUpperCase())) {
                                    form.reset();
                                }

                                // Refresh page content
                                setTimeout(() => window.UI.refreshContent(), 300);
                            }
                        } else if (response.status === 422 && data.errors) {
                            // Validation errors
                            showValidationErrors(form, data.errors);
                            window.UI.showToast(data.message || 'Please fix the errors below.', 'error');
                        } else {
                            // Other error
                            window.UI.showToast(data.message || 'Something went wrong.', 'error');
                        }
                    } else if (contentType.includes('text/html')) {
                        // Server returned HTML (redirect response that was followed)
                        // Parse the HTML for session flash messages
                        const html = await response.text();
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        // Check if the response is a redirect page (login page, etc.)
                        if (doc.querySelector('form[action*="login"]')) {
                            window.location.reload();
                            return;
                        }

                        // Replace content with the new HTML
                        const newContent = doc.querySelector('main') || doc.querySelector('.flex-1.overflow-y-auto');
                        const oldContent = document.querySelector('main') || document.querySelector('.flex-1.overflow-y-auto');
                        if (newContent && oldContent) {
                            oldContent.innerHTML = newContent.innerHTML;
                            oldContent.querySelectorAll('script').forEach(oldScript => {
                                const newScript = document.createElement('script');
                                if (oldScript.src) {
                                    newScript.src = oldScript.src;
                                } else {
                                    newScript.textContent = oldScript.textContent;
                                }
                                oldScript.parentNode.replaceChild(newScript, oldScript);
                            });
                        }

                        // Extract flash messages from the response HTML
                        const successMatch = html.match(/showToast\("(.+?)",\s*'success'\)/);
                        const errorMatch = html.match(/showToast\("(.+?)",\s*'error'\)/);
                        if (successMatch) {
                            window.UI.showToast(successMatch[1], 'success');
                        } else if (errorMatch) {
                            window.UI.showToast(errorMatch[1], 'error');
                        } else {
                            window.UI.showToast('Operation completed.', 'success');
                        }

                        // reattachConfirmInterceptors(); // Removed undefined function call
                    } else {
                        // Unknown response — check if it might be a file download
                        if (response.headers.get('content-disposition')?.includes('attachment')) {
                            // Trigger download
                            const blob = await response.blob();
                            const url = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = url;
                            a.download = response.headers.get('content-disposition')?.match(/filename="?(.+?)"?$/)?.[1] || 'download';
                            document.body.appendChild(a);
                            a.click();
                            a.remove();
                            URL.revokeObjectURL(url);
                        }
                    }
                } catch (error) {
                    console.error('AJAX form submission error:', error);
                    window.UI.showToast('Network error. Please try again.', 'error');
                } finally {
                    setLoading(submitBtn, false);
                    delete form.dataset.ajaxProcessing;
                }
            }, true); // Use capture phase to intercept before other handlers

            // ─── AJAX Confirm Click & Delete Interceptor ──────────────────────────
            // Handle buttons/links with data-confirm-click or data-confirm-delete
            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('[data-confirm-delete], [data-confirm-click]');
                if (!btn) return;

                if (btn.hasAttribute('data-confirm-click')) {
                    if (btn.dataset.confirmed !== 'true') {
                        e.preventDefault();
                        e.stopPropagation();
                        const message = btn.getAttribute('data-confirm-click') || 'Are you sure you want to proceed?';
                        const isConfirmed = await window.UI.confirm('Confirm Action', message, 'Confirm', 'error');
                        if (isConfirmed) {
                            btn.dataset.confirmed = 'true';
                            btn.click();
                        }
                        return;
                    } else {
                        delete btn.dataset.confirmed;
                        return; // Let original click proceed
                    }
                }

                // Handle data-confirm-delete
                e.preventDefault();
                e.stopPropagation();

                const message = btn.getAttribute('data-confirm-delete') || 'Are you sure you want to delete this?';
                const isConfirmed = await window.UI.confirm('Confirm Delete', message, 'Yes, Delete', 'error');
                if (!isConfirmed) return;

                // Find the associated form
                const form = btn.closest('form');
                if (form) {
                    // Mark it so our submit interceptor picks it up
                    form.dataset.ajaxProcessing = 'false';
                    // Trigger submission (our submit interceptor will handle AJAX)
                    form.requestSubmit ? form.requestSubmit() : form.submit();
                } else if (btn.dataset.deleteUrl) {
                    // Standalone button with data-delete-url
                    try {
                        const response = await fetch(btn.dataset.deleteUrl, {
                            method: 'POST',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': CSRF_TOKEN,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ _method: 'DELETE' })
                        });
                        const data = await response.json();
                        if (response.ok) {
                            window.UI.showToast(data.message || 'Deleted successfully.', 'success');
                            setTimeout(() => window.UI.refreshContent(), 300);
                        } else {
                            window.UI.showToast(data.message || 'Delete failed.', 'error');
                        }
                    } catch (err) {
                        window.UI.showToast('Network error during delete.', 'error');
                    }
                }
            }, true);
        })();

        // Mobile Sidebar Toggle
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('mobile-menu-btn');
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobile-sidebar-overlay');
            if (btn && sidebar && overlay) {
                const toggleSidebar = () => {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                };
                btn.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);
            }

            // Sidebar Scroll Persistence
            const sidebarNav = document.querySelector('#sidebar ul');
            if (sidebarNav) {
                const savedScroll = sessionStorage.getItem('sidebar-scroll');
                if (savedScroll !== null) {
                    sidebarNav.scrollTop = parseInt(savedScroll, 10);
                } else {
                    const activeItem = sidebarNav.querySelector('.bg-primary-container, .bg-primary-fixed');
                    if (activeItem) {
                        const offsetFromUl = activeItem.offsetTop - sidebarNav.offsetTop;
                        sidebarNav.scrollTop = offsetFromUl - (sidebarNav.clientHeight / 2) + (activeItem.offsetHeight / 2);
                    }
                }

                window.addEventListener('beforeunload', () => {
                    sessionStorage.setItem('sidebar-scroll', sidebarNav.scrollTop);
                });
            }



            // --- Global Session Toast Handling ---
            @if(session('success'))
                setTimeout(() => window.UI.showToast({!! json_encode(session('success')) !!}, 'success'), 100);
            @endif

            @if(session('error'))
                setTimeout(() => window.UI.showToast({!! json_encode(session('error')) !!}, 'error'), 100);
            @endif

            @if($errors->any())
                setTimeout(() => window.UI.showToast({!! json_encode($errors->first()) !!}, 'error'), 100);
            @endif

        });
    </script>
    @if(auth()->check() && auth()->user()->role_id == 5)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            function checkNotifications() {
                fetch('{{ route('parent.notifications.count') }}', {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notification-badge');
                    if(badge) {
                        if(data.count > 0) {
                            badge.classList.remove('hidden');
                        } else {
                            badge.classList.add('hidden');
                        }
                    }
                })
                .catch(e => console.error('Notification check failed'));
            }
            
            checkNotifications();
            setInterval(checkNotifications, 60000);
        });
    </script>
    @endif
</body>
</html>
