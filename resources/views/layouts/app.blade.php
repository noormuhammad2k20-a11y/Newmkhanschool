<!DOCTYPE html>
<html class="light" lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduGov Management</title>
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
    <style>
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
<body class="bg-background text-on-background font-body-md min-h-screen flex">
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
                                case 2:
                                    $portalName = 'Admin Portal'; break;
                                case 3:
                                    $portalName = 'Teacher Portal'; break;
                                case 4:
                                    $portalName = 'Student Portal'; break;
                                case 5:
                                    $portalName = 'Parent Portal'; break;
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
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.dashboard*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                
                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">People Management</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.students*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.students') }}">
                        <span class="material-symbols-outlined" data-icon="school">school</span>
                        <span class="font-label-md text-label-md">Students</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.teachers*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.teachers') }}">
                        <span class="material-symbols-outlined" data-icon="person">person</span>
                        <span class="font-label-md text-label-md">Teachers</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg text-secondary hover:bg-surface-container-high transition-transform duration-200 ease-in-out" href="{{ route('parent.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="family_home">family_home</span>
                        <span class="font-label-md text-label-md">Parents Portal</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Attendance & Academics</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.attendance.mark*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.attendance.mark') }}">
                        <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                        <span class="font-label-md text-label-md">Student Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.attendance.teacher*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.attendance.teacher') }}">
                        <span class="material-symbols-outlined" data-icon="how_to_reg">how_to_reg</span>
                        <span class="font-label-md text-label-md">Teacher Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.academics*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.academics.index') }}">
                        <span class="material-symbols-outlined" data-icon="library_books">library_books</span>
                        <span class="font-label-md text-label-md">Classes & Subjects</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.classes.timetable*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.classes.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                        <span class="font-label-md text-label-md">Timetable</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.exams*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.exams') }}">
                        <span class="material-symbols-outlined" data-icon="history_edu">history_edu</span>
                        <span class="font-label-md text-label-md">Examination</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.calendar*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.calendar') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_month">calendar_month</span>
                        <span class="font-label-md text-label-md">Academic Calendar</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.events*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.events') }}">
                        <span class="material-symbols-outlined" data-icon="event">event</span>
                        <span class="font-label-md text-label-md">School Events</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.announcements*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.announcements') }}">
                        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md">Announcements</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Finance & Resources</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.fees*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.fees') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Fee Management</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.payroll*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.payroll') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Staff Payroll</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.inventory*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.inventory') }}">
                        <span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
                        <span class="font-label-md text-label-md">Asset Management</span>
                    </a>
                </li>


                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">Reports & Analytics</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.reports*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.reports') }}">
                        <span class="material-symbols-outlined" data-icon="description">description</span>
                        <span class="font-label-md text-label-md">Reports</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.analytics*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.analytics.index') }}">
                        <span class="material-symbols-outlined" data-icon="insert_chart">insert_chart</span>
                        <span class="font-label-md text-label-md">Analytics</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.promotions*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.promotions.index') }}">
                        <span class="material-symbols-outlined" data-icon="trending_up">trending_up</span>
                        <span class="font-label-md text-label-md">Promotions</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">AI Modules</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.attendance*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.attendance') }}">
                        <span class="material-symbols-outlined" data-icon="online_prediction">online_prediction</span>
                        <span class="font-label-md text-label-md">Attendance Prediction</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.risk*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.risk') }}">
                        <span class="material-symbols-outlined" data-icon="psychology_alt">psychology_alt</span>
                        <span class="font-label-md text-label-md">Student Risk Analysis</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.timetable*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="smart_toy">smart_toy</span>
                        <span class="font-label-md text-label-md">Timetable Generator</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.ai.reports*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.ai.reports') }}">
                        <span class="material-symbols-outlined" data-icon="document_scanner">document_scanner</span>
                        <span class="font-label-md text-label-md">AI Report Generator</span>
                    </a>
                </li>

                <li class="px-md py-xs mt-sm">
                    <span class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider">System Settings</span>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('admin.roles*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('admin.roles') }}">
                        <span class="material-symbols-outlined" data-icon="admin_panel_settings">admin_panel_settings</span>
                        <span class="font-label-md text-label-md">Roles & Permissions</span>
                    </a>
                </li>


            @elseif(auth()->check() && auth()->user()->role_id == 3)
                @php
                    $teacherUser = \App\Models\Teacher::where('user_id', auth()->id())->first();
                    $assignedModules = $teacherUser ? \Illuminate\Support\Facades\DB::table('teacher_module_access')->where('teacher_id', $teacherUser->id)->pluck('module_name')->toArray() : [];
                @endphp
                <!-- Teacher Links -->
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.dashboard*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                @if(in_array('attendance', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.attendance*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.attendance') }}">
                        <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                        <span class="font-label-md text-label-md">Student Attendance</span>
                    </a>
                </li>
                @endif
                @if(in_array('classes', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.classes*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.classes') }}">
                        <span class="material-symbols-outlined" data-icon="class">class</span>
                        <span class="font-label-md text-label-md">My Classes</span>
                    </a>
                </li>
                @endif
                @if(in_array('subjects', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.subjects*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.subjects') }}">
                        <span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
                        <span class="font-label-md text-label-md">My Subjects</span>
                    </a>
                </li>
                @endif
                @if(in_array('students', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.students*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.students') }}">
                        <span class="material-symbols-outlined" data-icon="groups">groups</span>
                        <span class="font-label-md text-label-md">Student Lists</span>
                    </a>
                </li>
                @endif
                @if(in_array('marks', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.marks*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.marks') }}">
                        <span class="material-symbols-outlined" data-icon="grade">grade</span>
                        <span class="font-label-md text-label-md">Marks & Grades</span>
                    </a>
                </li>
                @endif
                @if(in_array('assignments', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.assignments*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.assignments') }}">
                        <span class="material-symbols-outlined" data-icon="assignment">assignment</span>
                        <span class="font-label-md text-label-md">Assignments</span>
                    </a>
                </li>
                @endif
                @if(in_array('homework', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.homework*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.homework') }}">
                        <span class="material-symbols-outlined" data-icon="home_work">home_work</span>
                        <span class="font-label-md text-label-md">Homework</span>
                    </a>
                </li>
                @endif
                @if(in_array('exams', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.exams*') && !request()->routeIs('teacher.online-exams*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.exams') }}">
                        <span class="material-symbols-outlined" data-icon="history_edu">history_edu</span>
                        <span class="font-label-md text-label-md">Exams & Results</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.online-exams*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.online-exams.index') }}">
                        <span class="material-symbols-outlined" data-icon="quiz">quiz</span>
                        <span class="font-label-md text-label-md">Online Exams</span>
                    </a>
                </li>
                @endif
                @if(in_array('exam_schedule', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.exam-schedule*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.exam-schedule') }}">
                        <span class="material-symbols-outlined" data-icon="event_note">event_note</span>
                        <span class="font-label-md text-label-md">Exam Schedule</span>
                    </a>
                </li>
                @endif
                @if(in_array('timetable', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.timetable*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_view_week">calendar_view_week</span>
                        <span class="font-label-md text-label-md">Timetable</span>
                    </a>
                </li>
                @endif
                @if(in_array('leaves', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.leaves*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.leaves') }}">
                        <span class="material-symbols-outlined" data-icon="event_busy">event_busy</span>
                        <span class="font-label-md text-label-md">Leave Requests</span>
                    </a>
                </li>
                @endif
                @if(in_array('announcements', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.announcements*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.announcements') }}">
                        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md">Announcements</span>
                    </a>
                </li>
                @endif
                @if(in_array('performance', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.performance*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.performance') }}">
                        <span class="material-symbols-outlined" data-icon="monitoring">monitoring</span>
                        <span class="font-label-md text-label-md">Student Performance</span>
                    </a>
                </li>
                @endif
                @if(in_array('profile', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.profile*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.profile') }}">
                        <span class="material-symbols-outlined" data-icon="manage_accounts">manage_accounts</span>
                        <span class="font-label-md text-label-md">My Profile</span>
                    </a>
                </li>
                @endif
                @if(in_array('messages', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.messages*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.messages') }}">
                        <span class="material-symbols-outlined" data-icon="forum">forum</span>
                        <span class="font-label-md text-label-md">Messaging</span>
                    </a>
                </li>
                @endif
                @if(in_array('reports', $assignedModules))
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('teacher.reports*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('teacher.reports') }}">
                        <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                        <span class="font-label-md text-label-md">Reports</span>
                    </a>
                </li>
                @endif
            @elseif(auth()->check() && auth()->user()->role_id == 4)
                <!-- Student Links -->
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.dashboard*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.dashboard') }}">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-md text-label-md">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.marks*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.marks') }}">
                        <span class="material-symbols-outlined" data-icon="grade">grade</span>
                        <span class="font-label-md text-label-md">My Marks</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.fees*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.fees') }}">
                        <span class="material-symbols-outlined" data-icon="payments">payments</span>
                        <span class="font-label-md text-label-md">Fee Status</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.timetable*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.timetable') }}">
                        <span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
                        <span class="font-label-md text-label-md">Timetable</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.assignments*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.assignments') }}">
                        <span class="material-symbols-outlined" data-icon="assignment">assignment</span>
                        <span class="font-label-md text-label-md">Assignments</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.attendance*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.attendance') }}">
                        <span class="material-symbols-outlined" data-icon="fact_check">fact_check</span>
                        <span class="font-label-md text-label-md">Attendance</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.report-card*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.report-card') }}">
                        <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
                        <span class="font-label-md text-label-md">Report Card</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.exam-schedule*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.exam-schedule') }}">
                        <span class="material-symbols-outlined" data-icon="event">event</span>
                        <span class="font-label-md text-label-md">Exam Schedule</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.digital_learning.notes*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.digital_learning.notes') }}">
                        <span class="material-symbols-outlined" data-icon="menu_book">menu_book</span>
                        <span class="font-label-md text-label-md">Digital Notes</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.digital_learning.quizzes*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.digital_learning.quizzes') }}">
                        <span class="material-symbols-outlined" data-icon="quiz">quiz</span>
                        <span class="font-label-md text-label-md">Quizzes</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.online-exams*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.online-exams.index') }}">
                        <span class="material-symbols-outlined" data-icon="desktop_windows">desktop_windows</span>
                        <span class="font-label-md text-label-md">Online Exams</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.announcements*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.announcements') }}">
                        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
                        <span class="font-label-md text-label-md">Announcements</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.leave*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.leave.index') }}">
                        <span class="material-symbols-outlined" data-icon="event_busy">event_busy</span>
                        <span class="font-label-md text-label-md">Leave Requests</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.messages*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.messages') }}">
                        <span class="material-symbols-outlined" data-icon="forum">forum</span>
                        <span class="font-label-md text-label-md">Messages</span>
                    </a>
                </li>
                <li>
                    <a class="flex items-center gap-md px-md py-sm rounded-lg transition-transform duration-200 ease-in-out {{ request()->routeIs('student.profile*') ? 'bg-primary-container text-on-primary-container font-semibold' : 'text-secondary hover:bg-surface-container-high' }}" href="{{ route('student.profile') }}">
                        <span class="material-symbols-outlined" data-icon="person">person</span>
                        <span class="font-label-md text-label-md">My Profile</span>
                    </a>
                </li>
            @elseif(auth()->check() && auth()->user()->role_id == 5)
                <!-- Parent Links -->
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
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.exam-schedule*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.children') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.exam-schedule*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="event_note">event_note</span>
                        <span class="font-label-md text-label-md tracking-wide">Exam Schedule</span>
                        @if(request()->routeIs('parent.child.exam-schedule*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.report-card*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.children') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.report-card*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="description">description</span>
                        <span class="font-label-md text-label-md tracking-wide">Report Card</span>
                        @if(request()->routeIs('parent.child.report-card*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.child.leave*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.children') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.child.leave*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="event_busy">event_busy</span>
                        <span class="font-label-md text-label-md tracking-wide">Leave Application</span>
                        @if(request()->routeIs('parent.child.leave*'))
                            <div class="ml-auto w-1.5 h-1.5 rounded-full bg-primary"></div>
                        @endif
                    </a>
                </li>
                <li class="mb-1">
                    <a class="group flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all duration-300 ease-in-out {{ request()->routeIs('parent.transport*') ? 'bg-primary-fixed text-primary font-bold shadow-sm' : 'text-secondary hover:bg-surface-container-high hover:text-on-surface' }}" href="{{ route('parent.transport') }}">
                        <span class="material-symbols-outlined text-[22px] transition-transform duration-300 {{ request()->routeIs('parent.transport*') ? 'scale-110' : 'group-hover:scale-110' }}" data-icon="directions_bus">directions_bus</span>
                        <span class="font-label-md text-label-md tracking-wide">Transport</span>
                        @if(request()->routeIs('parent.transport*'))
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
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
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
                <button class="text-secondary hover:bg-surface-container p-sm rounded-full transition-colors cursor-pointer active:opacity-80">
                    <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                </button>
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
                    } else {
                        okBtn.className = 'px-4 py-2 rounded-lg text-label-md font-label-md bg-primary text-on-primary hover:bg-primary-container hover:text-on-primary-container transition-colors shadow-sm';
                        iconContainer.className = 'w-10 h-10 rounded-full bg-primary-container text-primary flex items-center justify-center';
                        icon.textContent = 'warning';
                    }
                    
                    // Show modal
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    requestAnimationFrame(() => {
                        modal.classList.remove('opacity-0');
                        modalInner.classList.remove('scale-95');
                    });
                    
                    const cleanup = () => {
                        modal.classList.add('opacity-0');
                        modalInner.classList.add('scale-95');
                        setTimeout(() => {
                            modal.classList.add('hidden');
                            modal.classList.remove('flex');
                        }, 200);
                        
                        cancelBtn.removeEventListener('click', onCancel);
                        okBtn.removeEventListener('click', onOk);
                    };
                    
                    const onCancel = () => { cleanup(); resolve(false); };
                    const onOk = () => { cleanup(); resolve(true); };
                    
                    cancelBtn.addEventListener('click', onCancel);
                    okBtn.addEventListener('click', onOk);
                });
            }
        };

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
        });
    </script>
</body>
</html>
