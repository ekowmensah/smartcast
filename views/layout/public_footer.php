    </main>

    <!-- Public Footer -->
    <footer class="bg-light border-top mt-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center text-md-start">
                        <strong><?= APP_NAME ?></strong>
                        <span class="text-muted">&copy; <?= date('Y') ?> Professional Voting Platform</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center text-md-end">
                    <!--    <span class="text-muted">Version <?= APP_VERSION ?></span> -->
                        <span class="mx-2 text-muted">|</span>
                        <a href="<?= PUBLIC_URL ?>/privacy" class="text-decoration-none">Privacy</a>
                        <span class="mx-2 text-muted">|</span>
                        <a href="<?= PUBLIC_URL ?>/terms" class="text-decoration-none">Terms</a>
                        <span class="mx-2 text-muted">|</span>
                        <a href="<?= PUBLIC_URL ?>/support" class="text-decoration-none">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript (Required for mobile menu and dropdowns) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CoreUI JavaScript -->
    <script src="<?= COREUI_JS ?>"></script>
    <!-- Custom JavaScript -->
    <script src="<?= APP_URL ?>/public/js/public.js"></script>
    <!-- Image Helper -->
    <script src="<?= APP_URL ?>/public/assets/js/image-helper.js"></script>
    <!-- PWA JavaScript -->
    <script src="<?= APP_URL ?>/public/js/pwa.js"></script>
    <script>window.APP_URL = '<?= APP_URL ?>';</script>
    
    <!-- Enhanced Mobile Menu & Dropdown Support -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure Bootstrap dropdowns work properly
        var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
        var dropdownList = dropdownElementList.map(function (dropdownToggleEl) {
            return new bootstrap.Dropdown(dropdownToggleEl);
        });
        
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
                if (bsAlert) {
                    bsAlert.close();
                }
            });
        }, 5000);
        
        // Mobile menu auto-close on link click
        const navbarCollapse = document.getElementById('navbarNav');
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)');
        
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (navbarCollapse.classList.contains('show')) {
                    const bsCollapse = bootstrap.Collapse.getInstance(navbarCollapse);
                    if (bsCollapse) {
                        bsCollapse.hide();
                    }
                }
            });
        });
    });
    </script>
</body>
</html>
