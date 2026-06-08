<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
        <!-- Main Canvas -->
        <main class="flex-1 overflow-auto bg-background p-margin-mobile md:p-margin-desktop">
            <div class="max-w-[max-width] mx-auto">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-xl gap-md">
                    <div>
                        <h2 class="text-headline-lg font-headline-lg text-on-background">Student Directory</h2>
                        <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Manage and view all enrolled student records across the district.</p>
                    </div>
                    <button class="bg-primary text-on-primary text-label-md font-label-md py-sm px-md rounded-DEFAULT flex items-center justify-center gap-sm hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap self-start sm:self-auto">
                        <span class="material-symbols-outlined">person_add</span>
                        Add New Student
                    </button>
                </div>
                <!-- Filters & Search Bar -->
                <div class="bg-surface border border-outline-variant rounded-lg p-md mb-lg">
                    <form id="filter-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-md">
                        <div class="lg:col-span-2">
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Search</label>
                            <div class="flex items-center bg-surface-container-lowest rounded-DEFAULT px-md py-sm border border-outline-variant focus-within:border-primary focus-within:border-[2px] transition-all">
                                <span class="material-symbols-outlined text-on-surface-variant mr-sm">search</span>
                                <input id="filter-search" class="bg-transparent border-none focus:ring-0 text-body-md font-body-md w-full text-on-surface p-0" placeholder="Search by Name, B-Form, or Admission No." type="text" />
                            </div>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Class</label>
                            <select id="filter-class" class="w-full bg-surface-container-lowest rounded-DEFAULT px-md py-sm border border-outline-variant focus:border-primary focus:ring-0 text-body-md font-body-md text-on-surface appearance-none cursor-pointer">
                                <option value="">All Classes</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Section</label>
                            <select id="filter-section" class="w-full bg-surface-container-lowest rounded-DEFAULT px-md py-sm border border-outline-variant focus:border-primary focus:ring-0 text-body-md font-body-md text-on-surface appearance-none cursor-pointer">
                                <option value="">All Sections</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-label-md font-label-md text-on-surface-variant mb-xs">Status</label>
                            <select id="filter-status" class="w-full bg-surface-container-lowest rounded-DEFAULT px-md py-sm border border-outline-variant focus:border-primary focus:ring-0 text-body-md font-body-md text-on-surface appearance-none cursor-pointer">
                                <option value="">All Statuses</option>
                                <option value="Regular">Regular</option>
                                <option value="Transferred">Transferred</option>
                                <option value="Graduated">Graduated</option>
                            </select>
                        </div>
                    </form>
                </div>
                <!-- Data Table Card -->
                <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-surface-container border-b border-outline-variant">
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold">Adm No.</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold">Student Name</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold">Father's Name</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold">Class/Sec</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold">B-Form / CNIC</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold">Status</th>
                                    <th class="py-sm px-md text-label-md font-label-md text-on-surface-variant font-semibold text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="students-tbody" class="text-body-md font-body-md">
                                <tr><td colspan="7" class="py-8 text-center text-secondary">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('students-tbody');
        const filterForm = document.getElementById('filter-form');
        const searchInput = document.getElementById('filter-search');
        const classSelect = document.getElementById('filter-class');
        const sectionSelect = document.getElementById('filter-section');
        const statusSelect = document.getElementById('filter-status');

        function fetchStudents() {
            const params = new URLSearchParams({
                search: searchInput.value,
                class_id: classSelect.value,
                section_id: sectionSelect.value,
                status: statusSelect.value
            });
            
            fetch(`api/students.php?${params}`)
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        renderStudents(response.data);
                    }
                });
        }

        function renderStudents(students) {
            if (students.length === 0) {
                tbody.innerHTML = `<tr><td colspan="7" class="py-8 text-center text-secondary">No students found.</td></tr>`;
                return;
            }

            let html = '';
            students.forEach((student, index) => {
                const bgClass = index % 2 === 0 ? 'bg-surface' : 'bg-[#e3f2fd] bg-opacity-30';
                const initials = student.first_name[0] + (student.last_name[0] || '');
                let statusBadge = '';
                
                if (student.status === 'Regular') {
                    statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-[#e8f5e9] text-[#2e7d32] border border-[#c8e6c9]">Regular</span>`;
                } else if (student.status === 'Transferred') {
                    statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-[#fff3e0] text-[#ef6c00] border border-[#ffe0b2]">Transferred</span>`;
                } else {
                    statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold bg-surface-variant text-on-surface-variant border border-outline-variant">${student.status}</span>`;
                }

                html += `
                <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors ${bgClass}">
                    <td class="py-sm px-md text-secondary">${student.admission_no}</td>
                    <td class="py-sm px-md font-medium text-on-background flex items-center gap-sm">
                        <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-label-md font-bold uppercase">${initials}</div>
                        ${student.first_name} ${student.last_name}
                    </td>
                    <td class="py-sm px-md text-on-surface-variant">${student.father_name || '-'}</td>
                    <td class="py-sm px-md text-on-surface-variant">${student.class_name || '-'} / ${student.section_name || '-'}</td>
                    <td class="py-sm px-md text-on-surface-variant font-mono text-sm">${student.b_form_number || '-'}</td>
                    <td class="py-sm px-md">${statusBadge}</td>
                    <td class="py-sm px-md text-right">
                        <div class="flex items-center justify-end gap-sm">
                            <button class="text-primary hover:bg-primary-fixed p-xs rounded transition-colors" title="View Profile"><span class="material-symbols-outlined text-[20px]">visibility</span></button>
                            <button class="text-secondary hover:bg-surface-container-high p-xs rounded transition-colors" title="Edit Record"><span class="material-symbols-outlined text-[20px]">edit</span></button>
                            <button class="text-secondary hover:bg-surface-container-high p-xs rounded transition-colors" title="Transfer Out"><span class="material-symbols-outlined text-[20px]">directions_run</span></button>
                        </div>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        // Event listeners for filters
        searchInput.addEventListener('input', fetchStudents);
        classSelect.addEventListener('change', fetchStudents);
        sectionSelect.addEventListener('change', fetchStudents);
        statusSelect.addEventListener('change', fetchStudents);

        // Initial fetch
        fetchStudents();
    });
</script>
<?php include 'includes/footer.php'; ?>
