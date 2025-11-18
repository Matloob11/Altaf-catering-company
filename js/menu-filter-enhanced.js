// ========================================
// Enhanced Menu Filter & Search System
// ========================================
(function ($) {
    "use strict";

    var currentFilter = 'all';
    var searchTerm = '';

    // Initialize
    $(document).ready(function () {
        initializeFilters();
        initializeSearch();
        updateResultsCount();
    });

    // Filter Buttons
    function initializeFilters() {
        $('.filter-btn').click(function () {
            $('.filter-btn').removeClass('active');
            $(this).addClass('active');
            currentFilter = $(this).data('filter');
            applyFilters();
        });
    }

    // Search Functionality
    function initializeSearch() {
        var searchTimeout;

        $('#menuSearch').on('keyup', function () {
            clearTimeout(searchTimeout);
            searchTerm = $(this).val().toLowerCase();

            searchTimeout = setTimeout(function () {
                applyFilters();
            }, 300);
        });

        // Clear Search Button
        $('#clearSearch').click(function () {
            $('#menuSearch').val('');
            searchTerm = '';
            applyFilters();
        });
    }

    // Apply All Filters
    function applyFilters() {
        var visibleCount = 0;

        $('.menu-item').each(function () {
            var $item = $(this);
            var itemName = $item.data('name') ? $item.data('name').toLowerCase() : '';
            var itemDesc = $item.data('description') ? $item.data('description').toLowerCase() : '';
            var itemType = $item.data('type') ? $item.data('type').toLowerCase() : '';
            var itemPopular = $item.data('popular') === true || $item.data('popular') === 'true';

            var matchesSearch = true;
            var matchesFilter = true;

            // Search Filter
            if (searchTerm) {
                matchesSearch = itemName.includes(searchTerm) || itemDesc.includes(searchTerm);
            }

            // Type Filter
            if (currentFilter !== 'all') {
                if (currentFilter === 'popular') {
                    matchesFilter = itemPopular;
                } else {
                    matchesFilter = itemType === currentFilter;
                }
            }

            // Show/Hide Item
            if (matchesSearch && matchesFilter) {
                $item.fadeIn(300);
                visibleCount++;
            } else {
                $item.fadeOut(300);
            }
        });

        // Update Results Count
        updateResultsCount(visibleCount);

        // Show "No Results" Message
        if (visibleCount === 0) {
            showNoResults();
        } else {
            hideNoResults();
        }
    }

    // Update Results Counter
    function updateResultsCount(count) {
        if (count === undefined) {
            count = $('.menu-item:visible').length;
        }
        $('#countNumber').text(count);
    }

    // Show No Results Message
    function showNoResults() {
        if ($('#noResults').length === 0) {
            var message = $('<div id="noResults" class="col-12 text-center py-5">' +
                '<i class="fas fa-search fa-3x text-muted mb-3"></i>' +
                '<h4 class="text-muted">No items found</h4>' +
                '<p class="text-muted">Try adjusting your search or filter</p>' +
                '</div>');
            $('.tab-pane.active .row').append(message);
        }
    }

    // Hide No Results Message
    function hideNoResults() {
        $('#noResults').remove();
    }

    // Add data attributes to menu items for filtering
    function addDataAttributes() {
        $('.menu-item').each(function () {
            var $item = $(this);
            var name = $item.find('h4').text();
            var description = $item.find('p').text();

            $item.attr('data-name', name);
            $item.attr('data-description', description);

            // You can add more attributes based on your menu structure
            // Example: data-type="veg" or data-popular="true"
        });
    }

    // Call this after page load
    setTimeout(addDataAttributes, 500);

})(jQuery);
