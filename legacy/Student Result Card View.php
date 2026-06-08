<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .fill-icon {
            font-variation-settings: 'FILL' 1;
        }

        @media print {
            body {
                background: white;
            }

            nav,
            .no-print {
                display: none !important;
            }

            .print-area {
                margin: 0;
                padding: 0;
                box-shadow: none;
                border: none;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>

    <!-- SideNavBar -->
    
    <!-- Main Content Wrapper -->
    <div class="flex-1 ml-64 flex flex-col h-full overflow-hidden">
        <!-- TopNavBar -->
        
        <!-- Page Content -->
        <main class="flex-1 overflow-y-auto bg-surface p-margin-desktop">
            <div class="max-w-[1024px] mx-auto w-full">
                <!-- Header & Breadcrumbs -->
                <div class="flex flex-col md:flex-row md:items-end justify-between mb-lg no-print gap-md">
                    <div>
                        <nav class="flex items-center gap-xs font-label-md text-label-md text-on-surface-variant mb-xs">
                            <a class="hover:text-primary transition-colors" href="#">Academic Management</a>
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            <a class="hover:text-primary transition-colors" href="#">Examinations</a>
                            <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                            <span class="text-on-surface">Result Cards</span>
                        </nav>
                        <h1 class="font-headline-xl text-headline-xl text-on-surface">Student Result Card</h1>
                    </div>
                    <div class="flex gap-sm">
                        <button
                            class="px-md py-sm rounded border border-outline-variant bg-surface-container-lowest text-on-surface hover:bg-surface-container-low transition-colors font-label-md text-label-md flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[18px]">mail</span>
                            Email to Parent
                        </button>
                        <button
                            class="px-md py-sm rounded border border-outline-variant bg-surface-container-lowest text-on-surface hover:bg-surface-container-low transition-colors font-label-md text-label-md flex items-center gap-xs">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                            Download PDF
                        </button>
                        <button
                            class="px-md py-sm rounded bg-primary text-on-primary hover:bg-primary-container transition-colors font-label-md text-label-md flex items-center gap-xs"
                            onclick="window.print()">
                            <span class="material-symbols-outlined text-[18px]">print</span>
                            Print Result
                        </button>
                    </div>
                </div>
                <!-- Official Result Card Canvas -->
                <div
                    class="bg-surface-container-lowest border border-outline-variant rounded-DEFAULT shadow-sm p-xl print-area print:p-0">
                    <!-- Certificate Header -->
                    <div class="flex items-center justify-between border-b-2 border-primary pb-md mb-lg">
                        <div class="flex items-center gap-md">
                            <div
                                class="w-20 h-20 rounded-full border-2 border-primary flex items-center justify-center bg-surface shrink-0">
                                <span class="material-symbols-outlined text-primary text-4xl">account_balance</span>
                            </div>
                            <div>
                                <h2 class="font-headline-xl text-headline-xl text-primary uppercase tracking-tight">
                                    Government High School No. 1</h2>
                                <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Department of Basic
                                    Education, State Governance</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <h3 class="font-headline-md text-headline-md text-on-surface uppercase tracking-wide">Result
                                Card</h3>
                            <p
                                class="font-label-md text-label-md text-on-surface-variant bg-surface-container px-sm py-[2px] rounded inline-block mt-xs">
                                Annual Examination 2023-2024</p>
                        </div>
                    </div>
                    <!-- Student Info Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-md mb-xl">
                        <div class="bg-surface p-sm rounded border border-outline-variant">
                            <p class="font-label-md text-label-md text-on-surface-variant mb-1">Student Name</p>
                            <p class="font-headline-md text-headline-md text-on-surface">Aarav Kumar</p>
                        </div>
                        <div class="bg-surface p-sm rounded border border-outline-variant">
                            <p class="font-label-md text-label-md text-on-surface-variant mb-1">Roll Number</p>
                            <p class="font-headline-md text-headline-md text-on-surface">10A-01</p>
                        </div>
                        <div class="bg-surface p-sm rounded border border-outline-variant">
                            <p class="font-label-md text-label-md text-on-surface-variant mb-1">Class &amp; Section</p>
                            <p class="font-headline-md text-headline-md text-on-surface">Grade 10 - Sec A</p>
                        </div>
                        <div class="bg-surface p-sm rounded border border-outline-variant">
                            <p class="font-label-md text-label-md text-on-surface-variant mb-1">Date of Birth</p>
                            <p class="font-headline-md text-headline-md text-on-surface">15 Mar 2008</p>
                        </div>
                    </div>
                    <!-- Academic Performance Table -->
                    <div class="mb-xl">
                        <h4
                            class="font-headline-lg text-headline-lg text-on-surface mb-sm border-b border-outline-variant pb-xs">
                            Academic Performance</h4>
                        <div class="border border-outline-variant rounded overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead
                                    class="bg-surface-container-high font-label-md text-label-md text-on-surface uppercase border-b border-outline-variant">
                                    <tr>
                                        <th class="p-sm pl-md">Subject</th>
                                        <th class="p-sm text-center">Total Marks</th>
                                        <th class="p-sm text-center">Passing</th>
                                        <th class="p-sm text-center">Obtained</th>
                                        <th class="p-sm text-center">Grade</th>
                                        <th class="p-sm">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody class="font-body-md text-body-md text-on-surface">
                                    <tr class="border-b border-outline-variant">
                                        <td class="p-sm pl-md font-semibold">Mathematics</td>
                                        <td class="p-sm text-center text-on-surface-variant">150</td>
                                        <td class="p-sm text-center text-on-surface-variant">50</td>
                                        <td class="p-sm text-center font-semibold">124</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-[#d3e2ed] text-[#1a237e] px-2 py-1 rounded font-label-md">A</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Excellent logic</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant bg-[#e3f2fd]/30">
                                        <td class="p-sm pl-md font-semibold">Physics</td>
                                        <td class="p-sm text-center text-on-surface-variant">150</td>
                                        <td class="p-sm text-center text-on-surface-variant">50</td>
                                        <td class="p-sm text-center font-semibold">118</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-[#d3e2ed] text-[#1a237e] px-2 py-1 rounded font-label-md">A</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Good concepts</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant">
                                        <td class="p-sm pl-md font-semibold">Chemistry</td>
                                        <td class="p-sm text-center text-on-surface-variant">150</td>
                                        <td class="p-sm text-center text-on-surface-variant">50</td>
                                        <td class="p-sm text-center font-semibold">95</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-surface-container-high text-on-surface px-2 py-1 rounded font-label-md">B</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Needs lab focus</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant bg-[#e3f2fd]/30">
                                        <td class="p-sm pl-md font-semibold">Biology</td>
                                        <td class="p-sm text-center text-on-surface-variant">150</td>
                                        <td class="p-sm text-center text-on-surface-variant">50</td>
                                        <td class="p-sm text-center font-semibold">132</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-[#d3e2ed] text-[#1a237e] px-2 py-1 rounded font-label-md">A+</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Outstanding</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant">
                                        <td class="p-sm pl-md font-semibold">English</td>
                                        <td class="p-sm text-center text-on-surface-variant">150</td>
                                        <td class="p-sm text-center text-on-surface-variant">50</td>
                                        <td class="p-sm text-center font-semibold">105</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-surface-container-high text-on-surface px-2 py-1 rounded font-label-md">B</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Satisfactory</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant bg-[#e3f2fd]/30">
                                        <td class="p-sm pl-md font-semibold">Urdu</td>
                                        <td class="p-sm text-center text-on-surface-variant">100</td>
                                        <td class="p-sm text-center text-on-surface-variant">33</td>
                                        <td class="p-sm text-center font-semibold">78</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-[#d3e2ed] text-[#1a237e] px-2 py-1 rounded font-label-md">A</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Good expression</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant">
                                        <td class="p-sm pl-md font-semibold">Islamiyat</td>
                                        <td class="p-sm text-center text-on-surface-variant">100</td>
                                        <td class="p-sm text-center text-on-surface-variant">33</td>
                                        <td class="p-sm text-center font-semibold">82</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-[#d3e2ed] text-[#1a237e] px-2 py-1 rounded font-label-md">A</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Very Good</td>
                                    </tr>
                                    <tr class="border-b border-outline-variant bg-[#e3f2fd]/30">
                                        <td class="p-sm pl-md font-semibold">Pakistan Studies</td>
                                        <td class="p-sm text-center text-on-surface-variant">100</td>
                                        <td class="p-sm text-center text-on-surface-variant">33</td>
                                        <td class="p-sm text-center font-semibold">68</td>
                                        <td class="p-sm text-center"><span
                                                class="bg-surface-container-high text-on-surface px-2 py-1 rounded font-label-md">B</span>
                                        </td>
                                        <td class="p-sm text-on-surface-variant">Needs revision</td>
                                    </tr>
                                </tbody>
                                <tfoot
                                    class="bg-surface-container-low font-headline-md text-headline-md text-on-surface border-t-2 border-primary">
                                    <tr>
                                        <td class="p-sm pl-md font-bold uppercase tracking-tight text-primary">Grand
                                            Total</td>
                                        <td class="p-sm text-center font-bold">1050</td>
                                        <td class="p-sm text-center font-bold">349</td>
                                        <td class="p-sm text-center font-bold text-primary">802</td>
                                        <td class="p-sm text-center font-bold text-primary">A</td>
                                        <td class="p-sm"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <div class="mt-sm flex justify-end gap-md text-body-md">
                            <span class="bg-surface p-sm border border-outline-variant rounded">Percentage: <strong
                                    class="text-primary">76.38%</strong></span>
                            <span class="bg-surface p-sm border border-outline-variant rounded">Status: <strong
                                    class="text-[#006b5b]">PASS</strong></span>
                        </div>
                    </div>
                    <!-- Bottom Grid: Attendance & Remarks -->
                    <div class="grid md:grid-cols-2 gap-lg mb-xl">
                        <!-- Attendance Summary -->
                        <div>
                            <h4
                                class="font-headline-md text-headline-md text-on-surface mb-sm border-b border-outline-variant pb-xs flex items-center gap-xs">
                                <span
                                    class="material-symbols-outlined text-[20px] text-on-surface-variant">calendar_month</span>
                                Attendance Summary
                            </h4>
                            <div
                                class="bg-surface border border-outline-variant rounded p-sm flex justify-between items-center">
                                <div class="text-center px-sm border-r border-outline-variant flex-1">
                                    <p class="font-label-md text-label-md text-on-surface-variant">Total Days</p>
                                    <p class="font-headline-md text-headline-md text-on-surface">220</p>
                                </div>
                                <div class="text-center px-sm border-r border-outline-variant flex-1">
                                    <p class="font-label-md text-label-md text-on-surface-variant">Present</p>
                                    <p class="font-headline-md text-headline-md text-on-surface">205</p>
                                </div>
                                <div class="text-center px-sm flex-1">
                                    <p class="font-label-md text-label-md text-on-surface-variant">Percentage</p>
                                    <p class="font-headline-md text-headline-md text-primary">93.1%</p>
                                </div>
                            </div>
                        </div>
                        <!-- Teacher's Remarks -->
                        <div>
                            <h4
                                class="font-headline-md text-headline-md text-on-surface mb-sm border-b border-outline-variant pb-xs flex items-center gap-xs">
                                <span
                                    class="material-symbols-outlined text-[20px] text-on-surface-variant">edit_note</span>
                                Teacher's Remarks
                            </h4>
                            <div
                                class="bg-surface border border-outline-variant rounded p-sm h-[72px] flex items-center">
                                <p class="font-body-md text-body-md text-on-surface italic">"Aarav is a diligent student
                                    with a strong aptitude for sciences. Consistent effort in humanities will yield even
                                    better results."</p>
                            </div>
                        </div>
                    </div>
                    <!-- Signatures Area -->
                    <div class="mt-xl pt-xl border-t border-outline-variant grid grid-cols-3 gap-md">
                        <div class="text-center">
                            <div class="h-16 border-b border-outline-variant border-dashed mb-xs mx-auto w-3/4"></div>
                            <p class="font-label-md text-label-md text-on-surface uppercase">Class Teacher</p>
                        </div>
                        <div class="text-center relative">
                            <div
                                class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 opacity-10 pointer-events-none">
                                <span class="material-symbols-outlined text-8xl text-primary">verified</span>
                            </div>
                            <div class="h-16 mb-xs mx-auto w-3/4 flex items-end justify-center">
                                <p class="font-label-md text-label-md text-on-surface-variant mb-1">Issue Date:
                                    12-Apr-2024</p>
                            </div>
                            <p class="font-label-md text-label-md text-on-surface uppercase">Official Seal</p>
                        </div>
                        <div class="text-center">
                            <div class="h-16 border-b border-outline-variant border-dashed mb-xs mx-auto w-3/4"></div>
                            <p class="font-label-md text-label-md text-on-surface uppercase">Principal / Headmaster</p>
                        </div>
                    </div>
                </div>
                <p class="font-label-md text-label-md text-on-surface-variant text-center mt-md no-print">System
                    Generated Report. Valid without physical signature if verified via portal.</p>
            </div>
        </main>
    </div>


<?php include 'includes/footer.php'; ?>