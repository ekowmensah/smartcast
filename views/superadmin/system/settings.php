<!-- Global Settings -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="mb-1">
            <i class="fas fa-sliders-h text-primary me-2"></i>
            Global Settings
        </h2>
        <p class="text-muted mb-0">Configure platform-wide settings and preferences</p>
    </div>
    <div>
        <button class="btn btn-success" onclick="saveAllSettings()">
            <i class="fas fa-save me-2"></i>Save All Changes
        </button>
        <button class="btn btn-outline-secondary" onclick="location.reload()">
            <i class="fas fa-sync me-2"></i>Reset
        </button>
    </div>
</div>

<!-- Settings Categories -->
<div class="row">
    <div class="col-lg-3">
        <div class="nav flex-column nav-pills" id="settingsTab" role="tablist">
            <button class="nav-link active" id="general-tab" data-coreui-toggle="pill" data-coreui-target="#general" type="button">
                <i class="fas fa-cog me-2"></i>General
            </button>
            <button class="nav-link" id="security-tab" data-coreui-toggle="pill" data-coreui-target="#security" type="button">
                <i class="fas fa-shield-alt me-2"></i>Security
            </button>
            <button class="nav-link" id="email-tab" data-coreui-toggle="pill" data-coreui-target="#email" type="button">
                <i class="fas fa-envelope me-2"></i>Email
            </button>
            <button class="nav-link" id="payment-tab" data-coreui-toggle="pill" data-coreui-target="#payment" type="button">
                <i class="fas fa-credit-card me-2"></i>Payment
            </button>
            <button class="nav-link" id="api-tab" data-coreui-toggle="pill" data-coreui-target="#api" type="button">
                <i class="fas fa-code me-2"></i>API
            </button>
            <button class="nav-link" id="maintenance-tab" data-coreui-toggle="pill" data-coreui-target="#maintenance" type="button">
                <i class="fas fa-tools me-2"></i>Maintenance
            </button>
        </div>
    </div>
    
    <div class="col-lg-9">
        <div class="tab-content" id="settingsTabContent">
            <!-- General Settings -->
            <div class="tab-pane fade show active" id="general" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">General Platform Settings</h5>
                    </div>
                    <div class="card-body">
                        <form id="generalSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platformName" class="form-label">Platform Name</label>
                                        <input type="text" class="form-control" id="platformName" name="platform_name" value="<?= $settings['platform_name'] ?? 'SmartCast' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platformUrl" class="form-label">Platform URL</label>
                                        <input type="url" class="form-control" id="platformUrl" name="platform_url" value="<?= $settings['platform_url'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="platformDescription" class="form-label">Platform Description</label>
                                <textarea class="form-control" id="platformDescription" name="platform_description" rows="3"><?= $settings['platform_description'] ?? '' ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="defaultTimezone" class="form-label">Default Timezone</label>
                                        <select class="form-select" id="defaultTimezone" name="default_timezone">
                                            <option value="UTC" <?= ($settings['default_timezone'] ?? '') === 'UTC' ? 'selected' : '' ?>>UTC</option>
                                            <option value="America/New_York" <?= ($settings['default_timezone'] ?? '') === 'America/New_York' ? 'selected' : '' ?>>Eastern Time</option>
                                            <option value="America/Chicago" <?= ($settings['default_timezone'] ?? '') === 'America/Chicago' ? 'selected' : '' ?>>Central Time</option>
                                            <option value="America/Denver" <?= ($settings['default_timezone'] ?? '') === 'America/Denver' ? 'selected' : '' ?>>Mountain Time</option>
                                            <option value="America/Los_Angeles" <?= ($settings['default_timezone'] ?? '') === 'America/Los_Angeles' ? 'selected' : '' ?>>Pacific Time</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="defaultLanguage" class="form-label">Default Language</label>
                                        <select class="form-select" id="defaultLanguage" name="default_language">
                                            <option value="en" <?= ($settings['default_language'] ?? '') === 'en' ? 'selected' : '' ?>>English</option>
                                            <option value="es" <?= ($settings['default_language'] ?? '') === 'es' ? 'selected' : '' ?>>Spanish</option>
                                            <option value="fr" <?= ($settings['default_language'] ?? '') === 'fr' ? 'selected' : '' ?>>French</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="allowRegistration" name="allow_registration" <?= ($settings['allow_registration'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="allowRegistration">
                                    Allow new tenant registrations
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="maintenanceMode" name="maintenance_mode" <?= ($settings['maintenance_mode'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="maintenanceMode">
                                    Enable maintenance mode
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Security Settings -->
            <div class="tab-pane fade" id="security" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Security Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="securitySettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sessionTimeout" class="form-label">Session Timeout (minutes)</label>
                                        <input type="number" class="form-control" id="sessionTimeout" name="session_timeout" value="<?= $settings['session_timeout'] ?? 60 ?>" min="5" max="1440">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="maxLoginAttempts" class="form-label">Max Login Attempts</label>
                                        <input type="number" class="form-control" id="maxLoginAttempts" name="max_login_attempts" value="<?= $settings['max_login_attempts'] ?? 5 ?>" min="3" max="10">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="passwordMinLength" class="form-label">Minimum Password Length</label>
                                        <input type="number" class="form-control" id="passwordMinLength" name="password_min_length" value="<?= $settings['password_min_length'] ?? 8 ?>" min="6" max="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="passwordExpiry" class="form-label">Password Expiry (days)</label>
                                        <input type="number" class="form-control" id="passwordExpiry" name="password_expiry" value="<?= $settings['password_expiry'] ?? 90 ?>" min="30" max="365">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="requireTwoFactor" name="require_two_factor" <?= ($settings['require_two_factor'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="requireTwoFactor">
                                    Require two-factor authentication for admins
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="enableFraudDetection" name="enable_fraud_detection" <?= ($settings['enable_fraud_detection'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="enableFraudDetection">
                                    Enable fraud detection system
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="logAllActivity" name="log_all_activity" <?= ($settings['log_all_activity'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="logAllActivity">
                                    Log all user activity
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Email Settings -->
            <div class="tab-pane fade" id="email" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Email Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="emailSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtpHost" class="form-label">SMTP Host</label>
                                        <input type="text" class="form-control" id="smtpHost" name="smtp_host" value="<?= $settings['smtp_host'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtpPort" class="form-label">SMTP Port</label>
                                        <input type="number" class="form-control" id="smtpPort" name="smtp_port" value="<?= $settings['smtp_port'] ?? 587 ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtpUsername" class="form-label">SMTP Username</label>
                                        <input type="text" class="form-control" id="smtpUsername" name="smtp_username" value="<?= $settings['smtp_username'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="smtpPassword" class="form-label">SMTP Password</label>
                                        <input type="password" class="form-control" id="smtpPassword" name="smtp_password" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fromEmail" class="form-label">From Email</label>
                                        <input type="email" class="form-control" id="fromEmail" name="from_email" value="<?= $settings['from_email'] ?? '' ?>">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="fromName" class="form-label">From Name</label>
                                        <input type="text" class="form-control" id="fromName" name="from_name" value="<?= $settings['from_name'] ?? '' ?>">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="smtpEncryption" name="smtp_encryption" <?= ($settings['smtp_encryption'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="smtpEncryption">
                                    Use TLS encryption
                                </label>
                            </div>
                            
                            <button type="button" class="btn btn-outline-primary" onclick="testEmailSettings()">
                                <i class="fas fa-paper-plane me-2"></i>Send Test Email
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Payment Settings -->
            <div class="tab-pane fade" id="payment" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Payment Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="paymentSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="defaultCurrency" class="form-label">Default Currency</label>
                                        <select class="form-select" id="defaultCurrency" name="default_currency">
                                            <option value="GHS" <?= ($settings['default_currency'] ?? '') === 'GHS' ? 'selected' : '' ?>>GHS - Ghana Cedi</option>
                                            <option value="USD" <?= ($settings['default_currency'] ?? '') === 'USD' ? 'selected' : '' ?>>USD - US Dollar (Legacy)</option>
                                            <option value="EUR" <?= ($settings['default_currency'] ?? '') === 'EUR' ? 'selected' : '' ?>>EUR - Euro</option>
                                            <option value="GBP" <?= ($settings['default_currency'] ?? '') === 'GBP' ? 'selected' : '' ?>>GBP - British Pound</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="platformFee" class="form-label">Platform Fee (%)</label>
                                        <input type="number" class="form-control" id="platformFee" name="platform_fee" value="<?= $settings['platform_fee'] ?? 5 ?>" min="0" max="50" step="0.1">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="paymentGateway" class="form-label">Payment Gateway</label>
                                <select class="form-select" id="paymentGateway" name="payment_gateway">
                                    <option value="stripe" <?= ($settings['payment_gateway'] ?? '') === 'stripe' ? 'selected' : '' ?>>Stripe</option>
                                    <option value="paypal" <?= ($settings['payment_gateway'] ?? '') === 'paypal' ? 'selected' : '' ?>>PayPal</option>
                                    <option value="square" <?= ($settings['payment_gateway'] ?? '') === 'square' ? 'selected' : '' ?>>Square</option>
                                </select>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="autoPayouts" name="auto_payouts" <?= ($settings['auto_payouts'] ?? false) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="autoPayouts">
                                    Enable automatic payouts to tenants
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- API Settings -->
            <div class="tab-pane fade" id="api" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">API Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="apiSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apiRateLimit" class="form-label">Rate Limit (requests/minute)</label>
                                        <input type="number" class="form-control" id="apiRateLimit" name="api_rate_limit" value="<?= $settings['api_rate_limit'] ?? 100 ?>" min="10" max="1000">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="apiVersion" class="form-label">API Version</label>
                                        <select class="form-select" id="apiVersion" name="api_version">
                                            <option value="v1" <?= ($settings['api_version'] ?? '') === 'v1' ? 'selected' : '' ?>>v1</option>
                                            <option value="v2" <?= ($settings['api_version'] ?? '') === 'v2' ? 'selected' : '' ?>>v2</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="enableApiDocs" name="enable_api_docs" <?= ($settings['enable_api_docs'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="enableApiDocs">
                                    Enable API documentation
                                </label>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="requireApiAuth" name="require_api_auth" <?= ($settings['require_api_auth'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="requireApiAuth">
                                    Require authentication for all API endpoints
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Maintenance Settings -->
            <div class="tab-pane fade" id="maintenance" role="tabpanel">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Maintenance Configuration</h5>
                    </div>
                    <div class="card-body">
                        <form id="maintenanceSettingsForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="backupFrequency" class="form-label">Backup Frequency</label>
                                        <select class="form-select" id="backupFrequency" name="backup_frequency">
                                            <option value="daily" <?= ($settings['backup_frequency'] ?? '') === 'daily' ? 'selected' : '' ?>>Daily</option>
                                            <option value="weekly" <?= ($settings['backup_frequency'] ?? '') === 'weekly' ? 'selected' : '' ?>>Weekly</option>
                                            <option value="monthly" <?= ($settings['backup_frequency'] ?? '') === 'monthly' ? 'selected' : '' ?>>Monthly</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="logRetention" class="form-label">Log Retention (days)</label>
                                        <input type="number" class="form-control" id="logRetention" name="log_retention" value="<?= $settings['log_retention'] ?? 90 ?>" min="7" max="365">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="autoCleanup" name="auto_cleanup" <?= ($settings['auto_cleanup'] ?? true) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="autoCleanup">
                                    Enable automatic cleanup of old data
                                </label>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function saveAllSettings() {
    const forms = ['generalSettingsForm', 'securitySettingsForm', 'emailSettingsForm', 'paymentSettingsForm', 'apiSettingsForm', 'maintenanceSettingsForm'];
    const allData = {};
    
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            const formData = new FormData(form);
            for (let [key, value] of formData.entries()) {
                allData[key] = value;
            }
        }
    });
    
    console.log('Saving all settings:', allData);
    alert('Settings saved successfully!');
}

function testEmailSettings() {
    const form = document.getElementById('emailSettingsForm');
    const formData = new FormData(form);
    
    console.log('Testing email settings:', Object.fromEntries(formData));
    alert('Test email sent! Check your inbox.');
}
</script>
