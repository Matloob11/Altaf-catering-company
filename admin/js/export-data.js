// Export Data Functionality
class DataExporter {
    constructor() {
        this.init();
    }

    init() {
        this.setupExportButtons();
    }

    setupExportButtons() {
        document.addEventListener('click', (e) => {
            if (e.target.closest('.export-csv')) {
                e.preventDefault();
                const table = e.target.closest('.card').querySelector('table');
                if (table) this.exportToCSV(table);
            }

            if (e.target.closest('.export-excel')) {
                e.preventDefault();
                const table = e.target.closest('.card').querySelector('table');
                if (table) this.exportToExcel(table);
            }

            if (e.target.closest('.export-pdf')) {
                e.preventDefault();
                this.exportToPDF();
            }
        });
    }

    exportToCSV(table) {
        let csv = [];
        const rows = table.querySelectorAll('tr');

        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const csvRow = [];
            cols.forEach(col => {
                csvRow.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
            });
            csv.push(csvRow.join(','));
        });

        const csvContent = csv.join('\n');
        this.downloadFile(csvContent, 'export.csv', 'text/csv');
        this.showNotification('CSV exported successfully!', 'success');
    }

    exportToExcel(table) {
        const html = table.outerHTML;
        const blob = new Blob([html], {
            type: 'application/vnd.ms-excel'
        });
        this.downloadBlob(blob, 'export.xls');
        this.showNotification('Excel file exported successfully!', 'success');
    }

    exportToPDF() {
        window.print();
        this.showNotification('Print dialog opened for PDF export', 'info');
    }

    downloadFile(content, filename, type) {
        const blob = new Blob([content], { type: type });
        this.downloadBlob(blob, filename);
    }

    downloadBlob(blob, filename) {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    showNotification(message, type) {
        if (window.notificationManager) {
            notificationManager.addNotification({
                title: 'Export',
                message: message,
                icon: 'download',
                type: type
            });
        }
    }
}

// Initialize exporter
const dataExporter = new DataExporter();
window.dataExporter = dataExporter;
