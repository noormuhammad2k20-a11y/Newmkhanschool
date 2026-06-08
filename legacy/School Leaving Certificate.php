<?php
include 'includes/header.php';
?>
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }
        }
    </style>

    <!-- Action Bar (Focused View Exception) -->
    <div class="no-print sticky top-0 z-50 flex items-center justify-between px-md py-sm bg-surface-container-lowest border-b border-surface-variant shadow-sm w-full">
        <button aria-label="Go back" class="flex items-center gap-sm text-primary hover:bg-primary-fixed px-sm py-xs rounded transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            <span class="font-label-md uppercase">Student Records</span>
        </button>
        <div class="flex items-center gap-sm">
            <button class="flex items-center gap-xs px-md py-sm bg-secondary-container text-on-secondary-container rounded font-label-md uppercase hover:bg-secondary-fixed transition-colors">
                <span class="material-symbols-outlined text-[18px]">mail</span>
                Email
            </button>
            <button class="flex items-center gap-xs px-md py-sm bg-secondary-container text-on-secondary-container rounded font-label-md uppercase hover:bg-secondary-fixed transition-colors">
                <span class="material-symbols-outlined text-[18px]">download</span>
                Download PDF
            </button>
            <button class="flex items-center gap-xs px-md py-sm bg-primary text-on-primary rounded font-label-md uppercase hover:bg-primary-container transition-colors shadow-sm" onclick="window.print()">
                <span class="material-symbols-outlined text-[18px]">print</span>
                Print
            </button>
        </div>
    </div>
    <!-- Main Content Area -->
    <main class="flex-1 w-full flex justify-center relative">
        <!-- Immutable Shared Component: TopNavBar (Used here as the Certificate Body) -->
        <div class="relative flex h-auto min-h-screen w-full flex-col bg-[#f9f9fb] group/design-root overflow-x-hidden" style='font-family: Inter, "Noto Sans", sans-serif;'>
            <div class="layout-container flex h-full grow flex-col">
                <div class="px-40 flex flex-1 justify-center py-5">
                    <div class="layout-content-container flex flex-col max-w-[960px] flex-1 shadow-sm border border-outline-variant bg-white relative">
                        <!-- Added Watermark Overlay within the document container (without altering shared structural elements) -->
                        <div class="absolute inset-0 pointer-events-none flex items-center justify-center opacity-[0.03] overflow-hidden mix-blend-multiply z-0">
                            <div class="w-[600px] h-[600px] rounded-full border-[20px] border-solid border-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-[300px] text-primary">school</span>
                            </div>
                        </div>
                        <header class="flex items-center justify-between whitespace-nowrap border-b border-solid border-b-[#e9e9f2] px-10 py-3 relative z-10">
                            <div class="flex items-center gap-4 text-[#0f101a]">
                                <div class="size-4">
                                    <svg fill="none" viewbox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M44 11.2727C44 14.0109 39.8386 16.3957 33.69 17.6364C39.8386 18.877 44 21.2618 44 24C44 26.7382 39.8386 29.123 33.69 30.3636C39.8386 31.6043 44 33.9891 44 36.7273C44 40.7439 35.0457 44 24 44C12.9543 44 4 40.7439 4 36.7273C4 33.9891 8.16144 31.6043 14.31 30.3636C8.16144 29.123 4 26.7382 4 24C4 21.2618 8.16144 18.877 14.31 17.6364C8.16144 16.3957 4 14.0109 4 11.2727C4 7.25611 12.9543 4 24 4C35.0457 4 44 7.25611 44 11.2727Z" fill="currentColor"></path>
                                    </svg>
                                </div>
                                <h2 class="text-[#0f101a] text-lg font-bold leading-tight tracking-[-0.015em]">Government High School No. 1</h2>
                            </div>
                        </header>
                        <div class="flex flex-wrap justify-between gap-3 p-4 relative z-10">
                            <div class="flex min-w-72 flex-col gap-3">
                                <p class="text-[#0f101a] tracking-light text-[32px] font-bold leading-tight">SCHOOL LEAVING CERTIFICATE</p>
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Certificate No. 12345 | Date of Issue: 12-Aug-2023</p>
                            </div>
                        </div>
                        <div class="p-4 grid grid-cols-[20%_1fr] gap-x-6 relative z-10">
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Student Name</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Muhammad Ali</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Father's Name</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Ahmad Khan</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Admission Number</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">4582</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Date of Birth</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">14-May-2005 (Fourteenth May Two Thousand Five)</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">National ID (B-Form)</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">12345-6789012-3</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Date of Admission</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">01-Apr-2015</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Class at Admission</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Class 6</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Date of Withdrawal</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">10-Aug-2023</p>
                            </div>
                        </div>
                        <div class="p-4 grid grid-cols-[20%_1fr] gap-x-6 relative z-10">
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Class from which withdrawn</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Class 10</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Last Class Passed &amp; Result</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Class 10 - Pass</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Conduct and Character</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Good</p>
                            </div>
                            <div class="col-span-2 grid grid-cols-subgrid border-t border-t-[#d2d4e5] py-5">
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Reason for Leaving</p>
                                <p class="text-[#0f101a] text-sm font-normal leading-normal">Completion of Studies</p>
                            </div>
                        </div>
                        <h3 class="text-[#0f101a] text-lg font-bold leading-tight tracking-[-0.015em] px-4 pb-2 pt-4 relative z-10">Verification &amp; Signatures</h3>
                        <div class="grid grid-cols-2 gap-8 p-4 mt-8 relative z-10 border-t border-t-[#d2d4e5]">
                            <div class="flex flex-col items-center justify-end h-32 gap-2">
                                <div class="w-48 border-b border-[#0f101a]"></div>
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Prepared By (Clerk)</p>
                            </div>
                            <div class="flex flex-col items-center justify-end h-32 gap-2">
                                <div class="w-48 border-b border-[#0f101a]"></div>
                                <p class="text-[#555a91] text-sm font-normal leading-normal">Principal Signature &amp; Stamp</p>
                            </div>
                            <div class="col-span-2 mt-4 text-center">
                                <p class="text-[#555a91] text-xs font-normal leading-normal opacity-75">This is an officially computer-generated document from the State Educational Database. Any unauthorized alteration renders this document invalid.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php include 'includes/footer.php'; ?>
