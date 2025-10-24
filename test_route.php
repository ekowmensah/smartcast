<?php
/**
 * Quick route test - Check if USSD routes are registered
 */

require_once __DIR__ . '/bootstrap.php';

use SmartCast\Core\Application;

$app = new Application();

// Get all registered routes
$router = $app->getRouter();

echo "<h2>Registered Routes</h2>";
echo "<pre>";

// Check if router has a method to get routes
if (method_exists($router, 'getRoutes')) {
    $routes = $router->getRoutes();
    print_r($routes);
} else {
    echo "Router doesn't expose routes. Let's test the USSD endpoint directly.\n\n";
}

echo "</pre>";

echo "<h2>Test USSD Endpoint</h2>";
echo "<p>Trying to access: <code>/api/ussd/callback</code></p>";

// Simulate USSD request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/api/ussd/callback';
$_POST['sessionId'] = 'test_' . time();
$_POST['serviceCode'] = '*920*01#';
$_POST['phoneNumber'] = '233545644749';
$_POST['text'] = '';

echo "<h3>Request Details:</h3>";
echo "<pre>";
echo "Method: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "POST Data:\n";
print_r($_POST);
echo "</pre>";

echo "<h3>Response:</h3>";
echo "<pre>";

try {
    ob_start();
    $app->run();
    $output = ob_get_clean();
    echo htmlspecialchars($output);
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace:\n" . $e->getTraceAsString();
}

echo "</pre>";
