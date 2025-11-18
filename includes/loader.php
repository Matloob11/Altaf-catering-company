<!-- Spinner Start -->
<div id="spinner" class="show w-100 vh-100 position-fixed translate-middle top-50 start-50 d-flex align-items-center justify-content-center">
    <div class="loader-container">
        <img src="img/logo.png" alt="Altaf Catering" class="loader-logo">
        <div class="loader-spinner">
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
            <div class="spinner-ring"></div>
        </div>
        <div class="loader-brand">Altaf Catering</div>
        <div class="loader-text"><?php echo isset($loader_text) ? $loader_text : 'Loading...'; ?></div>
        <div class="loader-dots">
            <span class="loader-dot"></span>
            <span class="loader-dot"></span>
            <span class="loader-dot"></span>
        </div>
    </div>
    <div class="loader-progress">
        <div class="loader-progress-bar"></div>
    </div>
</div>
<script>
    // Ensure spinner shows initially
    if (document.getElementById('spinner')) {
        document.getElementById('spinner').classList.add('show');
    }
</script>

<!-- AGGRESSIVE LOADER REMOVAL - Load immediately after spinner -->
<script>
(function() {
    'use strict';
    var loaderHidden = false;
    
    function forceHideLoader() {
        if (loaderHidden) return;
        var spinner = document.getElementById('spinner');
        if (spinner) {
            spinner.classList.remove('show');
            loaderHidden = true;
            console.log('✅ Loader FORCED hidden');
        }
    }
    
    // Method 1: DOM Ready (800ms)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(forceHideLoader, 800);
        });
    } else {
        setTimeout(forceHideLoader, 800);
    }
    
    // Method 2: Window Load (500ms after)
    window.addEventListener('load', function() {
        setTimeout(forceHideLoader, 500);
    });
    
    // Method 3: Absolute Timeout (2.5 seconds MAX)
    setTimeout(forceHideLoader, 2500);
    
    // Method 4: On user interaction
    document.addEventListener('scroll', forceHideLoader, { once: true });
    document.addEventListener('mousemove', forceHideLoader, { once: true });
    document.addEventListener('touchstart', forceHideLoader, { once: true });
    document.addEventListener('click', forceHideLoader, { once: true });
})();
</script>
<!-- Spinner End -->
