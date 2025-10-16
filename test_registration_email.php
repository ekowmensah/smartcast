<?php
// Include composer autoloader and application
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Core/Application.php';

use SmartCast\Services\EmailService;

echo "Testing Registration Email...\n\n";

try {
    $emailService = new EmailService();
    
    // Test data similar to what registration would send
    $tenantData = [
        'name' => 'Test Organization',
        'email' => 'test@example.com',
        'phone' => '+233123456789',
        'plan' => 'Basic Plan',
        'tenant_id' => 999,
        'user_id' => 999
    ];
    
    echo "📧 Sending new tenant notification...\n";
    $result = $emailService->sendNewTenantNotificationToSuperAdmin($tenantData);
    
    if (is_array($result) && isset($result['success'])) {
        if ($result['success']) {
            echo "✅ Registration email sent successfully!\n";
            if (isset($result['message'])) {
                echo "📨 Message: " . $result['message'] . "\n";
            }
        } else {
            echo "❌ Registration email failed!\n";
            if (isset($result['error'])) {
                echo "📨 Error: " . $result['error'] . "\n";
            } elseif (isset($result['message'])) {
                echo "📨 Error: " . $result['message'] . "\n";
            }
        }
    } else {
        echo "❌ Unexpected response format!\n";
        echo "📨 Response: " . print_r($result, true) . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing registration email: " . $e->getMessage() . "\n";
    echo "📨 Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\nNote: If email failed, you may need to configure SMTP settings in .env file\n";
echo "Check .env.example for required email configuration.\n";
echo "\nDone!\n";
?>
