<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
    <style>
        .print-area {
            background-color: white;
            box-shadow: 0px 4px 12px rgba(26, 35, 126, 0.08);
        }

        @media print {
            body {
                background-color: white;
            }

            .no-print {
                display: none !important;
            }

            .print-area {
                box-shadow: none;
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 0;
            }

            #SideNavBar,
            #TopNavBar,
            aside,
            header {
                display: none !important;
            }

            .main-content, main {
                margin-left: 0 !important;
                padding: 0 !important;
            }
        }

        .signature-line {
            border-bottom: 1px solid #191c1d;
            width: 200px;
            display: inline-block;
        }

        .input-line {
            border-bottom: 1px solid #191c1d;
            display: inline-block;
            min-width: 150px;
            text-align: center;
        }
    </style>
        <!-- Document Canvas -->
        <main class="flex-1 p-md md:p-margin-desktop bg-surface-container-lowest md:bg-surface flex justify-center overflow-y-auto w-full">
            <div class="w-full max-w-4xl print-area rounded border border-outline-variant p-xl md:p-[48px] bg-white relative">
                <!-- Action Bar (No Print) -->
                <div class="absolute top-4 right-4 no-print flex space-x-2">
                    <button class="flex items-center space-x-1 bg-secondary-container text-on-secondary-container font-label-md text-label-md px-3 py-1.5 rounded hover:bg-secondary-fixed-dim transition-colors" onclick="window.print()">
                        <span class="material-symbols-outlined" style="font-size: 16px;">print</span>
                        <span>Print</span>
                    </button>
                    <button class="flex items-center space-x-1 bg-primary text-on-primary font-label-md text-label-md px-3 py-1.5 rounded hover:bg-on-primary-fixed-variant transition-colors">
                        <span class="material-symbols-outlined" style="font-size: 16px;">download</span>
                        <span>Download PDF</span>
                    </button>
                </div>
                <!-- Certificate Header -->
                <div class="text-center border-b border-outline-variant pb-6 mb-8 relative">
                    <!-- Watermark Logo -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                        <img alt="Background Seal" class="w-64 h-64" data-alt="A large, very faint watermark of an institutional crest, deeply desaturated to near invisibility, centered on a white document background." src="https://lh3.googleusercontent.com/aida-public/AB6AXuD7MHipgWkkVv0iw_rhZTi-UvaKhl7Lc8TZwbge4u82BIIBF4P5KqimCgJexD_IGbGUmXgKn_yptQ4y77_HCHdFCOklG1TqLnthJS8qY2eG0VMU9EAQ5eY36EwMvO1xvIzCKpA8ps0NxVBhIV8ourz9BjoYcW-6bnyTayJI_Xzh_IMrF3j1ZanBjX0UvBweGt4uSOtxvKer3H9_r8RUVHGoQdK38mz36Ftc2df7QGjEeounxVqSkZh4Yv2sYhnHagOCUaiMcCuM" />
                    </div>
                    <div class="flex justify-between items-start mb-4">
                        <div class="text-left w-1/4">
                            <p class="font-label-md text-label-md text-secondary">S. No: <span class="font-body-md text-on-surface">CC-2023-045</span></p>
                        </div>
                        <div class="w-2/4 flex justify-center">
                            <img alt="School Seal" class="w-24 h-24" data-alt="A crisp, high-contrast institutional seal featuring a detailed crest, presented in dark navy blue on a pure white background. It conveys formal state authority." src="https://lh3.googleusercontent.com/aida-public/AB6AXuAUatG0y8uhmCwu5yoJuOar6ixpRo5GN6C6lSTE4Ysfknw-kbmO1tSk7kZtqpSThhXq6QpdalsZIGaPJ8Jw04VeG6gQZGEr15KYkOO_3hq_g3GJVcl6h_ZD0YWxIDrhVVON50vuMDsVsNcipi85UQ28RDmkvoNNIDEemsMUGuvxREY-8lfOjku20jAdA3O8PxAqJyugu-RT6ZqRUX0YFSc5vzm-n9O6gcJOkcMIlHQ9M2r4jGYtczd_JfkrBpLwW7QDwuDuEppu" />
                        </div>
                        <div class="text-right w-1/4">
                            <p class="font-label-md text-label-md text-secondary">Date: <span class="font-body-md text-on-surface">14 Nov 2023</span></p>
                        </div>
                    </div>
                    <h1 class="font-headline-xl text-headline-xl text-primary uppercase tracking-widest mt-2 mb-1">State Public School</h1>
                    <p class="font-body-md text-secondary">Affiliated to State Board of Secondary Education</p>
                    <p class="font-body-md text-secondary">District Headquarters, State 123456</p>
                </div>
                <!-- Certificate Title -->
                <div class="text-center mb-10">
                    <h2 class="inline-block border-b-2 border-primary font-headline-lg text-headline-lg text-on-surface pb-1 uppercase tracking-wider">Character Certificate</h2>
                </div>
                <!-- Certificate Body -->
                <div class="font-body-lg text-body-lg text-on-surface leading-loose max-w-3xl mx-auto space-y-6">
                    <p class="text-justify">
                        This is to certify that <span class="input-line font-semibold px-2">Arjun Sharma</span>,
                        Son/Daughter of <span class="input-line font-semibold px-2">Mr. Rajesh Sharma</span>
                        and <span class="input-line font-semibold px-2">Mrs. Meena Sharma</span>,
                        is/was a bonafide student of this institution.
                    </p>
                    <p class="text-justify">
                        He/She was admitted to the school on <span class="input-line px-2">05 Apr 2018</span>
                        under Admission Number <span class="input-line px-2">45092/18</span>.
                        He/She passed/is studying in Class <span class="input-line px-2">XII - Science</span>
                        during the academic session <span class="input-line px-2">2022-2023</span>.
                    </p>
                    <p class="text-justify">
                        To the best of my knowledge and belief, he/she bears an <span class="font-semibold text-primary">Exemplary</span> character.
                        During his/her stay in the school, his/her conduct towards teachers and fellow students has been highly satisfactory.
                        He/She has not taken part in any activity subversive of school discipline.
                    </p>
                    <p class="text-justify mt-4">
                        We wish him/her all success in his/her future endeavors.
                    </p>
                </div>
                <!-- Signatures -->
                <div class="mt-24 flex justify-between items-end px-8">
                    <div class="text-center">
                        <div class="signature-line mb-2"></div>
                        <p class="font-label-md text-label-md text-secondary">Class Teacher Signature</p>
                    </div>
                    <div class="text-center">
                        <div class="w-24 h-24 border-2 border-outline-variant rounded-full flex items-center justify-center mx-auto mb-4 opacity-50">
                            <span class="font-label-md text-label-md text-secondary text-center">School<br />Stamp</span>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="signature-line mb-2"></div>
                        <p class="font-label-md text-label-md text-secondary">Principal Signature</p>
                        <p class="font-body-md text-on-surface mt-1 text-sm">(Dr. Alok Nath)</p>
                    </div>
                </div>
            </div>
        </main>

<?php include 'includes/footer.php'; ?>
