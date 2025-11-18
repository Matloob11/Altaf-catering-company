/**
 * Menu Filter & Search Functionality
 * Allows users to filter menu items by category and search by name
 */

class MenuFilter {
    constructor() {
        this.init();
    }

    init() {
        // Add search functionality if search input exists
        const searchInput = document.getElementById('menuSearch');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => this.searchMenu(e.target.value));
        }

        // Add filter functionality to category buttons
        const filterButtons = document.querySelectorAll('[data-filter]');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => this.filterByCategory(btn.dataset.filter));
        });

        // Track current filter state
        this.currentFilter = 'all';
        this.currentSearch = '';
    }

    searchMenu(searchTerm) {
        this.currentSearch = searchTerm.toLowerCase();
        this.applyFilters();
    }

    filterByCategory(category) {
        this.currentFilter = category;
        this.applyFilters();
    }

    applyFilters() {
        const menuItems = document.querySelectorAll('.menu-item');
        let visibleCount = 0;

        menuItems.forEach(item => {
            const shouldShow = this.shouldShowItem(item);
            item.parentElement.style.display = shouldShow ? '' : 'none';
            if (shouldShow) visibleCount++;
        });

        // Show "no results" message if needed
        this.updateNoResultsMessage(visibleCount);
    }

    shouldShowItem(item) {
        // Get menu item name
        const title = item.querySelector('h4')?.textContent.toLowerCase() || '';
        const description = item.querySelector('p')?.textContent.toLowerCase() || '';

        // Check search term
        if (this.currentSearch && !title.includes(this.currentSearch) && !description.includes(this.currentSearch)) {
            return false;
        }

        // Check category (if implemented with data attributes)
        const category = item.dataset.category;
        if (this.currentFilter !== 'all' && category && category !== this.currentFilter) {
            return false;
        }

        return true;
    }

    updateNoResultsMessage(visibleCount) {
        let noResultsDiv = document.getElementById('menuNoResults');

        if (visibleCount === 0) {
            if (!noResultsDiv) {
                noResultsDiv = document.createElement('div');
                noResultsDiv.id = 'menuNoResults';
                noResultsDiv.className = 'col-12 text-center py-5';
                const tabContent = document.querySelector('.tab-content');
                if (tabContent) {
                    tabContent.appendChild(noResultsDiv);
                }
            }
            noResultsDiv.innerHTML = `
                <div class="alert alert-info">
                    <i class="fas fa-search me-2"></i>
                    <strong>No items found</strong> matching your search criteria.
                    <br><small>Try adjusting your filters or search terms.</small>
                </div>
            `;
        } else if (noResultsDiv) {
            noResultsDiv.remove();
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    new MenuFilter();
});
