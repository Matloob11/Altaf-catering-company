(function ($) {
    "use strict";

    // Active nav item highlighting
    $(window).on('load', function () {
        setTimeout(function () {
            // Remove all active classes first
            $('.nav-item .nav-link, .dropdown-item').removeClass('active');

            // Get current page URL path
            var path = window.location.pathname;
            var page = path.split("/").pop() || 'index.html';

            // Set active class on nav items
            $('.navbar-nav .nav-item .nav-link').each(function () {
                var href = $(this).attr('href');
                if (href === page) {
                    $(this).addClass('active');
                    // If this is inside a dropdown, also highlight the dropdown toggle
                    var dropdownToggle = $(this).closest('.dropdown').find('.dropdown-toggle');
                    if (dropdownToggle.length) {
                        dropdownToggle.addClass('active');
                    }
                }
            });

            // Also check dropdown items
            $('.dropdown-menu .dropdown-item').each(function () {
                var href = $(this).attr('href');
                if (href === page) {
                    $(this).addClass('active');
                    // Highlight the dropdown toggle when a dropdown item is active
                    $(this).closest('.dropdown').find('.dropdown-toggle').addClass('active');
                } else {
                    $(this).removeClass('active');
                }
            });
        }, 100); // Small delay to ensure DOM is ready
    });

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner(0);


    // Initiate the wowjs
    new WOW().init();


    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top, .back-to-top-btn').addClass('show').fadeIn('slow');
        } else {
            $('.back-to-top, .back-to-top-btn').removeClass('show').fadeOut('slow');
        }
    });
    $('.back-to-top, .back-to-top-btn').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });


    // Modal Video
    $(document).ready(function () {
        var $videoSrc;
        $('.btn-play').click(function () {
            $videoSrc = $(this).data("src");
        });
        console.log($videoSrc);

        $('#videoModal').on('shown.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc + "?autoplay=1&amp;modestbranding=1&amp;showinfo=0");
        })

        $('#videoModal').on('hide.bs.modal', function (e) {
            $("#video").attr('src', $videoSrc);
        })
    });


    // Facts counter
    $('[data-toggle="counter-up"]').counterUp({
        delay: 10,
        time: 2000
    });


    // Testimonial carousel
    $(".testimonial-carousel-1").owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        navText: [
            '<i class="fa fa-angle-left"></i>',
            '<i class="fa fa-angle-right"></i>'
        ],
        margin: 25,
        autoplay: true,
        slideTransition: 'ease-in-out',
        autoplayTimeout: 5000,
        autoplaySpeed: 1000,
        autoplayHoverPause: true,
        mouseDrag: true,
        touchDrag: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1
            },
            575: {
                items: 1
            },
            767: {
                items: 2
            },
            991: {
                items: 3
            }
        }
    });

    $(".testimonial-carousel-2").owlCarousel({
        loop: true,
        dots: true,
        nav: true,
        navText: [
            '<i class="fa fa-angle-left"></i>',
            '<i class="fa fa-angle-right"></i>'
        ],
        rtl: true,
        margin: 25,
        autoplay: true,
        slideTransition: 'ease-in-out',
        autoplayTimeout: 5000,
        autoplaySpeed: 1000,
        autoplayHoverPause: true,
        mouseDrag: true,
        touchDrag: true,
        smartSpeed: 1000,
        responsive: {
            0: {
                items: 1
            },
            575: {
                items: 1
            },
            767: {
                items: 2
            },
            991: {
                items: 3
            }
        }
    });

    // Save index scroll position when user clicks Read More (so we can return to same spot)
    $(document).on('click', 'a.save-index-scroll', function () {
        try {
            sessionStorage.setItem('indexScroll', window.scrollY || window.pageYOffset || 0);
        } catch (e) {
            // storage might be blocked; ignore silently
        }
    });

    // On load, restore scroll position if set (used when returning from service page)
    $(window).on('load', function () {
        try {
            var saved = sessionStorage.getItem('indexScroll');
            if (saved) {
                // small timeout to let other scripts/layout settle
                setTimeout(function () {
                    $('html, body').animate({ scrollTop: parseInt(saved, 10) || 0 }, 600);
                    sessionStorage.removeItem('indexScroll');
                }, 120);
            }
        } catch (e) {
            // ignore
        }
    });

    // Sticky navbar: when the main hero/content starts, make the nav sticky with a small delay and animation
    (function () {
        var $hero = $('.container-fluid.bg-light.py-6').first();
        var $nav = $('.nav-bar');
        if ($hero.length && $nav.length) {
            var $placeholder = $('<div class="nav-placeholder"></div>');
            $nav.before($placeholder);
            function recalc() {
                $placeholder.height($nav.outerHeight());
            }
            recalc();
            var threshold = Math.max(0, $hero.offset().top - 20);
            var stickyTimer = null;
            $(window).on('scroll resize', function () {
                var st = $(this).scrollTop();
                if (st >= threshold) {
                    if (!$nav.hasClass('sticky')) {
                        clearTimeout(stickyTimer);
                        stickyTimer = setTimeout(function () {
                            $nav.addClass('sticky');
                            $placeholder.show();
                        }, 150);
                    }
                } else {
                    clearTimeout(stickyTimer);
                    if ($nav.hasClass('sticky')) {
                        $nav.removeClass('sticky');
                        $placeholder.hide();
                    }
                }
            });
            // also recalc heights on images/fonts load
            $(window).on('load', recalc);
        }
    })();

    // Responsive helper for social buttons (toggle classes small/xsmall based on width)
    (function () {
        var $sb = $('.social-buttons');
        if (!$sb.length) return;
        var resizeTimer = null;
        function updateSocialButtons() {
            var w = $(window).width();
            $sb.removeClass('small xsmall');
            if (w < 400) {
                $sb.addClass('xsmall');
            } else if (w < 768) {
                $sb.addClass('small');
            }
        }
        // init
        $(document).ready(updateSocialButtons);
        // debounce on resize
        $(window).on('resize', function () {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(updateSocialButtons, 120);
        });
    })();

    // Newsletter form handler (client-side only)
    (function () {
        var $form = $('#newsletterForm');
        if (!$form.length) return;
        $form.on('submit', function (e) {
            e.preventDefault();
            var $email = $('#newsletterEmail');
            var email = ($email.val() || '').trim();
            // Simple client-side validation
            var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\\.,;:\s@\"]+\.)+[^<>()[\]\\.,;:\s@\"]{2,})$/i;
            if (!email || !re.test(email)) {
                // show inline error
                var $err = $('<div class="mt-2 text-warning small">Please enter a valid email address.</div>');
                $form.find('.text-warning').remove();
                $form.append($err);
                $email.focus();
                return;
            }

            // Show a temporary success message (no server submission)
            $form.find('.text-warning').remove();
            var $msg = $('<div class="mt-2 text-success small">Thank you — you are subscribed (demo).</div>');
            $form.append($msg);
            // Clear input after a short delay
            setTimeout(function () {
                $email.val('');
                $msg.fadeOut(400, function () { $(this).remove(); });
            }, 2500);
        });
    })();

})(jQuery);

