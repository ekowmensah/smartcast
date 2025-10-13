        </div>
    </div>

    <!-- Public Footer -->
    <footer class="footer">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="text-center text-md-start">
                        <strong><?= APP_NAME ?></strong>
                        <span class="text-medium-emphasis">&copy; <?= date('Y') ?> Professional Voting Platform</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-center text-md-end">
                        <span class="text-medium-emphasis">Version <?= APP_VERSION ?></span>
                        <span class="mx-2">|</span>
                        <a href="<?= PUBLIC_URL ?>/privacy" class="text-decoration-none">Privacy</a>
                        <span class="mx-2">|</span>
                        <a href="<?= PUBLIC_URL ?>/terms" class="text-decoration-none">Terms</a>
                        <span class="mx-2">|</span>
                        <a href="<?= PUBLIC_URL ?>/support" class="text-decoration-none">Support</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- CoreUI JavaScript -->
    <script src="<?= COREUI_JS ?>"></script>
    <!-- Custom JavaScript -->
    <script src="<?= APP_URL ?>/public/js/public.js"></script>
    <!-- Image Helper -->
    <script src="<?= APP_URL ?>/public/assets/js/image-helper.js"></script>
    <script>window.APP_URL = '<?= APP_URL ?>';</script>
    
    <!-- Auto-hide alerts -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
            alerts.forEach(function(alert) {
                const bsAlert = new coreui.Alert(alert);
                if (bsAlert) {
                    bsAlert.close();
                }
            });
        }, 5000);
    });
    </script>
</body>
</html>
