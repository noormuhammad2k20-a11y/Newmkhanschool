<?php
// Session check could go here
?>
<!DOCTYPE html>

<html class="light" lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>GovSchool Portal - Guardian Dashboard</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "primary-fixed-dim": "#bdc2ff",
                        "error": "#ba1a1a",
                        "inverse-surface": "#2e3132",
                        "tertiary-fixed": "#e4e2e1",
                        "background": "#f8f9fa",
                        "surface-bright": "#f8f9fa",
                        "on-error": "#ffffff",
                        "tertiary-fixed-dim": "#c8c6c6",
                        "on-primary-fixed": "#000767",
                        "surface-container": "#edeeef",
                        "surface": "#f8f9fa",
                        "on-primary-fixed-variant": "#343d96",
                        "on-error-container": "#93000a",
                        "tertiary-container": "#303030",
                        "surface-container-lowest": "#ffffff",
                        "secondary-fixed-dim": "#bac9d3",
                        "on-tertiary-fixed": "#1b1c1c",
                        "surface-container-high": "#e7e8e9",
                        "primary-fixed": "#e0e0ff",
                        "on-tertiary-container": "#999897",
                        "inverse-primary": "#bdc2ff",
                        "on-tertiary": "#ffffff",
                        "on-surface": "#191c1d",
                        "on-secondary-container": "#56656e",
                        "surface-variant": "#e1e3e4",
                        "outline": "#767683",
                        "on-tertiary-fixed-variant": "#474747",
                        "on-background": "#191c1d",
                        "outline-variant": "#c6c5d4",
                        "on-secondary": "#ffffff",
                        "primary-container": "#1a237e",
                        "on-surface-variant": "#454652",
                        "on-primary-container": "#8690ee",
                        "error-container": "#ffdad6",
                        "secondary": "#526069",
                        "surface-tint": "#4c56af",
                        "on-secondary-fixed": "#0f1d25",
                        "on-secondary-fixed-variant": "#3b4951",
                        "surface-container-low": "#f3f4f5",
                        "primary": "#000666",
                        "tertiary": "#1b1b1b",
                        "secondary-fixed": "#d6e5ef",
                        "inverse-on-surface": "#f0f1f2",
                        "secondary-container": "#d3e2ed",
                        "surface-container-highest": "#e1e3e4",
                        "surface-dim": "#d9dadb",
                        "on-primary": "#ffffff"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "max-width": "1440px",
                        "lg": "24px",
                        "md": "16px",
                        "sm": "8px",
                        "xl": "32px",
                        "base": "4px",
                        "xs": "4px",
                        "margin-mobile": "16px",
                        "gutter": "20px",
                        "margin-desktop": "32px"
                    },
                    "fontFamily": {
                        "body-md": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "body-lg": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "headline-xl": ["Inter"],
                        "headline-md": ["Inter"]
                    },
                    "fontSize": {
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }],
                        "headline-lg-mobile": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-xl": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            background-color: theme('colors.background');
            color: theme('colors.on-background');
        }

        .card {
            background-color: theme('colors.surface');
            border: 1px solid theme('colors.outline-variant');
            border-radius: theme('borderRadius.lg');
        }

        .card-header {
            border-bottom: 1px solid theme('colors.outline-variant');
        }
    </style>
</head>

