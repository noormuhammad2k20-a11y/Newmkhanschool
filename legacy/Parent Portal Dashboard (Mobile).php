<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>EduGov Parent Portal - Mobile Dashboard</title>
    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <!-- Google Fonts: Inter -->
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&amp;display=swap" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-tint": "#4c56af",
                        "on-surface-variant": "#454652",
                        "on-secondary-fixed": "#0f1d25",
                        "tertiary-fixed": "#e4e2e1",
                        "surface-bright": "#f8f9fa",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f3f4f5",
                        "surface-dim": "#d9dadb",
                        "outline-variant": "#c6c5d4",
                        "secondary-fixed": "#d6e5ef",
                        "secondary-container": "#d3e2ed",
                        "surface-variant": "#e1e3e4",
                        "on-secondary": "#ffffff",
                        "tertiary-container": "#303030",
                        "on-tertiary-container": "#999897",
                        "surface": "#f8f9fa",
                        "on-surface": "#191c1d",
                        "on-error": "#ffffff",
                        "tertiary": "#1b1b1b",
                        "primary-fixed": "#e0e0ff",
                        "secondary": "#526069",
                        "on-primary-fixed": "#000767",
                        "surface-container": "#edeeef",
                        "surface-container-highest": "#e1e3e4",
                        "surface-container-high": "#e7e8e9",
                        "error-container": "#ffdad6",
                        "on-secondary-container": "#56656e",
                        "primary-fixed-dim": "#bdc2ff",
                        "on-tertiary-fixed-variant": "#474747",
                        "secondary-fixed-dim": "#bac9d3",
                        "on-primary-fixed-variant": "#343d96",
                        "inverse-on-surface": "#f0f1f2",
                        "inverse-primary": "#bdc2ff",
                        "on-secondary-fixed-variant": "#3b4951",
                        "background": "#f8f9fa",
                        "on-tertiary-fixed": "#1b1c1c",
                        "primary": "#000666",
                        "on-primary": "#ffffff",
                        "outline": "#767683",
                        "on-tertiary": "#ffffff",
                        "on-background": "#191c1d",
                        "primary-container": "#1a237e",
                        "on-error-container": "#93000a",
                        "surface-container-lowest": "#ffffff",
                        "tertiary-fixed-dim": "#c8c6c6",
                        "on-primary-container": "#8690ee",
                        "inverse-surface": "#2e3132"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "gutter": "20px",
                        "base": "4px",
                        "max-width": "1440px",
                        "margin-desktop": "32px",
                        "xs": "4px",
                        "sm": "8px",
                        "md": "16px",
                        "xl": "32px",
                        "margin-mobile": "16px",
                        "lg": "24px"
                    },
                    "fontFamily": {
                        "headline-xl": ["Inter"],
                        "body-md": ["Inter"],
                        "headline-md": ["Inter"],
                        "headline-lg": ["Inter"],
                        "label-md": ["Inter"],
                        "headline-lg-mobile": ["Inter"],
                        "body-lg": ["Inter"]
                    },
                    "fontSize": {
                        "headline-xl": ["32px", { "lineHeight": "40px", "letterSpacing": "-0.02em", "fontWeight": "700" }],
                        "body-md": ["14px", { "lineHeight": "20px", "fontWeight": "400" }],
                        "headline-md": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "headline-lg": ["24px", { "lineHeight": "32px", "letterSpacing": "-0.01em", "fontWeight": "600" }],
                        "label-md": ["12px", { "lineHeight": "16px", "letterSpacing": "0.05em", "fontWeight": "600" }],
                        "headline-lg-mobile": ["20px", { "lineHeight": "28px", "fontWeight": "600" }],
                        "body-lg": ["16px", { "lineHeight": "24px", "fontWeight": "400" }]
                    }
                }
            }
        }
    </script>
    <style>
        .gauge-ring {
            background: conic-gradient(var(--tw-gradient-stops));
        }
        .hide-scrollbar::-webkit-scrollbar {
            display: none;
        }
        .hide-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>
