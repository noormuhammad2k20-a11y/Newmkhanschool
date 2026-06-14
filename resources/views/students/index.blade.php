@extends('layouts.app')

@section('title', 'Student Directory')

@section('content')
        <main class="flex-1 overflow-auto bg-background p-margin-mobile md:p-margin-desktop">
            <div class="max-w-[max-width] mx-auto">
                <!-- Page Header -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-xl gap-md">
                    <div>
                        <h2 class="text-headline-lg font-headline-lg text-on-background">Student Directory</h2>
                        <p class="text-body-md font-body-md text-on-surface-variant mt-xs">Manage and view all enrolled student records across the district.</p>
                    </div>
                    <a href="{{ route('admin.students.create') }}" class="bg-primary text-on-primary text-label-md font-label-md py-sm px-md rounded-DEFAULT flex items-center justify-center gap-sm hover:bg-primary-container transition-colors shadow-sm whitespace-nowrap self-start sm:self-auto">
                        <span class="material-symbols-outlined">person_add</span>
                        Add New Student
                    </a>
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
                        <div class="flex items-center mt-6">
                            <label class="flex items-center gap-sm cursor-pointer group">
                                <div class="relative flex items-center justify-center w-5 h-5">
                                    <input type="checkbox" id="filter-tuition" value="1" class="peer appearance-none w-5 h-5 border-2 border-outline rounded-[2px] checked:bg-primary checked:border-primary transition-colors focus:ring-2 focus:ring-primary focus:ring-offset-2">
                                    <span class="material-symbols-outlined absolute text-on-primary text-[16px] pointer-events-none opacity-0 peer-checked:opacity-100 transition-opacity">check</span>
                                </div>
                                <span class="text-label-md font-label-md text-on-surface group-hover:text-primary transition-colors">Tuition Students Only</span>
                            </label>
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
        const tuitionCheckbox = document.getElementById('filter-tuition');

        function fetchStudents() {
            const params = new URLSearchParams({
                search: searchInput.value,
                class_id: classSelect.value,
                section_id: sectionSelect.value,
                status: statusSelect.value,
                is_tuition: tuitionCheckbox.checked ? '1' : ''
            });
            
            fetch(`/api/students?${params}`)
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

                if (student.is_tuition) {
                    statusBadge += ` <span class="inline-flex items-center px-2 py-1 ml-1 rounded-full text-[10px] font-bold bg-primary-container text-on-primary-container border border-primary">Tuition</span>`;
                }

                html += `
                <tr class="border-b border-outline-variant hover:bg-surface-container-lowest transition-colors ${bgClass}">
                    <td class="py-sm px-md text-secondary">${student.admission_no}</td>
                    <td class="py-sm px-md font-medium text-on-background flex items-center gap-sm">
                        <div class="w-8 h-8 rounded-full bg-primary-container text-on-primary-container flex items-center justify-center text-label-md font-bold uppercase">${initials}</div>${student.first_name} ${student.last_name}
                    </td>
                    <td class="py-sm px-md text-on-surface-variant">${student.father_name || '-'}</td>
                    <td class="py-sm px-md text-on-surface-variant">${student.class_name || '-'} / ${student.section_name || '-'}</td>
                    <td class="py-sm px-md text-on-surface-variant font-mono text-sm">${student.b_form_number || '-'}</td>
                    <td class="py-sm px-md">${statusBadge}</td>
                    <td class="py-sm px-md text-right">
                        <div class="flex items-center justify-end gap-sm">
                            <a href="/admin/students/${student.id}" class="text-primary hover:bg-primary-fixed p-xs rounded transition-colors" title="View Profile"><span class="material-symbols-outlined text-[20px]">visibility</span></a>
                            <a href="/admin/students/${student.id}/edit" class="text-secondary hover:bg-surface-container-high p-xs rounded transition-colors" title="Edit Record"><span class="material-symbols-outlined text-[20px]">edit</span></a>
                            <button onclick="removeStudent(${student.id})" class="text-secondary hover:bg-surface-container-high p-xs rounded transition-colors" title="Remove"><span class="material-symbols-outlined text-[20px]">directions_run</span></button>
                        </div>
                    </td>
                </tr>`;
            });
            tbody.innerHTML = html;
        }

        window.removeStudent = async function(id) {
            const isConfirmed = await window.UI.confirm('Confirm Action', 'Are you sure you want to remove this student?', 'Confirm', 'error');
            if (isConfirmed) {
                fetch(`/api/students/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                .then(res => res.json())
                .then(response => {
                    if (response.status === 'success') {
                        window.UI.showToast('Student removed successfully', 'success');
                        fetchStudents(); // Refresh the list
                    } else {
                        window.UI.showToast('Error removing student', 'error');
                    }
                });
            }
        };

        searchInput.addEventListener('input', fetchStudents);
        classSelect.addEventListener('change', fetchStudents);
        sectionSelect.addEventListener('change', fetchStudents);
        statusSelect.addEventListener('change', fetchStudents);
        tuitionCheckbox.addEventListener('change', fetchStudents);

        fetchStudents();
    });
</script>
@endsection
