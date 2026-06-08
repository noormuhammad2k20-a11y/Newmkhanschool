<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
        <!-- Main Canvas -->
        <main class="flex-1 overflow-y-auto p-margin-mobile md:p-margin-desktop max-w-[1440px] mx-auto w-full">
            <!-- Page Header -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-lg gap-md">
                <div>
                    <h2 class="text-headline-lg font-headline-lg text-on-surface">Teacher Directory</h2>
                    <p class="text-body-md font-body-md text-secondary mt-1">Manage and view all registered educators in the district.</p>
                </div>
                <div class="flex items-center gap-md">
                    <button class="bg-surface-container-lowest border border-outline-variant text-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-surface-container-low transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]" data-icon="download">download</span>
                        Export
                    </button>
                    <button class="bg-primary text-on-primary px-4 py-2 rounded-lg font-label-md text-label-md hover:bg-on-primary-fixed-variant transition-colors flex items-center gap-2 shadow-sm">
                        <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
                        Add New Teacher
                    </button>
                </div>
            </div>
            <!-- Filters & Search Bar -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md mb-lg">
                <form id="filter-form" class="grid grid-cols-1 md:grid-cols-4 gap-md items-end">
                    <div class="relative w-full col-span-1">
                        <label class="block text-label-md font-label-md text-secondary mb-1">Search</label>
                        <span class="material-symbols-outlined absolute left-sm top-9 -translate-y-1/2 text-secondary" data-icon="search">search</span>
                        <input id="filter-search" class="w-full bg-surface border border-outline-variant rounded-md py-2 pl-10 pr-4 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary" placeholder="Search..." type="text" />
                    </div>
                    <div>
                        <label class="block text-label-md font-label-md text-secondary mb-1">Department</label>
                        <select class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface">
                            <option value="">All Departments</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-label-md font-label-md text-secondary mb-1">Status</label>
                        <select class="w-full bg-surface border border-outline-variant rounded-md py-2 px-3 text-body-md font-body-md focus:border-primary focus:ring-1 focus:ring-primary text-on-surface">
                            <option value="">All Statuses</option>
                            <option>Active</option>
                            <option>On Leave</option>
                            <option>Retired</option>
                        </select>
                    </div>
                    <div class="flex items-center justify-end">
                        <button type="button" class="text-primary font-label-md text-label-md hover:underline flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]" data-icon="filter_list">filter_list</span>
                            More Filters
                        </button>
                    </div>
                </form>
            </div>
            <!-- Data Table -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-lg overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-outline-variant">
                                <th class="py-3 px-4 text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Employee ID</th>
                                <th class="py-3 px-4 text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Teacher Name</th>
                                <th class="py-3 px-4 text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Department / Specialization</th>
                                <th class="py-3 px-4 text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Contact Info</th>
                                <th class="py-3 px-4 text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider">Status</th>
                                <th class="py-3 px-4 text-label-md font-label-md text-secondary font-semibold uppercase tracking-wider text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="teachers-tbody" class="divide-y divide-outline-variant">
                            <tr><td colspan="6" class="py-8 text-center text-secondary">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tbody = document.getElementById('teachers-tbody');
        const searchInput = document.getElementById('filter-search');

        function fetchTeachers() {
            const params = new URLSearchParams({
                search: searchInput.value
            });
            
            fetch(`api/teachers.php?${params}`)
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        renderTeachers(response.data);
                    }
                });
        }

        function renderTeachers(teachers) {
            if (teachers.length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="py-8 text-center text-secondary">No teachers found.</td></tr>`;
                return;
            }

            let html = '';
            teachers.forEach((teacher, index) => {
                const bgClass = index % 2 === 0 ? '' : 'bg-surface';
                const names = teacher.full_name.split(' ');
                const initials = (names[0][0] + (names[1] ? names[1][0] : '')).toUpperCase();
                
                // Static status for now since it's not in the DB schema explicitly (assumed Active)
                const statusBadge = `<span class="inline-flex items-center px-2 py-1 rounded-full text-[11px] font-bold bg-[#e8f5e9] text-[#2e7d32]">Active</span>`;

                html += `
                <tr class="${bgClass} hover:bg-surface-container-low transition-colors group">
                    <td class="py-3 px-4 text-body-md font-body-md font-medium text-on-surface">${teacher.employee_number || '-'}</td>
                    <td class="py-3 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary-fixed flex items-center justify-center text-on-primary-fixed font-bold text-label-md">${initials}</div>
                            <div>
                                <p class="text-body-md font-body-md font-medium text-on-surface">${teacher.full_name}</p>
                                <p class="text-label-md font-label-md text-secondary">${teacher.qualification || '-'}</p>
                            </div>
                        </div>
                    </td>
                    <td class="py-3 px-4">
                        <p class="text-body-md font-body-md text-on-surface">${teacher.specialization || '-'}</p>
                        <p class="text-label-md font-label-md text-secondary">Experience: ${teacher.experience || 0} yrs</p>
                    </td>
                    <td class="py-3 px-4">
                        <p class="text-body-md font-body-md text-on-surface">${teacher.email || '-'}</p>
                        <p class="text-label-md font-label-md text-secondary">${teacher.mobile || '-'}</p>
                    </td>
                    <td class="py-3 px-4">${statusBadge}</td>
                    <td class="py-3 px-4 text-right">
                        <button class="text-secondary hover:text-primary transition-colors p-1" title="View Profile"><span class="material-symbols-outlined text-[20px]" data-icon="visibility">visibility</span></button>
                        <button class="text-secondary hover:text-primary transition-colors p-1" title="Edit"><span class="material-symbols-outlined text-[20px]" data-icon="edit">edit</span></button>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        searchInput.addEventListener('input', fetchTeachers);
        fetchTeachers();
    });
</script>

<?php include 'includes/footer.php'; ?>
