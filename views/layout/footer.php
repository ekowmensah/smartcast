    </main>

    <footer class="bg-light mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><?= APP_NAME ?></h5>
                    <p class="text-muted">Professional voting management system for events and competitions.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="text-muted mb-0">
                        &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
                    </p>
                    <small class="text-muted">Version <?= APP_VERSION ?></small>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="<?= APP_URL ?>/public/js/app.js?v=<?= time() ?>"></script>
</body>
</html>
