// Advanced Search & Filter System
class AdvancedSearch {
    constructor() {
        this.searchData = [];
        this.filters = {};
        this.init();
    }

    init() {
        this.setupSearchBox();
        this.setupFilters();
        this.setupSorting();
    }

    setupSearchBox() {
        const searchInputs = document.querySelectorAll('.advanced-search');
        searchInputs.forEach(input => {
            input.addEventListener('input', (e) => {
                this.performSearch(e.target.value, e.target.dataset.target);
            });
        });
    }

    performSearch(query, target) {
        const targetElement = document.querySelector(target);
        if (!targetElement) return;

        const items = targetElement.querySelectorAll('[data-searchable]');
        let visibleCount = 0;

        items.forEach(item => {
            const searchText = item.dataset.searchable.toLowerCase();
            const matches = searchText.includes(query.toLowerCase());

            if (matches || query === '') {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        this.updateResultCount(targetElement, visibleCount, items.length);
    }

    setupFilters() {
        const filterSelects = document.querySelectorAll('.filter-select');
        filterSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                this.applyFilter(e.target.value, e.target.dataset.filterType, e.target.dataset.target);
            });
        });
    }

    applyFilter(value, filterType, target) {
        const targetElement = document.querySelector(target);
        if (!targetElement) return;

        const items = targetElement.querySelectorAll('[data-filterable]');
        let visibleCount = 0;

        items.forEach(item => {
            const filterValue = item.dataset[filterType];
            const matches = value === '' || filterValue === value;

            if (matches) {
                item.style.display = '';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        this.updateResultCount(targetElement, visibleCount, items.length);
    }

    setupSorting() {
        const sortButtons = document.querySelectorAll('[data-sort]');
        sortButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const sortBy = button.dataset.sort;
                const sortOrder = button.dataset.order || 'asc';
                const target = button.dataset.target;

                this.sortItems(sortBy, sortOrder, target);

                // Toggle sort order
                button.dataset.order = sortOrder === 'asc' ? 'desc' : 'asc';
                this.updateSortIcon(button, button.dataset.order);
            });
        });
    }

    sortItems(sortBy, order, target) {
        const container = document.querySelector(target);
        if (!container) return;

        const items = Array.from(container.querySelectorAll('[data-sortable]'));

        items.sort((a, b) => {
            const aValue = a.dataset[sortBy] || a.textContent;
            const bValue = b.dataset[sortBy] || b.textContent;

            if (order === 'asc') {
                return aValue.localeCompare(bValue);
            } else {
                return bValue.localeCompare(aValue);
            }
        });

        items.forEach(item => container.appendChild(item));
    }

    updateSortIcon(button, order) {
        const icon = button.querySelector('i');
        if (icon) {
            icon.className = order === 'asc' ? 'fas fa-sort-up' : 'fas fa-sort-down';
        }
    }

    updateResultCount(container, visible, total) {
        let countElement = container.querySelector('.result-count');
        if (!countElement) {
            countElement = document.createElement('div');
            countElement.className = 'result-count alert alert-info mt-2';
            container.insertBefore(countElement, container.firstChild);
        }
        countElement.textContent = `Showing ${visible} of ${total} results`;
    }

    // Bulk selection
    setupBulkSelection() {
        const selectAll = document.querySelector('.select-all');
        if (selectAll) {
            selectAll.addEventListener('change', (e) => {
                const checkboxes = document.querySelectorAll('.item-checkbox');
                checkboxes.forEach(cb => cb.checked = e.target.checked);
                this.updateBulkActions();
            });
        }

        const itemCheckboxes = document.querySelectorAll('.item-checkbox');
        itemCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => this.updateBulkActions());
        });
    }

    updateBulkActions() {
        const selected = document.querySelectorAll('.item-checkbox:checked').length;
        const bulkActions = document.querySelector('.bulk-actions');

        if (bulkActions) {
            if (selected > 0) {
                bulkActions.style.display = 'block';
                bulkActions.querySelector('.selected-count').textContent = selected;
            } else {
                bulkActions.style.display = 'none';
            }
        }
    }

    getSelectedItems() {
        const checkboxes = document.querySelectorAll('.item-checkbox:checked');
        return Array.from(checkboxes).map(cb => cb.value);
    }
}

// Initialize advanced search
const advancedSearch = new AdvancedSearch();
window.advancedSearch = advancedSearch;