<body class="font-body-md text-body-md flex min-h-screen">
    <!-- SideNavBar -->
    <nav
        class="hidden md:flex flex-col p-md gap-sm bg-surface-container-low dark:bg-inverse-surface h-screen w-64 fixed left-0 top-0 border-r border-outline-variant dark:border-outline z-20">
        <div class="flex items-center gap-sm mb-lg px-md">
            <span class="material-symbols-outlined text-headline-xl font-headline-xl text-primary">school</span>
            <span class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed-dim">GovSchool
                Portal</span>
        </div>
        <div class="flex flex-col gap-xs mb-lg px-md">
            <div class="flex items-center gap-sm">
                <img alt="School Crest" class="w-10 h-10 rounded-full border border-outline-variant"
                    data-alt="A professional headshot of a student in a neat school uniform, smiling softly. The lighting is bright and evenly distributed, creating a clean, institutional mood. The background is a soft, sky-blue studio backdrop that complements a Corporate Modern design language. The image feels structured, calm, and reliable."
                    src="https://lh3.googleusercontent.com/aida-public/AB6AXuCOzlDN5Rvt7AeMDVpcE51lHBuHbbgLpN0wGZuWYNCGY9fvl9PvKtGpy6paRm53NjgrKacLzBb1PmMozAPAHc_LhSjjZyzULn2OP1sWCPGwRs4OikAZd99Dr37hCsPhLjoBeQiCvsN3CIhp5SOZHF8w3SBpeyBFvyEyU3TaUphYuoXA6VkLD9bUvNEkxDVl2W1XTYoxgPuHNclNV5n1vBLBkezlry9F11L0lNyngWbLd4WbwkHmvCxP5b6bqs5fnwkbY3-gFX89" />
                <div>
                    <p class="font-label-md text-label-md text-on-surface">Sarah Jenkins</p>
                    <p class="font-label-md text-label-md text-on-surface-variant font-normal">State High School - Grade
                        10</p>
                </div>
            </div>
        </div>
        <ul class="flex flex-col gap-xs flex-1">
            <li>
                <a class="flex items-center gap-md px-md py-sm bg-secondary-container dark:bg-primary-container text-on-secondary-container dark:text-on-primary-container font-bold rounded-full scale-95 active:scale-100 transition-transform"
                    href="#">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                    <span class="font-label-md text-label-md">Dashboard</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-md px-md py-sm text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container rounded-full transition-all scale-95 active:scale-100 transition-transform"
                    href="#">
                    <span class="material-symbols-outlined">calendar_today</span>
                    <span class="font-label-md text-label-md">Attendance</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-md px-md py-sm text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container rounded-full transition-all scale-95 active:scale-100 transition-transform"
                    href="#">
                    <span class="material-symbols-outlined">school</span>
                    <span class="font-label-md text-label-md">Academics</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-md px-md py-sm text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container rounded-full transition-all scale-95 active:scale-100 transition-transform"
                    href="#">
                    <span class="material-symbols-outlined">payments</span>
                    <span class="font-label-md text-label-md">Fee Status</span>
                </a>
            </li>
            <li>
                <a class="flex items-center gap-md px-md py-sm text-on-surface-variant dark:text-on-tertiary-container hover:bg-surface-container-high dark:hover:bg-tertiary-container rounded-full transition-all scale-95 active:scale-100 transition-transform"
                    href="#">
                    <span class="material-symbols-outlined">notifications</span>
                    <span class="font-label-md text-label-md">Notifications</span>
                </a>
            </li>
        </ul>
    </nav>
    <!-- Main Content Area -->
    <main class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopAppBar (Mobile Only or Minimal Desktop Header) -->
        <header
            class="md:hidden bg-surface dark:bg-inverse-surface border-b border-outline-variant dark:border-outline flex justify-between items-center px-margin-mobile h-16 w-full sticky top-0 z-10 transition-colors duration-200">
            <span class="font-headline-md text-headline-md font-bold text-primary dark:text-primary-fixed-dim">GovSchool
                Portal</span>
            <button
                class="text-primary dark:text-primary-fixed-dim hover:bg-surface-container dark:hover:bg-tertiary-container p-sm rounded-full transition-colors duration-200">
                <span class="material-symbols-outlined">account_circle</span>
            </button>
        </header>
        <!-- Desktop Header Area (Title Context) -->
        <div
            class="hidden md:flex justify-between items-center px-margin-desktop h-16 w-full max-w-max-width mx-auto border-b border-outline-variant bg-surface sticky top-0 z-10">
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Guardian Dashboard</h1>
            <div class="flex items-center gap-md">
                <span class="font-label-md text-label-md text-on-surface-variant">Welcome, Guardian of Sarah</span>
                <button
                    class="text-primary hover:bg-surface-container p-sm rounded-full transition-colors duration-200">
                    <span class="material-symbols-outlined">account_circle</span>
                </button>
            </div>
        </div>
        <!-- Dashboard Content -->
        <div class="p-margin-mobile md:p-margin-desktop max-w-max-width mx-auto w-full flex-1 flex flex-col gap-lg">
            <!-- Welcome Banner / Quick Stats -->
            <section class="grid grid-cols-1 md:grid-cols-3 gap-md">
                <!-- Student Overview -->
                <div class="card p-md flex flex-col justify-between col-span-1 md:col-span-2 relative overflow-hidden">
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-primary-fixed opacity-20 rounded-full -mr-10 -mt-10 blur-2xl pointer-events-none">
                    </div>
                    <div class="flex items-start gap-md z-10 relative">
                        <img alt="Sarah Jenkins Portrait"
                            class="w-20 h-20 rounded-full border-2 border-primary-fixed object-cover"
                            data-alt="A clear, professional studio portrait of a high school student in uniform. The background is a solid, light grey to ensure focus on the subject. The lighting is soft and flattering, suitable for an official school ID. The aesthetic aligns perfectly with a clean, trustworthy institutional portal design."
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuAcoiugXGI1rzovASSVM96MPzqR_T8qdEUdmBMoL0I0tWbOFTN3FUNGLe4cHBTmz2V60geCN--e9lJnRZg3sZj8_oR2NCS5g5bkp5ShFb6dQbdUwdwYQPtV1LKU_OX_X94ILFuesT-OedoYdrpYqtiwl49mhAOHSTebiNqOALxljGuMfw5PWY8GM0kEN5RMQXTNTpg9Zk97rzNP7CdzR03GZZjGWffaEIpKGSC6aXt-WE-HF_pYmH8TQlT5wwgzlkvRB2CtdqMF" />
                        <div>
                            <h2 class="font-headline-md text-headline-md text-on-surface mb-xs">Sarah Jenkins</h2>
                            <p class="font-body-md text-body-md text-on-surface-variant">Class: Grade 10 - Sec A</p>
                            <p class="font-body-md text-body-md text-on-surface-variant">Roll No: 10A-42</p>
                        </div>
                    </div>
                    <div
                        class="mt-md pt-md border-t border-outline-variant flex justify-between items-center z-10 relative">
                        <div>
                            <p
                                class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">
                                Overall Attendance</p>
                            <div class="flex items-baseline gap-sm">
                                <span class="font-headline-xl text-headline-xl text-primary">94%</span>
                                <span
                                    class="font-label-md text-label-md text-[#2e7d32] bg-[#e8f5e9] px-2 py-1 rounded-full">Good
                                    Standing</span>
                            </div>
                        </div>
                        <button
                            class="bg-surface-container hover:bg-surface-container-high text-primary font-label-md text-label-md px-md py-sm rounded-lg transition-colors border border-outline-variant">View
                            Details</button>
                    </div>
                </div>
                <!-- Fee Status Alert -->
                <div
                    class="card p-md flex flex-col justify-center items-center text-center col-span-1 border-error/30 bg-error-container/20">
                    <span class="material-symbols-outlined text-[40px] text-error mb-sm">error</span>
                    <h3 class="font-headline-md text-headline-md text-on-surface mb-xs">Fee Alert</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant mb-md">Term 2 tuition fees are pending.
                    </p>
                    <p class="font-headline-lg text-headline-lg text-error mb-md">$450.00</p>
                    <button
                        class="w-full bg-primary text-on-primary font-label-md text-label-md py-sm rounded-lg hover:bg-primary-container transition-colors">Pay
                        Now</button>
                </div>
            </section>
            <!-- Bento Grid Main Content -->
            <section class="grid grid-cols-1 md:grid-cols-12 gap-md">
                <!-- Academic Progress -->
                <div class="card col-span-1 md:col-span-8 flex flex-col">
                    <div class="card-header p-md flex justify-between items-center">
                        <div class="flex items-center gap-sm">
                            <span class="material-symbols-outlined text-primary">school</span>
                            <h3 class="font-headline-md text-headline-md text-on-surface">Recent Results: Mid-Term</h3>
                        </div>
                        <button class="text-primary font-label-md text-label-md hover:underline">Download
                            Report</button>
                    </div>
                    <div class="p-md flex-1 overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr
                                    class="border-b border-outline-variant text-on-surface-variant font-label-md text-label-md uppercase tracking-wider">
                                    <th class="pb-sm font-semibold">Subject</th>
                                    <th class="pb-sm font-semibold">Marks</th>
                                    <th class="pb-sm font-semibold">Grade</th>
                                    <th class="pb-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody class="font-body-md text-body-md">
                                <tr
                                    class="border-b border-surface-variant hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-sm py-3 text-on-surface">Mathematics</td>
                                    <td class="py-sm py-3 text-on-surface-variant">88/100</td>
                                    <td class="py-sm py-3 font-semibold text-primary">A</td>
                                    <td class="py-sm py-3"><span
                                            class="inline-block px-2 py-1 bg-[#e8f5e9] text-[#2e7d32] font-label-md text-label-md rounded-full">Pass</span>
                                    </td>
                                </tr>
                                <tr
                                    class="border-b border-surface-variant bg-surface-container-low hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-sm py-3 text-on-surface">Science</td>
                                    <td class="py-sm py-3 text-on-surface-variant">92/100</td>
                                    <td class="py-sm py-3 font-semibold text-primary">A+</td>
                                    <td class="py-sm py-3"><span
                                            class="inline-block px-2 py-1 bg-[#e8f5e9] text-[#2e7d32] font-label-md text-label-md rounded-full">Pass</span>
                                    </td>
                                </tr>
                                <tr
                                    class="border-b border-surface-variant hover:bg-surface-container-lowest transition-colors">
                                    <td class="py-sm py-3 text-on-surface">English Literature</td>
                                    <td class="py-sm py-3 text-on-surface-variant">76/100</td>
                                    <td class="py-sm py-3 font-semibold text-on-surface-variant">B</td>
                                    <td class="py-sm py-3"><span
                                            class="inline-block px-2 py-1 bg-[#e8f5e9] text-[#2e7d32] font-label-md text-label-md rounded-full">Pass</span>
                                    </td>
                                </tr>
                                <tr
                                    class="hover:bg-surface-container-lowest transition-colors bg-surface-container-low">
                                    <td class="py-sm py-3 text-on-surface">History</td>
                                    <td class="py-sm py-3 text-on-surface-variant">81/100</td>
                                    <td class="py-sm py-3 font-semibold text-primary">B+</td>
                                    <td class="py-sm py-3"><span
                                            class="inline-block px-2 py-1 bg-[#e8f5e9] text-[#2e7d32] font-label-md text-label-md rounded-full">Pass</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Upcoming Events / Schedule -->
                <div class="card col-span-1 md:col-span-4 flex flex-col">
                    <div class="card-header p-md flex justify-between items-center">
                        <div class="flex items-center gap-sm">
                            <span class="material-symbols-outlined text-primary">event</span>
                            <h3 class="font-headline-md text-headline-md text-on-surface">Upcoming</h3>
                        </div>
                    </div>
                    <div class="p-md flex flex-col gap-md flex-1">
                        <div class="flex gap-sm border-l-2 border-primary pl-sm">
                            <div class="flex flex-col min-w-[3rem]">
                                <span class="font-label-md text-label-md text-primary uppercase">Mon</span>
                                <span class="font-headline-md text-headline-md text-on-surface">12</span>
                            </div>
                            <div>
                                <p class="font-body-md text-body-md font-semibold text-on-surface">Mathematics Test</p>
                                <p class="font-body-md text-body-md text-on-surface-variant text-sm">Chapter 4 &amp; 5
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-sm border-l-2 border-outline-variant pl-sm">
                            <div class="flex flex-col min-w-[3rem]">
                                <span class="font-label-md text-label-md text-on-surface-variant uppercase">Wed</span>
                                <span class="font-headline-md text-headline-md text-on-surface-variant">14</span>
                            </div>
                            <div>
                                <p class="font-body-md text-body-md font-semibold text-on-surface">Parent-Teacher Meet
                                </p>
                                <p class="font-body-md text-body-md text-on-surface-variant text-sm">4:00 PM - Main Hall
                                </p>
                            </div>
                        </div>
                        <div class="flex gap-sm border-l-2 border-outline-variant pl-sm opacity-60">
                            <div class="flex flex-col min-w-[3rem]">
                                <span class="font-label-md text-label-md text-on-surface-variant uppercase">Fri</span>
                                <span class="font-headline-md text-headline-md text-on-surface-variant">16</span>
                            </div>
                            <div>
                                <p class="font-body-md text-body-md font-semibold text-on-surface">Science Project Due
                                </p>
                                <p class="font-body-md text-body-md text-on-surface-variant text-sm">Physics Lab</p>
                            </div>
                        </div>
                        <button
                            class="mt-auto w-full border border-outline-variant text-primary font-label-md text-label-md py-sm rounded-lg hover:bg-surface-container transition-colors">View
                            Full Calendar</button>
                    </div>
                </div>
            </section>
            <!-- Quick Actions Bottom Row -->
            <section class="grid grid-cols-2 md:grid-cols-4 gap-md pb-xl">
                <button
                    class="card p-sm md:p-md flex flex-col items-center justify-center gap-sm hover:bg-surface-container-high transition-colors text-center border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[28px]">download</span>
                    <span class="font-label-md text-label-md text-on-surface">Download Result</span>
                </button>
                <button
                    class="card p-sm md:p-md flex flex-col items-center justify-center gap-sm hover:bg-surface-container-high transition-colors text-center border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[28px]">payments</span>
                    <span class="font-label-md text-label-md text-on-surface">Pay Fees</span>
                </button>
                <button
                    class="card p-sm md:p-md flex flex-col items-center justify-center gap-sm hover:bg-surface-container-high transition-colors text-center border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[28px]">schedule</span>
                    <span class="font-label-md text-label-md text-on-surface">View Timetable</span>
                </button>
                <button
                    class="card p-sm md:p-md flex flex-col items-center justify-center gap-sm hover:bg-surface-container-high transition-colors text-center border-outline-variant">
                    <span class="material-symbols-outlined text-primary text-[28px]">mail</span>
                    <span class="font-label-md text-label-md text-on-surface">Contact Teacher</span>
                </button>
            </section>
        </div>
    </main>
</body>

</html>