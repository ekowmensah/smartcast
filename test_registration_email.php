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
    
    if ($result['success']) {
        echo "✅ Registration email sent successfully!\n";
        echo "📨 Message: " . $result['message'] . "\n";
    } else {
        echo "❌ Registration email failed!\n";
        echo "📨 Error: " . $result['message'] . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error testing registration email: " . $e->getMessage() . "\n";
}

echo "\nDone!\n";
?>
