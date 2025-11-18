/**
 * Search & Filter Functionality for Admin Panel
 * Adds real-time search and filtering to tables and cards
 */

(function () {
    'use strict';

    // ========== TABLE SEARCH ==========
    function initTableSearch() {
        // Create search input for tables
        const tables = document.querySelectorAll('.table');

        tables.forEach(table => {
            const tableParent = table.closest('.card') || table.parentElement;
            const cardHeader = tableParent.querySelector('.card-header');

            if (cardHeader && !cardHeader.querySelector('.table-search')) {
                // Create search input
                const searchDiv = document.createElement('div');
                searchDiv.className = 'table-search float-end';
                searchDiv.innerHTML = `
                    <input type="text" 
                           class="form-control form-control-sm" 
                           placeholder="Search..." 
                           style="width: 200px; display: inline-block;">
                `;

                cardHeader.appendChild(searchDiv);

                const searchInput = searchDiv.querySelector('input');

                // Search functionality
                searchInput.addEventListener('keyup', function () {
                    const searchTerm = this.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    }

    // ========== CARD SEARCH ==========
    function initCardSearch() {
        // Add search for card-based layouts (team, gallery, etc.)
        const cardContainers = document.querySelectorAll('.row');

        cardContainers.forEach(container => {
            const cards = container.querySelectorAll('.card');

            if (cards.length > 3) { // Only add search if more than 3 cards
                const mainHeader = container.previousElementSibling;

                if (mainHeader && mainHeader.classList.contains('border-bottom') && !mainHeader.querySelector('.card-search')) {
                    const searchDiv = document.createElement('div');
                    searchDiv.className = 'card-search';
                    searchDiv.innerHTML = `
                        <input type="text" 
                               class="form-control" 
                               placeholder="Search items..." 
                               style="max-width: 300px;">
                    `;

                    mainHeader.appendChild(searchDiv);

                    const searchInput = searchDiv.querySelector('input');

                    searchInput.addEventListener('keyup', function () {
                        const searchTerm = this.value.toLowerCase();

                        cards.forEach(card => {
                            const text = card.textContent.toLowerCase();
                            const col = card.closest('[class*="col-"]');

                            if (text.includes(searchTerm)) {
                                if (col) col.style.display = '';
                            } else {
                                if (col) col.style.display = 'none';
                            }
                        });
                    });
                }
            }
        });
    }

    // ========== STATUS FILTER ==========
    function initStatusFilter() {
        const tables = document.querySelectorAll('.table');

        tables.forEach(table => {
            const tableParent = table.closest('.card') || table.parentElement;
            const cardHeader = tableParent.querySelector('.card-header');

            if (cardHeader && !cardHeader.querySelector('.status-filter')) {
                const filterDiv = document.createElement('div');
                filterDiv.className = 'status-filter float-end me-2';
                filterDiv.innerHTML = `
                    <select class="form-select form-select-sm" style="width: 150px; display: inline-block;">
                        <option value="all">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="published">Published</option>
                        <option value="draft">Draft</option>
                        <option value="pending">Pending</option>
                    </select>
                `;

                cardHeader.insertBefore(filterDiv, cardHeader.firstChild.nextSibling);

                const filterSelect = filterDiv.querySelector('select');

                filterSelect.addEventListener('change', function () {
                    const filterValue = this.value.toLowerCase();
                    const rows = table.querySelectorAll('tbody tr');

                    rows.forEach(row => {
                        if (filterValue === 'all') {
                            row.style.display = '';
                        } else {
                            const statusBadge = row.querySelector('.badge');
                            if (statusBadge) {
                                const status = statusBadge.textContent.toLowerCase();
                                if (status.includes(filterValue)) {
                                    row.style.display = '';
                                } else {
                                    row.style.display = 'none';
                                }
                            }
                        }
                    });
                });
            }
        });
    }

    // ========== BULK ACTIONS ==========
    function initBulkActions() {
        const tables = document.querySelectorAll('.table');

        tables.forEach(table => {
            const tbody = table.querySelector('tbody');
            if (!tbody) return;

            const rows = tbody.querySelectorAll('tr');
            if (rows.length === 0) return;

            // Add checkboxes to each row
            rows.forEach(row => {
                if (!row.querySelector('.bulk-checkbox')) {
                    const firstCell = row.querySelector('td');
                    if (firstCell) {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.className = 'bulk-checkbox form-check-input me-2';
                        firstCell.insertBefore(checkbox, firstCell.firstChild);
                    }
                }
            });

            // Add select all checkbox to header
            const thead = table.querySelector('thead');
            if (thead) {
                const firstHeader = thead.querySelector('th');
                if (firstHeader && !firstHeader.querySelector('.bulk-select-all')) {
                    const selectAll = document.createElement('input');
                    selectAll.type = 'checkbox';
                    selectAll.className = 'bulk-select-all form-check-input me-2';
                    firstHeader.insertBefore(selectAll, firstHeader.firstChild);

                    // Select all functionality
                    selectAll.addEventListener('change', function () {
                        const checkboxes = tbody.querySelectorAll('.bulk-checkbox');
                        checkboxes.forEach(cb => cb.checked = this.checked);
                        updateBulkActions();
                    });
                }
            }

            // Add bulk action bar
            const tableParent = table.closest('.card') || table.parentElement;
            if (tableParent && !tableParent.querySelector('.bulk-actions-bar')) {
                const bulkBar = document.createElement('div');
                bulkBar.className = 'bulk-actions-bar alert alert-info d-none';
                bulkBar.innerHTML = `
                    <span class="selected-count">0 items selected</span>
                    <button class="btn btn-sm btn-danger ms-3" onclick="bulkDelete()">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                    <button class="btn btn-sm btn-success ms-2" onclick="bulkActivate()">
                        <i class="fas fa-check"></i> Activate
                    </button>
                    <button class="btn btn-sm btn-secondary ms-2" onclick="bulkDeactivate()">
                        <i class="fas fa-times"></i> Deactivate
                    </button>
                `;

                table.parentElement.insertBefore(bulkBar, table);
            }

            // Update bulk actions on checkbox change
            tbody.addEventListener('change', function (e) {
                if (e.target.classList.contains('bulk-checkbox')) {
                    updateBulkActions();
                }
            });
        });
    }

    function updateBulkActions() {
        const checkboxes = document.querySelectorAll('.bulk-checkbox:checked');
        const bulkBar = document.querySelector('.bulk-actions-bar');
        const countSpan = bulkBar?.querySelector('.selected-count');

        if (bulkBar && countSpan) {
            if (checkboxes.length > 0) {
                bulkBar.classList.remove('d-none');
                countSpan.textContent = `${checkboxes.length} item${checkboxes.length > 1 ? 's' : ''} selected`;
            } else {
                bulkBar.classList.add('d-none');
            }
        }
    }

    // ========== EXPORT TO CSV ==========
    function initExportButtons() {
        const tables = document.querySelectorAll('.table');

        tables.forEach(table => {
            const tableParent = table.closest('.card') || table.parentElement;
            const cardHeader = tableParent.querySelector('.card-header');

            if (cardHeader && !cardHeader.querySelector('.export-btn')) {
                const exportBtn = document.createElement('button');
                exportBtn.className = 'btn btn-sm btn-success export-btn float-end me-2';
                exportBtn.innerHTML = '<i class="fas fa-download"></i> Export';

                cardHeader.appendChild(exportBtn);

                exportBtn.addEventListener('click', function () {
                    exportTableToCSV(table);
                });
            }
        });
    }

    function exportTableToCSV(table) {
        const rows = table.querySelectorAll('tr');
        let csv = [];

        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = [];

            cols.forEach(col => {
                let data = col.textContent.trim();
                data = data.replace(/"/g, '""'); // Escape quotes
                rowData.push(`"${data}"`);
            });

            csv.push(rowData.join(','));
        });

        // Download CSV
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `export_${Date.now()}.csv`;
        a.click();
        window.URL.revokeObjectURL(url);
    }

    // ========== QUICK STATS ==========
    function initQuickStats() {
        // Add percentage changes to stats cards
        const statsCards = document.querySelectorAll('.border-left-primary, .border-left-success, .border-left-info, .border-left-warning');

        statsCards.forEach(card => {
            const valueElement = card.querySelector('.h5');
            if (valueElement && !card.querySelector('.stat-change')) {
                const changeSpan = document.createElement('small');
                changeSpan.className = 'stat-change text-success ms-2';
                changeSpan.innerHTML = '<i class="fas fa-arrow-up"></i> +12%';
                valueElement.appendChild(changeSpan);
            }
        });
    }

    // ========== INITIALIZE ALL ==========
    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function () {
                initTableSearch();
                initCardSearch();
                initStatusFilter();
                initBulkActions();
                initExportButtons();
                initQuickStats();
            });
        } else {
            initTableSearch();
            initCardSearch();
            initStatusFilter();
            initBulkActions();
            initExportButtons();
            initQuickStats();
        }
    }

    init();

})();

// ========== GLOBAL BULK ACTION FUNCTIONS ==========
function bulkDelete() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox:checked');
    if (checkboxes.length === 0) return;

    if (confirm(`Are you sure you want to delete ${checkboxes.length} item(s)?`)) {
        // Implement bulk delete logic here
        alert('Bulk delete functionality - to be implemented with backend');
    }
}

function bulkActivate() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox:checked');
    if (checkboxes.length === 0) return;

    alert(`Activating ${checkboxes.length} item(s) - to be implemented with backend`);
}

function bulkDeactivate() {
    const checkboxes = document.querySelectorAll('.bulk-checkbox:checked');
    if (checkboxes.length === 0) return;

    alert(`Deactivating ${checkboxes.length} item(s) - to be implemented with backend`);
}
