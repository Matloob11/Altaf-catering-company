// 🎯 Bulk Actions System for Admin Panel

class BulkActionsManager {
    constructor() {
        this.selectedItems = new Set();
        this.init();
    }

    init() {
        this.setupSelectAll();
        this.setupItemCheckboxes();
        this.setupBulkActionButtons();
        this.updateBulkActionsBar();
    }

    // Select All checkbox
    setupSelectAll() {
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                const isChecked = e.target.checked;
                document.querySelectorAll('.item-checkbox').forEach(checkbox => {
                    checkbox.checked = isChecked;
                    if (isChecked) {
                        this.selectedItems.add(checkbox.value);
                    } else {
                        this.selectedItems.delete(checkbox.value);
                    }
                });
                this.updateBulkActionsBar();
            });
        }
    }

    // Individual item checkboxes
    setupItemCheckboxes() {
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.selectedItems.add(e.target.value);
                } else {
                    this.selectedItems.delete(e.target.value);
                }
                this.updateSelectAllState();
                this.updateBulkActionsBar();
            });
        });
    }

    // Update "Select All" checkbox state
    updateSelectAllState() {
        const selectAllCheckbox = document.getElementById('selectAll');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        const checkedCount = document.querySelectorAll('.item-checkbox:checked').length;

        if (selectAllCheckbox) {
            selectAllCheckbox.checked = checkedCount === itemCheckboxes.length && checkedCount > 0;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < itemCheckboxes.length;
        }
    }

    // Update bulk actions bar visibility and count
    updateBulkActionsBar() {
        const bulkActionsBar = document.getElementById('bulkActionsBar');
        const selectedCount = document.getElementById('selectedCount');

        if (bulkActionsBar && selectedCount) {
            if (this.selectedItems.size > 0) {
                bulkActionsBar.style.display = 'block';
                bulkActionsBar.classList.add('animate-fade-in');
                selectedCount.textContent = this.selectedItems.size;
            } else {
                bulkActionsBar.style.display = 'none';
            }
        }
    }

    // Setup bulk action buttons
    setupBulkActionButtons() {
        // Bulk Delete
        const bulkDeleteBtn = document.getElementById('bulkDelete');
        if (bulkDeleteBtn) {
            bulkDeleteBtn.addEventListener('click', () => this.bulkDelete());
        }

        // Bulk Publish
        const bulkPublishBtn = document.getElementById('bulkPublish');
        if (bulkPublishBtn) {
            bulkPublishBtn.addEventListener('click', () => this.bulkPublish());
        }

        // Bulk Unpublish
        const bulkUnpublishBtn = document.getElementById('bulkUnpublish');
        if (bulkUnpublishBtn) {
            bulkUnpublishBtn.addEventListener('click', () => this.bulkUnpublish());
        }

        // Bulk Export
        const bulkExportBtn = document.getElementById('bulkExport');
        if (bulkExportBtn) {
            bulkExportBtn.addEventListener('click', () => this.bulkExport());
        }

        // Clear Selection
        const clearSelectionBtn = document.getElementById('clearSelection');
        if (clearSelectionBtn) {
            clearSelectionBtn.addEventListener('click', () => this.clearSelection());
        }
    }

    // Bulk Delete
    bulkDelete() {
        if (this.selectedItems.size === 0) {
            this.showNotification('Please select items to delete', 'warning');
            return;
        }

        const confirmMessage = `Are you sure you want to delete ${this.selectedItems.size} item(s)? This action cannot be undone!`;

        if (confirm(confirmMessage)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.style.display = 'none';

            // Add bulk_delete action
            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'bulk_delete';
            actionInput.value = '1';
            form.appendChild(actionInput);

            // Add selected IDs
            this.selectedItems.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }
    }

    // Bulk Publish
    bulkPublish() {
        if (this.selectedItems.size === 0) {
            this.showNotification('Please select items to publish', 'warning');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'bulk_publish';
        actionInput.value = '1';
        form.appendChild(actionInput);

        this.selectedItems.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Bulk Unpublish
    bulkUnpublish() {
        if (this.selectedItems.size === 0) {
            this.showNotification('Please select items to unpublish', 'warning');
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.style.display = 'none';

        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'bulk_unpublish';
        actionInput.value = '1';
        form.appendChild(actionInput);

        this.selectedItems.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'selected_ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
    }

    // Bulk Export
    bulkExport() {
        if (this.selectedItems.size === 0) {
            this.showNotification('Please select items to export', 'warning');
            return;
        }

        this.showNotification(`Exporting ${this.selectedItems.size} item(s)...`, 'info');

        // Create export data
        const exportData = [];
        this.selectedItems.forEach(id => {
            const row = document.querySelector(`tr[data-id="${id}"]`);
            if (row) {
                const rowData = {};
                row.querySelectorAll('td').forEach((cell, index) => {
                    if (!cell.querySelector('.item-checkbox')) {
                        rowData[`column_${index}`] = cell.textContent.trim();
                    }
                });
                exportData.push(rowData);
            }
        });

        // Convert to CSV
        if (exportData.length > 0) {
            const csv = this.convertToCSV(exportData);
            this.downloadCSV(csv, 'bulk_export.csv');
            this.showNotification('Export completed successfully!', 'success');
        }
    }

    // Convert data to CSV
    convertToCSV(data) {
        const headers = Object.keys(data[0]);
        const csvRows = [];

        // Add headers
        csvRows.push(headers.join(','));

        // Add data rows
        data.forEach(row => {
            const values = headers.map(header => {
                const value = row[header] || '';
                return `"${value.replace(/"/g, '""')}"`;
            });
            csvRows.push(values.join(','));
        });

        return csvRows.join('\n');
    }

    // Download CSV file
    downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    // Clear selection
    clearSelection() {
        this.selectedItems.clear();
        document.querySelectorAll('.item-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        const selectAllCheckbox = document.getElementById('selectAll');
        if (selectAllCheckbox) {
            selectAllCheckbox.checked = false;
            selectAllCheckbox.indeterminate = false;
        }
        this.updateBulkActionsBar();
        this.showNotification('Selection cleared', 'info');
    }

    // Show notification
    showNotification(message, type = 'info') {
        if (window.notificationManager) {
            notificationManager.addNotification({
                title: 'Bulk Actions',
                message: message,
                icon: type === 'success' ? 'check-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle',
                type: type
            });
        } else {
            alert(message);
        }
    }

    // Get selected items
    getSelectedItems() {
        return Array.from(this.selectedItems);
    }

    // Get selected count
    getSelectedCount() {
        return this.selectedItems.size;
    }
}

// Initialize bulk actions manager
let bulkActionsManager;
document.addEventListener('DOMContentLoaded', () => {
    bulkActionsManager = new BulkActionsManager();
    window.bulkActionsManager = bulkActionsManager;
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = BulkActionsManager;
}
