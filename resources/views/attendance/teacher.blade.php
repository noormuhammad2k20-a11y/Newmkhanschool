@extends('layouts.app')

@section('title', 'Teacher Attendance & Leave')

@section('content')
<main class="flex-1 overflow-y-auto bg-surface-bright p-margin-desktop w-full">
    <div class="max-w-[1440px] mx-auto space-y-xl">
        <!-- Page Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-md">
            <div>
                <h1 class="text-headline-lg font-headline-lg font-semibold text-on-surface">Daily Attendance &amp; Leave Management</h1>
                <p class="font-body-md text-body-md text-on-surface-variant mt-xs">Overview for <span id="current-date">{{ date('l, F j, Y') }}</span></p>
            </div>
            <button class="inline-flex items-center gap-sm px-lg py-sm bg-primary text-on-primary font-label-md text-label-md rounded hover:opacity-90 transition-opacity whitespace-nowrap shadow-[0_4px_12px_rgba(26,35,126,0.08)]">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">description</span>
                Generate Monthly Duty Report
            </button>
        </div>
        
        <!-- Summary Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-md">
            <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Total Staff Present</p>
                    <h3 id="stat-present" class="font-headline-xl text-headline-xl text-on-surface">--</h3>
                    <p class="font-body-md text-body-md text-secondary mt-xs flex items-center gap-xs">
                        <span class="material-symbols-outlined text-surface-tint" style="font-size: 16px;">trending_up</span>
                        Today's Count
                    </p>
                </div>
                <div class="w-12 h-12 rounded-full bg-secondary-container flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined" style="font-size: 24px;">group</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg flex items-center justify-between relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-error-container rounded-bl-full opacity-20 -z-0"></div>
                <div class="relative z-10">
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Currently On Leave</p>
                    <h3 id="stat-leave" class="font-headline-xl text-headline-xl text-on-surface">--</h3>
                    <p class="font-body-md text-body-md text-secondary mt-xs">Approved leaves for today</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-error-container flex items-center justify-center text-error relative z-10">
                    <span class="material-symbols-outlined" style="font-size: 24px;">event_busy</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest border border-outline-variant rounded p-lg flex items-center justify-between">
                <div>
                    <p class="font-label-md text-label-md text-on-surface-variant uppercase tracking-wider mb-xs">Late Arrivals</p>
                    <h3 id="stat-late" class="font-headline-xl text-headline-xl text-on-surface">--</h3>
                    <p class="font-body-md text-body-md text-secondary mt-xs">Status = 'Late'</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-surface-container-highest flex items-center justify-center text-on-surface-variant">
                    <span class="material-symbols-outlined" style="font-size: 24px;">schedule</span>
                </div>
            </div>
        </div>
        
        <!-- Main Data Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-xl">
            <!-- Leave Requests Table (Span 8) -->
            <div class="lg:col-span-8 bg-surface-container-lowest border border-outline-variant rounded flex flex-col">
                <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-container-low">
                    <h3 class="font-headline-md text-headline-md text-on-surface">Pending Leave Requests</h3>
                    <button class="font-label-md text-label-md text-primary hover:underline">View All History</button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container border-b border-outline-variant">
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Teacher Name</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Leave Type</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Duration</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase">Status</th>
                                <th class="py-sm px-md font-label-md text-label-md text-on-surface-variant uppercase text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="leaves-tbody" class="font-body-md text-body-md text-on-surface">
                            <tr>
                                <td colspan="5" class="py-md px-md text-center text-on-surface-variant">Loading...</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Daily Attendance Log (Span 4) -->
            <div class="lg:col-span-4 bg-surface-container-lowest border border-outline-variant rounded flex flex-col h-full max-h-[500px]">
                <div class="p-md border-b border-outline-variant bg-surface-container-low flex justify-between items-center">
                    <h3 class="font-headline-md text-headline-md text-on-surface">Live Attendance Log</h3>
                    <span onclick="fetchDashboardData()" class="flex items-center justify-center w-6 h-6 rounded-full bg-secondary-container text-primary cursor-pointer hover:bg-primary-container hover:text-on-primary transition-colors" title="Refresh">
                        <span class="material-symbols-outlined" style="font-size: 16px;">refresh</span>
                    </span>
                </div>
                <div class="p-sm border-b border-outline-variant bg-surface-container-lowest">
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-2 top-1/2 -translate-y-1/2 text-outline" style="font-size: 18px;">search</span>
                        <input id="log-search" class="w-full pl-8 pr-3 py-1.5 text-body-md font-body-md border border-outline-variant rounded focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary bg-surface-bright text-on-surface placeholder:text-outline transition-all" placeholder="Search staff..." type="text" />
                    </div>
                </div>
                <div id="attendance-logs" class="flex-1 overflow-y-auto p-sm space-y-1">
                    <div class="p-sm text-center text-on-surface-variant">Loading...</div>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    let allLogs = [];

    document.addEventListener('DOMContentLoaded', function() {
        fetchDashboardData();

        document.getElementById('log-search').addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const filteredLogs = allLogs.filter(log => log.teacher_name.toLowerCase().includes(term) || log.department.toLowerCase().includes(term));
            renderLogs(filteredLogs);
        });
    });

    function fetchDashboardData() {
        fetch('/api/teacher-attendance/dashboard')
            .then(res => res.json())
            .then(response => {
                if(response.status === 'success') {
                    const data = response.data;
                    
                    // Update stats
                    document.getElementById('stat-present').innerText = data.stats.present;
                    document.getElementById('stat-leave').innerText = data.stats.on_leave;
                    document.getElementById('stat-late').innerText = data.stats.late;

                    // Update Leaves table
                    renderLeaves(data.pending_leaves);

                    // Update Logs
                    allLogs = data.logs;
                    const searchTerm = document.getElementById('log-search').value.toLowerCase();
                    const filteredLogs = allLogs.filter(log => log.teacher_name.toLowerCase().includes(searchTerm) || log.department.toLowerCase().includes(searchTerm));
                    renderLogs(filteredLogs);
                }
            })
            .catch(error => console.error("Error fetching dashboard data:", error));
    }

    function renderLeaves(leaves) {
        const tbody = document.getElementById('leaves-tbody');
        if (leaves.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="py-md px-md text-center text-on-surface-variant">No pending leave requests.</td></tr>`;
            return;
        }

        let html = '';
        leaves.forEach(leave => {
            html += `
                <tr class="border-b border-surface-variant hover:bg-surface-bright transition-colors">
                    <td class="py-md px-md font-semibold">${leave.teacher_name}</td>
                    <td class="py-md px-md">${leave.leave_type}</td>
                    <td class="py-md px-md">${leave.duration}</td>
                    <td class="py-md px-md">
                        <span class="inline-flex items-center px-2 py-1 rounded-full bg-surface-container-highest text-on-surface-variant font-label-md text-[10px] uppercase">${leave.status}</span>
                    </td>
                    <td class="py-md px-md text-right space-x-2">
                        <button onclick="updateLeave(${leave.id}, 'Approved')" class="px-3 py-1 bg-secondary-container text-primary font-label-md text-label-md rounded hover:bg-primary-container hover:text-on-primary transition-colors">Approve</button>
                        <button onclick="updateLeave(${leave.id}, 'Rejected')" class="px-3 py-1 border border-outline-variant text-on-surface-variant font-label-md text-label-md rounded hover:bg-error-container hover:text-error hover:border-error transition-colors">Reject</button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function renderLogs(logs) {
        const logsContainer = document.getElementById('attendance-logs');
        if (logs.length === 0) {
            logsContainer.innerHTML = `<div class="p-sm text-center text-on-surface-variant">No logs found.</div>`;
            return;
        }

        let html = '';
        logs.forEach(log => {
            const names = log.teacher_name.split(' ');
            const initials = names[0][0] + (names.length > 1 ? names[1][0] : '');
            
            let bgClass = 'bg-secondary-container text-primary';
            let statusIcon = 'login';
            let timeColor = 'text-primary';
            let containerClass = 'hover:bg-surface-container-low';

            if (log.status === 'L') {
                bgClass = 'bg-error-container text-error';
                statusIcon = 'warning';
                timeColor = 'text-error';
                containerClass += ' bg-error-container/20';
            } else if (log.status === 'A') {
                bgClass = 'bg-surface-container-highest text-on-surface-variant';
                statusIcon = 'cancel';
                timeColor = 'text-secondary';
                containerClass += ' opacity-75';
            }

            html += `
                <div class="flex items-center justify-between p-sm rounded transition-colors ${containerClass}">
                    <div class="flex items-center gap-sm">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-label-md text-label-md uppercase ${bgClass}">${initials}</div>
                        <div>
                            <p class="font-body-md text-body-md font-semibold text-on-surface leading-tight">${log.teacher_name}</p>
                            <p class="font-label-md text-label-md text-secondary">${log.department}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-label-md text-label-md flex items-center gap-1 justify-end ${timeColor}">
                            <span class="material-symbols-outlined" style="font-size: 14px;">${statusIcon}</span> ${log.time}
                        </p>
                    </div>
                </div>
            `;
        });
        logsContainer.innerHTML = html;
    }

    async function updateLeave(id, status) {
        const confirmStyle = status === 'Approved' ? 'primary' : 'error';
        const isConfirmed = await window.UI.confirm('Confirm Action', `Are you sure you want to mark this leave as ${status}?`, 'Confirm', confirmStyle);
        if (!isConfirmed) return;

        fetch(`/api/teacher-attendance/leaves/${id}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ status: status })
        })
        .then(res => res.json())
        .then(response => {
            if (response.status === 'success') {
                fetchDashboardData();
                window.UI.showToast(`Leave marked as ${status}`, 'success');
            } else {
                window.UI.showToast(response.message || 'Error updating status', 'error');
            }
        })
        .catch(error => {
            console.error('Error updating leave status:', error);
            window.UI.showToast('An error occurred. Check console for details.', 'error');
        });
    }
</script>
@endsection
