<?php
// Include composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Include application bootstrap
require_once __DIR__ . '/src/Core/Application.php';

use SmartCast\Services\EmailService;
use SmartCast\Services\EmailServiceSimple;

echo "Testing Email Services...\n\n";

// Test PHPMailer availability
try {
    $emailService = new EmailService();
    echo "✅ PHPMailer EmailService: Available\n";
    
    // Test email sending
    $result = $emailService->sendTestEmail('test@example.com', 'Test User');
    echo "📧 Test Email Result: " . ($result ? 'Success' : 'Failed') . "\n";
    
} catch (Error $e) {
    echo "❌ PHPMailer EmailService: Not Available\n";
    echo "Error: " . $e->getMessage() . "\n";
    
    // Test fallback
    try {
        $simpleEmailService = new EmailServiceSimple();
        echo "✅ Simple EmailService: Available (Fallback)\n";
        
        $result = $simpleEmailService->sendTestEmail('test@example.com', 'Test User');
        echo "📧 Simple Email Result: " . ($result ? 'Success' : 'Failed') . "\n";
        
    } catch (Exception $e2) {
        echo "❌ Simple EmailService: Also Failed\n";
        echo "Error: " . $e2->getMessage() . "\n";
    }
}

echo "\nDone!\n";
?>
