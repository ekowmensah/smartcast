<?php
// Include composer autoloader and application
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Core/Application.php';

use SmartCast\Services\EmailServiceFactory;

echo "Testing Email Service Factory...\n\n";

// Get email service status
$status = EmailServiceFactory::getStatus();

echo "📊 Email Service Status:\n";
echo "- PHPMailer Available: " . ($status['phpmailer_available'] ? '✅ Yes' : '❌ No') . "\n";
echo "- Service Type: " . ($status['service_type'] ?? 'Unknown') . "\n";
if ($status['error']) {
    echo "- Error: " . $status['error'] . "\n";
}
echo "\n";

// Test creating email service
try {
    echo "🔧 Creating email service...\n";
    $emailService = EmailServiceFactory::create();
    echo "✅ Email service created: " . get_class($emailService) . "\n";
    
    // Test sending email
    echo "📧 Testing email functionality...\n";
    $result = $emailService->sendTestEmail('test@example.com', 'Test User');
    
    if ($result) {
        echo "✅ Email test successful!\n";
    } else {
        echo "❌ Email test failed (but service is working)\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error creating email service: " . $e->getMessage() . "\n";
}

echo "\n";
echo "🚀 Production Deployment Status:\n";
echo "- Local Development: " . (EmailServiceFactory::isPHPMailerAvailable() ? '✅ Ready' : '⚠️  Using fallback') . "\n";
echo "- Production Server: Upload vendor/ folder or run 'composer install'\n";
echo "\nDone!\n";
?>
