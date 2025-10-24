<?php
/**
 * USSD Endpoint Test Script
 * Tests if the USSD callback URL is accessible and working
 */

// Get the base URL from config
require_once __DIR__ . '/config/config.php';

// USSD callback URL
$ussdCallbackUrl = APP_URL . '/api/ussd/callback';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>USSD Endpoint Test - SmartCast</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-mobile-alt me-2"></i>
                            USSD Endpoint Test
                        </h4>
                    </div>
                    <div class="card-body">
                        
                        <!-- URL Information -->
                        <div class="alert alert-info">
                            <h5><i class="fas fa-info-circle me-2"></i>Your USSD Callback URLs</h5>
                            <p class="mb-2">
                                <strong>Service Interaction URL:</strong><br>
                                <code id="callbackUrl"><?= $ussdCallbackUrl ?></code>
                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('callbackUrl')">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </p>
                            <p class="mb-0">
                                <strong>Fulfilment URL:</strong><br>
                                <code id="fulfilmentUrl"><?= $ussdCallbackUrl ?></code>
                                <button class="btn btn-sm btn-outline-primary ms-2" onclick="copyToClipboard('fulfilmentUrl')">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </p>
                        </div>

                        <!-- Test Form -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Test USSD Request</h5>
                            </div>
                            <div class="card-body">
                                <form id="testForm">
                                    <div class="mb-3">
                                        <label class="form-label">Session ID</label>
                                        <input type="text" class="form-control" id="sessionId" value="test_<?= time() ?>" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Service Code</label>
                                        <input type="text" class="form-control" id="serviceCode" value="*920*01#" required>
                                        <small class="text-muted">Format: *920*XX# where XX is tenant code</small>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" id="phoneNumber" value="233545644749" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">User Input (Text)</label>
                                        <input type="text" class="form-control" id="text" value="" placeholder="Leave empty for first request">
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-paper-plane me-2"></i>Send Test Request
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Response Display -->
                        <div id="responseSection" style="display: none;">
                            <div class="card">
                                <div class="card-header bg-success text-white">
                                    <h5 class="mb-0">
                                        <i class="fas fa-check-circle me-2"></i>Response
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <pre id="response" class="bg-light p-3 rounded"></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Error Display -->
                        <div id="errorSection" style="display: none;">
                            <div class="alert alert-danger">
                                <h5><i class="fas fa-exclamation-triangle me-2"></i>Error</h5>
                                <pre id="error" class="mb-0"></pre>
                            </div>
                        </div>

                        <!-- Checklist -->
                        <div class="card mt-4">
                            <div class="card-header">
                                <h5 class="mb-0">Pre-Deployment Checklist</h5>
                            </div>
                            <div class="card-body">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="check1">
                                    <label class="form-check-label" for="check1">
                                        Database migration completed
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="check2">
                                    <label class="form-check-label" for="check2">
                                        At least one tenant configured with USSD code
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="check3">
                                    <label class="form-check-label" for="check3">
                                        USSD endpoint returns valid response
                                    </label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" id="check4">
                                    <label class="form-check-label" for="check4">
                                        SSL certificate active (HTTPS)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="check5">
                                    <label class="form-check-label" for="check5">
                                        Hubtel USSD codes registered
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(elementId) {
        const text = document.getElementById(elementId).textContent;
        navigator.clipboard.writeText(text).then(() => {
            alert('Copied to clipboard!');
        });
    }

    document.getElementById('testForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const sessionId = document.getElementById('sessionId').value;
        const serviceCode = document.getElementById('serviceCode').value;
        const phoneNumber = document.getElementById('phoneNumber').value;
        const text = document.getElementById('text').value;
        
        // Hide previous results
        document.getElementById('responseSection').style.display = 'none';
        document.getElementById('errorSection').style.display = 'none';
        
        try {
            const formData = new FormData();
            formData.append('sessionId', sessionId);
            formData.append('serviceCode', serviceCode);
            formData.append('phoneNumber', phoneNumber);
            formData.append('text', text);
            
            const response = await fetch('<?= $ussdCallbackUrl ?>', {
                method: 'POST',
                body: formData
            });
            
            const responseText = await response.text();
            
            // Remove CON/END prefix for cleaner display
            const cleanResponse = responseText.replace(/^(CON|END)\s+/, '');
            
            // Display clean response without prefix
            document.getElementById('response').textContent = cleanResponse;
            document.getElementById('responseSection').style.display = 'block';
            
            // Check the checkbox if successful
            if (responseText.startsWith('CON') || responseText.startsWith('END')) {
                document.getElementById('check3').checked = true;
            }
            
        } catch (error) {
            document.getElementById('error').textContent = error.message;
            document.getElementById('errorSection').style.display = 'block';
        }
    });
    </script>
</body>
</html>
