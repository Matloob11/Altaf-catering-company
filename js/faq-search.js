// ========================================
// FAQ Search Functionality
// ========================================
(function ($) {
    "use strict";

    var searchTimeout;

    $('#faqSearch').on('keyup', function () {
        clearTimeout(searchTimeout);
        var searchTerm = $(this).val().toLowerCase();

        searchTimeout = setTimeout(function () {
            filterFAQs(searchTerm);
        }, 300);
    });

    function filterFAQs(searchTerm) {
        var visibleCount = 0;

        $('.accordion-item').each(function () {
            var $item = $(this);
            var question = $item.find('.accordion-button').text().toLowerCase();
            var answer = $item.find('.accordion-body').text().toLowerCase();

            if (searchTerm === '' || question.includes(searchTerm) || answer.includes(searchTerm)) {
                $item.fadeIn(300);
                visibleCount++;
            } else {
                $item.fadeOut(300);
            }
        });

        // Update count
        $('#faqCount').text(visibleCount);

        // Show no results message
        if (visibleCount === 0) {
            showNoResults();
        } else {
            hideNoResults();
        }
    }

    function showNoResults() {
        if ($('#noFaqResults').length === 0) {
            var message = $('<div id="noFaqResults" class="col-12 text-center py-5">' +
                '<i class="fas fa-search fa-3x text-muted mb-3"></i>' +
                '<h4 class="text-muted">No FAQs found</h4>' +
                '<p class="text-muted">Try different keywords or <a href="contact.php">contact us</a> directly</p>' +
                '</div>');
            $('.row.g-4').append(message);
        }
    }

    function hideNoResults() {
        $('#noFaqResults').remove();
    }

})(jQuery);
