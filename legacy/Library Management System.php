<?php
include 'includes/header.php';
include 'includes/sidebar.php';
include 'includes/topbar.php';
?>
        <!-- Page Canvas -->
        <div class="p-margin-mobile md:p-margin-desktop flex-1 max-w-[max-width] mx-auto w-full">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-xl gap-md">
                <div>
                    <h2 class="font-headline-xl text-headline-xl text-on-surface">Library Overview</h2>
                    <p class="font-body-md text-body-md text-secondary mt-xs">Manage catalog, track issues, and monitor inventory.</p>
                </div>
                <div class="flex gap-sm w-full sm:w-auto">
                    <button class="flex-1 sm:flex-none bg-surface-bright text-primary border border-primary px-md py-sm rounded-DEFAULT font-label-md text-label-md hover:bg-secondary-container transition-colors flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">keyboard_return</span>
                        Return Book
                    </button>
                    <button class="flex-1 sm:flex-none bg-primary text-on-primary px-md py-sm rounded-DEFAULT font-label-md text-label-md hover:bg-primary-container transition-colors flex items-center justify-center gap-xs">
                        <span class="material-symbols-outlined text-[18px]">library_add</span>
                        Issue Book
                    </button>
                </div>
            </div>
            <!-- Dashboard KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-md mb-xl">
                <!-- Total Books -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-[120px]">
                    <div class="flex justify-between items-start">
                        <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Total Collection</span>
                        <div class="bg-secondary-container p-xs rounded-full text-primary">
                            <span class="material-symbols-outlined">menu_book</span>
                        </div>
                    </div>
                    <div>
                        <div id="stat-total-books" class="font-headline-xl text-headline-xl text-on-surface">-</div>
                        <div class="font-label-md text-label-md text-secondary mt-xs">+124 this month</div>
                    </div>
                </div>
                <!-- Books Issued -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg p-md flex flex-col justify-between h-[120px]">
                    <div class="flex justify-between items-start">
                        <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Currently Issued</span>
                        <div class="bg-[#e0f2f1] p-xs rounded-full text-[#00695c]">
                            <span class="material-symbols-outlined">how_to_vote</span>
                        </div>
                    </div>
                    <div>
                        <div id="stat-issued" class="font-headline-xl text-headline-xl text-on-surface">-</div>
                        <div class="font-label-md text-label-md text-secondary mt-xs">Active issues</div>
                    </div>
                </div>
                <!-- Overdue Returns -->
                <div class="bg-surface-container-lowest border border-error-container rounded-lg p-md flex flex-col justify-between h-[120px] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-16 h-16 bg-error opacity-5 rounded-bl-full"></div>
                    <div class="flex justify-between items-start relative z-10">
                        <span class="font-label-md text-label-md text-secondary uppercase tracking-wider">Overdue Returns</span>
                        <div class="bg-error-container p-xs rounded-full text-error">
                            <span class="material-symbols-outlined">warning</span>
                        </div>
                    </div>
                    <div class="relative z-10">
                        <div id="stat-overdue" class="font-headline-xl text-headline-xl text-error">-</div>
                        <div class="font-label-md text-label-md text-secondary mt-xs">Requires immediate attention</div>
                    </div>
                </div>
            </div>
            <!-- Main Content Layout (Bento-style Grid) -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-md">
                <!-- Catalog Section (Takes up 2 columns) -->
                <div class="lg:col-span-2 bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col h-[500px]">
                    <div class="p-md border-b border-outline-variant flex justify-between items-center bg-surface-bright rounded-t-lg">
                        <h3 class="font-headline-md text-headline-md text-on-surface">Quick Catalog Search</h3>
                        <div class="relative max-w-[200px] sm:max-w-xs w-full">
                            <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline text-[18px]">search</span>
                            <input class="w-full pl-xl pr-sm py-[6px] bg-surface-container-lowest border border-outline-variant rounded-DEFAULT focus:border-primary focus:border-2 focus:ring-0 font-body-md text-body-md text-on-surface transition-colors placeholder:text-outline text-sm" placeholder="Search Title, Author, ISBN..." type="text" />
                        </div>
                    </div>
                    <div class="flex-1 overflow-auto p-md">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="border-b border-outline-variant text-secondary">
                                    <th class="pb-sm font-label-md text-label-md font-semibold w-2/5">Book Title</th>
                                    <th class="pb-sm font-label-md text-label-md font-semibold w-1/4">Author</th>
                                    <th class="pb-sm font-label-md text-label-md font-semibold hidden sm:table-cell">ISBN</th>
                                    <th class="pb-sm font-label-md text-label-md font-semibold text-right">Status</th>
                                </tr>
                            </thead>
                            <tbody id="books-tbody" class="font-body-md text-body-md">
                                <tr><td colspan="4" class="py-4 text-center text-secondary">Loading...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Recent Transactions (Takes 1 column) -->
                <div class="bg-surface-container-lowest border border-outline-variant rounded-lg flex flex-col h-[500px]">
                    <div class="p-md border-b border-outline-variant bg-surface-bright rounded-t-lg">
                        <h3 class="font-headline-md text-headline-md text-on-surface">Recent Transactions</h3>
                    </div>
                    <div id="transactions-container" class="flex-1 overflow-auto p-md flex flex-col gap-sm">
                        <div class="text-center text-secondary py-4">Loading transactions...</div>
                    </div>
                    <div class="p-sm border-t border-outline-variant text-center bg-surface-bright rounded-b-lg">
                        <button class="font-label-md text-label-md text-primary hover:underline">View All Transactions</button>
                    </div>
                </div>
            </div>
        </div>
    </main>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const booksTbody = document.getElementById('books-tbody');
        const transactionsContainer = document.getElementById('transactions-container');

        fetch(`api/library.php`)
            .then(res => res.json())
            .then(response => {
                if (response.status === 'success') {
                    renderLibrary(response.data);
                }
            });

        function renderLibrary(data) {
            // Update stats
            document.getElementById('stat-total-books').textContent = data.stats.total_books.toLocaleString();
            document.getElementById('stat-issued').textContent = data.stats.issued.toLocaleString();
            document.getElementById('stat-overdue').textContent = data.stats.overdue.toLocaleString();

            // Render books
            let booksHtml = '';
            data.books.forEach((book, index) => {
                const bgClass = index % 2 !== 0 ? 'bg-surface-bright' : '';
                let statusBadge = '';
                if (book.status === 'Available') {
                    statusBadge = `<span class="inline-block px-sm py-[2px] rounded-full bg-[#e8f5e9] text-[#2e7d32] font-label-md text-[10px]">Available</span>`;
                } else {
                    statusBadge = `<span class="inline-block px-sm py-[2px] rounded-full bg-error-container text-error font-label-md text-[10px]">Checked Out</span>`;
                }

                booksHtml += `
                <tr class="${bgClass} border-b border-outline-variant hover:bg-surface-container-low transition-colors">
                    <td class="py-sm pr-sm">
                        <div class="font-medium text-on-surface">${book.title}</div>
                        <div class="text-secondary text-xs mt-[2px] hidden sm:block">${book.category}</div>
                    </td>
                    <td class="py-sm text-on-surface-variant">${book.author}</td>
                    <td class="py-sm text-secondary hidden sm:table-cell">${book.isbn}</td>
                    <td class="py-sm text-right">${statusBadge}</td>
                </tr>`;
            });
            booksTbody.innerHTML = booksHtml;

            // Render transactions
            let txHtml = '';
            data.transactions.forEach(tx => {
                if (tx.type === 'issue') {
                    txHtml += `
                    <div class="p-sm border border-outline-variant rounded-lg bg-surface-container-lowest flex items-start gap-sm">
                        <div class="bg-secondary-container p-[6px] rounded-full text-primary mt-xs">
                            <span class="material-symbols-outlined text-[16px]">library_add</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="font-label-md text-label-md text-on-surface truncate">${tx.student}</p>
                                <span class="font-label-md text-[10px] text-secondary whitespace-nowrap">${tx.time}</span>
                            </div>
                            <p class="font-body-md text-[13px] text-on-surface-variant truncate mt-[2px]">Issued: ${tx.book}</p>
                            <p class="font-label-md text-[11px] text-secondary mt-xs">Due: ${tx.due}</p>
                        </div>
                    </div>`;
                } else if (tx.type === 'return') {
                    txHtml += `
                    <div class="p-sm border border-outline-variant rounded-lg bg-surface-container-lowest flex items-start gap-sm">
                        <div class="bg-surface-bright border border-outline-variant p-[6px] rounded-full text-secondary mt-xs">
                            <span class="material-symbols-outlined text-[16px]">keyboard_return</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="font-label-md text-label-md text-on-surface truncate">${tx.student}</p>
                                <span class="font-label-md text-[10px] text-secondary whitespace-nowrap">${tx.time}</span>
                            </div>
                            <p class="font-body-md text-[13px] text-on-surface-variant truncate mt-[2px]">Returned: ${tx.book}</p>
                            <p class="font-label-md text-[11px] text-[#2e7d32] mt-xs">${tx.due}</p>
                        </div>
                    </div>`;
                } else if (tx.type === 'overdue') {
                    txHtml += `
                    <div class="p-sm border border-error-container rounded-lg bg-[#fff8f6] flex items-start gap-sm">
                        <div class="bg-error-container p-[6px] rounded-full text-error mt-xs">
                            <span class="material-symbols-outlined text-[16px]">warning</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex justify-between items-start">
                                <p class="font-label-md text-label-md text-error truncate">${tx.student}</p>
                                <span class="font-label-md text-[10px] text-error whitespace-nowrap">${tx.time}</span>
                            </div>
                            <p class="font-body-md text-[13px] text-on-surface-variant truncate mt-[2px]">${tx.book}</p>
                            <p class="font-label-md text-[11px] text-error mt-xs">Due: ${tx.due}</p>
                        </div>
                    </div>`;
                }
            });
            transactionsContainer.innerHTML = txHtml;
        }
    });
</script>

<?php include 'includes/footer.php'; ?>
