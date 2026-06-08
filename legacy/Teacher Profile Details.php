<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
<style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <!-- SideNavBar (Desktop) -->
    
    <!-- Main Content Area -->
    <div class="flex-1 md:ml-64 flex flex-col min-h-screen">
        <!-- TopNavBar -->
        
        <!-- Page Content -->
        <main class="flex-1 p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
            <!-- Breadcrumb & Actions -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-lg gap-md">
                <div>
                    <nav aria-label="Breadcrumb" class="flex text-body-md font-body-md text-on-surface-variant mb-xs">
                        <ol class="flex items-center space-x-2">
                            <li><a class="hover:text-primary transition-colors" href="#">Teachers</a></li>
                            <li><span class="material-symbols-outlined text-[16px]">chevron_right</span></li>
                            <li aria-current="page" class="text-on-surface font-semibold">Profile</li>
                        </ol>
                    </nav>
                    <h2
                        class="text-headline-lg-mobile md:text-headline-lg font-headline-lg-mobile md:font-headline-lg text-on-background">
                        Teacher Profile</h2>
                </div>
                <div class="flex gap-md w-full md:w-auto">
                    <button
                        class="flex-1 md:flex-none px-lg py-sm bg-surface-container-lowest border border-outline-variant text-primary text-label-md font-label-md rounded-DEFAULT hover:bg-surface-container-low transition-colors flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">print</span>
                        Print
                    </button>
                    <button
                        class="flex-1 md:flex-none px-lg py-sm bg-primary text-on-primary text-label-md font-label-md rounded-DEFAULT hover:bg-surface-tint transition-colors flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">edit</span>
                        Edit Profile
                    </button>
                </div>
            </div>
            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-lg">
                <!-- Profile Header Card (Spans full width on mobile, 4 cols on desktop) -->
                <div
                    class="md:col-span-4 bg-surface-container-lowest border border-outline-variant rounded-DEFAULT p-lg flex flex-col items-center text-center">
                    <div class="relative mb-md">
                        <img alt="Teacher Avatar"
                            class="w-32 h-32 rounded-full object-cover border-4 border-surface shadow-sm"
                            data-alt="A professional headshot of a male high school mathematics teacher. He is wearing a crisp white dress shirt and a subtle patterned tie. The lighting is studio-quality, bright and analytical, reflecting an academic environment. The background is a solid, soft gray, ensuring the subject is the focal point. The mood is serious, knowledgeable, and reliable."
                            src="https://lh3.googleusercontent.com/aida-public/AB6AXuBTC6JBFAEWGyMCezPRprlIOnh2y1xixIqA6kq6EAJ91VN6WBvusRwuW56q3H-W0O6FB5rMfvfhUS-gDH3bqpWifVsNKioZBY3F2q6TAJDwNsNjmuaKNDLNtXcCngiHEkhtIYEIEFcmoVjIyrQGSibmp9loOVZmAYdnoheXHDZxGVB12lFmtao_pe9ptbCuGV4x4DXahygYH4jw0sfboXBLWfDhBPoyskBvTM_4C2IWHb51Ivlv_f4NZTYbOsTz2gLoDW-TeZpa" />
                        <span
                            class="absolute bottom-2 right-2 w-4 h-4 bg-[#10b981] border-2 border-surface rounded-full"
                            title="Active Status"></span>
                    </div>
                    <h3 class="text-headline-md font-headline-md text-on-surface mb-xs">Ahmad Raza</h3>
                    <p class="text-body-lg font-body-lg text-secondary mb-xs">Senior Mathematics Teacher</p>
                    <div
                        class="inline-flex items-center gap-xs bg-secondary-container text-on-secondary-container px-sm py-xs rounded-full text-label-md font-label-md mb-lg">
                        <span class="material-symbols-outlined text-[14px]">badge</span>
                        EMP-2018-0492
                    </div>
                    <div class="w-full border-t border-outline-variant pt-md flex flex-col gap-sm text-left">
                        <div class="flex items-center gap-md">
                            <span class="material-symbols-outlined text-on-surface-variant text-[20px]">mail</span>
                            <span
                                class="text-body-md font-body-md text-on-surface truncate">ahmad.raza@goved.edu.pk</span>
                        </div>
                        <div class="flex items-center gap-md">
                            <span class="material-symbols-outlined text-on-surface-variant text-[20px]">call</span>
                            <span class="text-body-md font-body-md text-on-surface">+92 300 1234567</span>
                        </div>
                        <div class="flex items-center gap-md">
                            <span
                                class="material-symbols-outlined text-on-surface-variant text-[20px]">location_on</span>
                            <span class="text-body-md font-body-md text-on-surface">Block 4, Clifton, Karachi</span>
                        </div>
                    </div>
                </div>
                <!-- Main Info Area (Spans 8 cols on desktop) -->
                <div class="md:col-span-8 flex flex-col gap-lg">
                    <!-- Personal Details Card -->
                    <div
                        class="bg-surface-container-lowest border border-outline-variant rounded-DEFAULT flex flex-col">
                        <div class="px-lg py-md border-b border-outline-variant flex items-center gap-sm">
                            <span class="material-symbols-outlined text-primary">person</span>
                            <h4 class="text-headline-md font-headline-md text-on-surface text-[18px]">Personal Details
                            </h4>
                        </div>
                        <div class="p-lg grid grid-cols-1 sm:grid-cols-2 gap-y-md gap-x-lg">
                            <div>
                                <p class="text-label-md font-label-md text-on-surface-variant mb-xs">Date of Birth</p>
                                <p class="text-body-md font-body-md text-on-surface">14 Aug 1985</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface-variant mb-xs">Gender</p>
                                <p class="text-body-md font-body-md text-on-surface">Male</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface-variant mb-xs">Nationality</p>
                                <p class="text-body-md font-body-md text-on-surface">Pakistani</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface-variant mb-xs">Blood Group</p>
                                <p class="text-body-md font-body-md text-on-surface">O+</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface-variant mb-xs">Date of Joining</p>
                                <p class="text-body-md font-body-md text-on-surface">01 Sep 2018</p>
                            </div>
                            <div>
                                <p class="text-label-md font-label-md text-on-surface-variant mb-xs">Current Scale (BPS)
                                </p>
                                <p class="text-body-md font-body-md text-on-surface">BPS-17</p>
                            </div>
                        </div>
                    </div>
                    <!-- Education History -->
                    <div
                        class="bg-surface-container-lowest border border-outline-variant rounded-DEFAULT flex flex-col">
                        <div class="px-lg py-md border-b border-outline-variant flex items-center gap-sm">
                            <span class="material-symbols-outlined text-primary">school</span>
                            <h4 class="text-headline-md font-headline-md text-on-surface text-[18px]">Education History
                            </h4>
                        </div>
                        <div class="p-0 overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead
                                    class="bg-surface-container-low text-label-md font-label-md text-on-surface-variant border-b border-outline-variant">
                                    <tr>
                                        <th class="py-sm px-lg font-semibold">Degree/Certificate</th>
                                        <th class="py-sm px-lg font-semibold">Institution</th>
                                        <th class="py-sm px-lg font-semibold">Year</th>
                                        <th class="py-sm px-lg font-semibold">Grade/CGPA</th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="text-body-md font-body-md text-on-surface divide-y divide-outline-variant">
                                    <tr class="hover:bg-surface-container-lowest transition-colors bg-[#e3f2fd]/20">
                                        <td class="py-md px-lg font-medium">M.Phil Mathematics</td>
                                        <td class="py-md px-lg">University of Karachi</td>
                                        <td class="py-md px-lg">2012</td>
                                        <td class="py-md px-lg">3.8</td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-lowest transition-colors">
                                        <td class="py-md px-lg font-medium">BS Mathematics</td>
                                        <td class="py-md px-lg">University of Karachi</td>
                                        <td class="py-md px-lg">2008</td>
                                        <td class="py-md px-lg">3.6</td>
                                    </tr>
                                    <tr class="hover:bg-surface-container-lowest transition-colors bg-[#e3f2fd]/20">
                                        <td class="py-md px-lg font-medium">B.Ed</td>
                                        <td class="py-md px-lg">Iqra University</td>
                                        <td class="py-md px-lg">2014</td>
                                        <td class="py-md px-lg">A</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Document Checklist -->
                    <div
                        class="bg-surface-container-lowest border border-outline-variant rounded-DEFAULT flex flex-col">
                        <div class="px-lg py-md border-b border-outline-variant flex items-center justify-between">
                            <div class="flex items-center gap-sm">
                                <span class="material-symbols-outlined text-primary">folder_open</span>
                                <h4 class="text-headline-md font-headline-md text-on-surface text-[18px]">Document
                                    Checklist</h4>
                            </div>
                            <span
                                class="text-label-md font-label-md bg-[#e8f5e9] text-[#2e7d32] px-sm py-xs rounded-full">All
                                Verified</span>
                        </div>
                        <div class="p-lg grid grid-cols-1 sm:grid-cols-2 gap-md">
                            <!-- Doc Item 1 -->
                            <div
                                class="flex items-center justify-between p-md border border-outline-variant rounded-DEFAULT bg-surface">
                                <div class="flex items-center gap-md">
                                    <div
                                        class="w-10 h-10 rounded-DEFAULT bg-primary-container text-on-primary-container flex items-center justify-center">
                                        <span class="material-symbols-outlined">badge</span>
                                    </div>
                                    <div>
                                        <p class="text-body-md font-body-md font-semibold text-on-surface">CNIC Copy</p>
                                        <p class="text-label-md font-label-md text-on-surface-variant font-normal">PDF •
                                            1.2 MB</p>
                                    </div>
                                </div>
                                <button aria-label="View Document"
                                    class="text-primary hover:bg-primary-fixed-dim/20 p-sm rounded-full transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                            <!-- Doc Item 2 -->
                            <div
                                class="flex items-center justify-between p-md border border-outline-variant rounded-DEFAULT bg-surface">
                                <div class="flex items-center gap-md">
                                    <div
                                        class="w-10 h-10 rounded-DEFAULT bg-primary-container text-on-primary-container flex items-center justify-center">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <div>
                                        <p class="text-body-md font-body-md font-semibold text-on-surface">M.Phil Degree
                                        </p>
                                        <p class="text-label-md font-label-md text-on-surface-variant font-normal">PDF •
                                            2.4 MB</p>
                                    </div>
                                </div>
                                <button aria-label="View Document"
                                    class="text-primary hover:bg-primary-fixed-dim/20 p-sm rounded-full transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                            <!-- Doc Item 3 -->
                            <div
                                class="flex items-center justify-between p-md border border-outline-variant rounded-DEFAULT bg-surface">
                                <div class="flex items-center gap-md">
                                    <div
                                        class="w-10 h-10 rounded-DEFAULT bg-primary-container text-on-primary-container flex items-center justify-center">
                                        <span class="material-symbols-outlined">school</span>
                                    </div>
                                    <div>
                                        <p class="text-body-md font-body-md font-semibold text-on-surface">B.Ed
                                            Certificate</p>
                                        <p class="text-label-md font-label-md text-on-surface-variant font-normal">JPG •
                                            0.8 MB</p>
                                    </div>
                                </div>
                                <button aria-label="View Document"
                                    class="text-primary hover:bg-primary-fixed-dim/20 p-sm rounded-full transition-colors flex items-center justify-center">
                                    <span class="material-symbols-outlined">visibility</span>
                                </button>
                            </div>
                            <!-- Doc Item 4 -->
                            <div
                                class="flex items-center justify-between p-md border border-outline-variant rounded-DEFAULT bg-surface">
                                <div class="flex items-center gap-md">
                                    <div
                                        class="w-10 h-10 rounded-DEFAULT bg-surface-container-highest text-on-surface-variant flex items-center justify-center border border-outline-variant border-dashed">
                                        <span class="material-symbols-outlined">add</span>
                                    </div>
                                    <div>
                                        <p
                                            class="text-body-md font-body-md font-semibold text-primary cursor-pointer hover:underline">
                                            Upload Document</p>
                                        <p class="text-label-md font-label-md text-on-surface-variant font-normal">Max
                                            5MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>


<?php include 'includes/footer.php'; ?>