<body class="bg-background text-on-background font-body-md antialiased overflow-x-hidden">
    <div class="max-w-md mx-auto relative min-h-screen pb-24 shadow-sm bg-surface">
        <header class="bg-surface fixed top-0 w-full z-50 border-b border-outline-variant transition-colors active:scale-95 duration-100 flex items-center justify-between px-margin-mobile h-16 max-w-md mx-auto left-0 right-0">
            <div class="flex items-center gap-sm hover:bg-surface-container-high transition-colors p-1 rounded-full cursor-pointer">
                <img alt="Student Portrait" class="w-8 h-8 rounded-full object-cover border border-outline-variant" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDpozogJQTdoTMvEAe9ACK7hHy2oaqKeswDJOzA1N6m7oZnKKwhn-oXOX6MA61X_iyTBv6DGHvGw6OClutRqwaWpEhKTMSNqYHd3vek8RIe9s10-FpqreKuZA6tPoI3j8hLu-KoRergerfkxKCSIUFTZMmk4mZ2UkhepXXUJ9M8a_ECsut4CDJPNAi9WerTOlye-WHjhLmf5C4-o5cES4Oz6YPKP3QpIPIcqkfi6j_cv1KojP67U7dFWOFVWdC8uhlee0A2NJ4U" />
            </div>
            <h1 class="font-headline-md text-headline-md font-bold text-primary truncate">EduGov Parent Portal</h1>
            <button aria-label="Profile Settings" class="flex items-center justify-center hover:bg-surface-container-high transition-colors p-2 rounded-full text-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">account_circle</span>
            </button>
        </header>
        <main class="pt-20 px-margin-mobile flex flex-col gap-lg pb-safe">
            <section class="bg-surface-container-lowest rounded border border-outline-variant p-md flex items-center justify-between shadow-[0_4px_12px_rgba(26,35,126,0.02)]">
                <div class="flex items-center gap-md">
                    <img alt="Sarah Jenkins" class="w-14 h-14 rounded-full object-cover border-2 border-surface-tint shadow-sm" src="https://lh3.googleusercontent.com/aida-public/AB6AXuA_AuxjMyGzUoURy6ZMgSMNxpotXCjs2E8GrQLBkLQWKsnyBfU5sSyvtszwe8-yWs3NUM1_cJvgaTygUxoz_ayY4c-rpssC6R_3ubjEW4I7LqjgX70LN0VQapreRv-ahQJv16dJ6aCieTxmTNtQCSbF21qblvlMebe0v71rZZlLsynycOU-_CBvxrfPI6OhV-jw9Y-NHYXmK2jsGOI2J3vEF2vpBiZcM0HIq8XjBeEDYzBwOwWHWKz1Z8FLnBF8wq-HdkyhG5a0" />
                    <div>
                        <h2 class="font-headline-md text-headline-md text-on-surface">Sarah Jenkins</h2>
                        <p class="font-body-md text-body-md text-on-surface-variant mt-0.5">Grade 10 • Section A</p>
                    </div>
                </div>
                <div class="flex flex-col items-center">
                    <div class="relative w-12 h-12 rounded-full gauge-ring from-primary via-primary to-surface-variant flex items-center justify-center" style="--tw-gradient-from-position: 0%; --tw-gradient-via-position: 94%; --tw-gradient-to-position: 94%;">
                        <div class="w-10 h-10 bg-surface-container-lowest rounded-full flex items-center justify-center absolute inset-1">
                            <span class="font-label-md text-label-md text-primary">94%</span>
                        </div>
                    </div>
                    <span class="font-label-md text-[10px] uppercase text-on-surface-variant mt-1 tracking-wider">Attendance</span>
                </div>
            </section>
            <section class="bg-error-container border border-error rounded p-md flex items-start gap-sm">
                <span class="material-symbols-outlined text-error mt-0.5" style="font-variation-settings: 'FILL' 1;">error</span>
                <div class="flex-1">
                    <h3 class="font-headline-md text-headline-lg-mobile text-on-error-container mb-1">Fee Pending</h3>
                    <p class="font-body-md text-body-md text-on-error-container mb-3">Term 2 tuition fees are pending. <strong>$450.00</strong></p>
                    <button class="bg-error text-on-error font-label-md text-label-md px-4 py-2 rounded uppercase tracking-wider transition-opacity hover:opacity-90 active:scale-95 shadow-sm inline-flex items-center gap-2">
                        Pay Now <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                    </button>
                </div>
            </section>
            <section class="grid grid-cols-2 gap-sm">
                <button class="bg-surface-container-lowest border border-outline-variant rounded p-sm flex flex-col items-center justify-center gap-2 hover:bg-surface-container-low transition-colors active:scale-95 h-24 shadow-[0_2px_8px_rgba(26,35,126,0.02)]">
                    <div class="bg-secondary-container text-on-secondary-container p-2 rounded-full">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">download</span>
                    </div>
                    <span class="font-label-md text-label-md text-on-surface">Download Result</span>
                </button>
                <button class="bg-surface-container-lowest border border-outline-variant rounded p-sm flex flex-col items-center justify-center gap-2 hover:bg-surface-container-low transition-colors active:scale-95 h-24 shadow-[0_2px_8px_rgba(26,35,126,0.02)]">
                    <div class="bg-secondary-container text-on-secondary-container p-2 rounded-full">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">payments</span>
                    </div>
                    <span class="font-label-md text-label-md text-on-surface">Pay Fees</span>
                </button>
                <button class="bg-surface-container-lowest border border-outline-variant rounded p-sm flex flex-col items-center justify-center gap-2 hover:bg-surface-container-low transition-colors active:scale-95 h-24 shadow-[0_2px_8px_rgba(26,35,126,0.02)]">
                    <div class="bg-secondary-container text-on-secondary-container p-2 rounded-full">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">calendar_view_week</span>
                    </div>
                    <span class="font-label-md text-label-md text-on-surface">View Timetable</span>
                </button>
                <button class="bg-surface-container-lowest border border-outline-variant rounded p-sm flex flex-col items-center justify-center gap-2 hover:bg-surface-container-low transition-colors active:scale-95 h-24 shadow-[0_2px_8px_rgba(26,35,126,0.02)]">
                    <div class="bg-secondary-container text-on-secondary-container p-2 rounded-full">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">chat_bubble</span>
                    </div>
                    <span class="font-label-md text-label-md text-on-surface">Contact Teacher</span>
                </button>
            </section>
            <section class="bg-surface-container-lowest rounded border border-outline-variant overflow-hidden shadow-[0_4px_12px_rgba(26,35,126,0.02)]">
                <div class="px-md py-sm border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <h3 class="font-headline-md text-[16px] leading-[24px] font-semibold text-on-surface">Recent Results</h3>
                    <button class="text-primary font-label-md text-label-md uppercase tracking-wider hover:underline">View All</button>
                </div>
                <div class="flex flex-col">
                    <div class="flex justify-between items-center px-md py-sm bg-surface-container-lowest border-b border-outline-variant last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">calculate</span>
                            </div>
                            <span class="font-body-md text-body-md text-on-surface font-medium">Mathematics</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-headline-md text-[16px] text-primary">A</span>
                            <span class="bg-[#e8f5e9] text-[#2e7d32] px-2 py-0.5 rounded-full font-label-md text-[10px] uppercase border border-[#c8e6c9]">Pass</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-md py-sm bg-surface-bright border-b border-outline-variant last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">science</span>
                            </div>
                            <span class="font-body-md text-body-md text-on-surface font-medium">Physics</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-headline-md text-[16px] text-primary">B+</span>
                            <span class="bg-[#e8f5e9] text-[#2e7d32] px-2 py-0.5 rounded-full font-label-md text-[10px] uppercase border border-[#c8e6c9]">Pass</span>
                        </div>
                    </div>
                    <div class="flex justify-between items-center px-md py-sm bg-surface-container-lowest border-b border-outline-variant last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded bg-surface-container flex items-center justify-center text-on-surface-variant">
                                <span class="material-symbols-outlined text-[18px]">menu_book</span>
                            </div>
                            <span class="font-body-md text-body-md text-on-surface font-medium">Literature</span>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="font-headline-md text-[16px] text-primary">A-</span>
                            <span class="bg-[#e8f5e9] text-[#2e7d32] px-2 py-0.5 rounded-full font-label-md text-[10px] uppercase border border-[#c8e6c9]">Pass</span>
                        </div>
                    </div>
                </div>
            </section>
            <section class="bg-surface-container-lowest rounded border border-outline-variant p-md shadow-[0_4px_12px_rgba(26,35,126,0.02)]">
                <h3 class="font-headline-md text-[16px] leading-[24px] font-semibold text-on-surface mb-4">Upcoming Activities</h3>
                <div class="relative pl-4 border-l-2 border-surface-variant ml-2 flex flex-col gap-5">
                    <div class="relative">
                        <div class="absolute -left-[23px] top-1 w-3 h-3 rounded-full bg-primary border-2 border-surface-container-lowest"></div>
                        <div class="flex flex-col">
                            <span class="font-label-md text-[11px] text-on-surface-variant uppercase tracking-wider mb-0.5">Mon, 12 Oct • 09:00 AM</span>
                            <h4 class="font-body-md text-[15px] font-semibold text-on-surface">Mathematics Term Test</h4>
                            <p class="font-body-md text-[13px] text-on-surface-variant mt-1">Room 304, Main Building</p>
                        </div>
                    </div>
                    <div class="relative">
                        <div class="absolute -left-[23px] top-1 w-3 h-3 rounded-full bg-surface-dim border-2 border-surface-container-lowest"></div>
                        <div class="flex flex-col">
                            <span class="font-label-md text-[11px] text-on-surface-variant uppercase tracking-wider mb-0.5">Wed, 14 Oct • 04:30 PM</span>
                            <h4 class="font-body-md text-[15px] font-semibold text-on-surface">Parent-Teacher Meet</h4>
                            <p class="font-body-md text-[13px] text-on-surface-variant mt-1">Virtual Meeting via Portal</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <nav class="bg-surface fixed bottom-0 w-full z-50 border-t border-outline-variant flex justify-around items-center h-20 px-2 pb-safe max-w-md mx-auto left-0 right-0">
            <button class="flex flex-col items-center justify-center bg-secondary-container text-on-secondary-container rounded-full px-4 py-1 active:scale-90 duration-200 hover:bg-surface-variant transition-all">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">dashboard</span>
                <span class="font-label-md text-label-md mt-1">Dashboard</span>
            </button>
            <button class="flex flex-col items-center justify-center text-on-secondary-container opacity-70 active:scale-90 duration-200 hover:bg-surface-variant transition-all px-2 py-1 rounded-full">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">school</span>
                <span class="font-label-md text-label-md mt-1">Academics</span>
            </button>
            <button class="flex flex-col items-center justify-center text-on-secondary-container opacity-70 active:scale-90 duration-200 hover:bg-surface-variant transition-all px-2 py-1 rounded-full">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">calendar_today</span>
                <span class="font-label-md text-label-md mt-1">Attendance</span>
            </button>
            <button class="flex flex-col items-center justify-center text-on-secondary-container opacity-70 active:scale-90 duration-200 hover:bg-surface-variant transition-all px-2 py-1 rounded-full">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">payments</span>
                <span class="font-label-md text-label-md mt-1">Fees</span>
            </button>
            <button class="flex flex-col items-center justify-center text-on-secondary-container opacity-70 active:scale-90 duration-200 hover:bg-surface-variant transition-all px-2 py-1 rounded-full relative">
                <div class="absolute top-1 right-2 w-2 h-2 bg-error rounded-full border border-surface"></div>
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 0;">notifications</span>
                <span class="font-label-md text-label-md mt-1">Alerts</span>
            </button>
        </nav>
    </div>
</body>
</html>
