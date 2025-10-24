<?php
/**
 * Test Hubtel Service Fulfillment Callback
 * This script tests sending the callback to Hubtel's endpoint
 */

// Test data
$sessionId = 'test_session_' . time();
$orderId = 'test_order_' . time();
$status = 'success';

$callbackUrl = 'https://gs-callback.hubtel.com/callback';

$payload = [
    'SessionId' => $sessionId,
    'OrderId' => $orderId,
    'ServiceStatus' => $status,
    'MetaData' => null
];

echo "=== Hubtel Fulfillment Callback Test ===\n\n";
echo "Callback URL: {$callbackUrl}\n";
echo "Payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n\n";

// Initialize cURL
$ch = curl_init($callbackUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 30);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_VERBOSE, true);

// Capture verbose output
$verbose = fopen('php://temp', 'w+');
curl_setopt($ch, CURLOPT_STDERR, $verbose);

echo "Sending callback...\n\n";

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlError = curl_error($ch);
$curlInfo = curl_getinfo($ch);

// Get verbose output
rewind($verbose);
$verboseLog = stream_get_contents($verbose);
fclose($verbose);

curl_close($ch);

// Display results
echo "=== RESULTS ===\n\n";
echo "HTTP Code: {$httpCode}\n";
echo "Response: {$response}\n\n";

if ($curlError) {
    echo "CURL Error: {$curlError}\n\n";
}

echo "=== CURL INFO ===\n";
echo "Total Time: " . $curlInfo['total_time'] . " seconds\n";
echo "Connect Time: " . $curlInfo['connect_time'] . " seconds\n";
echo "SSL Verify Result: " . $curlInfo['ssl_verify_result'] . "\n";
echo "Primary IP: " . ($curlInfo['primary_ip'] ?? 'N/A') . "\n";
echo "Primary Port: " . ($curlInfo['primary_port'] ?? 'N/A') . "\n\n";

echo "=== VERBOSE LOG ===\n";
echo $verboseLog . "\n";

// Interpretation
echo "\n=== INTERPRETATION ===\n";
if ($httpCode >= 200 && $httpCode < 300) {
    echo "✅ SUCCESS: Callback sent successfully!\n";
} elseif ($httpCode == 0) {
    echo "❌ FAILED: Could not connect to Hubtel. Possible issues:\n";
    echo "   - Firewall blocking outbound HTTPS on port 9055\n";
    echo "   - DNS resolution failed\n";
    echo "   - SSL certificate issue\n";
    echo "   - Server cannot reach Hubtel's network\n";
} elseif ($httpCode >= 400 && $httpCode < 500) {
    echo "⚠️  CLIENT ERROR: Hubtel rejected the request (HTTP {$httpCode})\n";
    echo "   - Check payload format\n";
    echo "   - Verify SessionId and OrderId are valid\n";
} elseif ($httpCode >= 500) {
    echo "⚠️  SERVER ERROR: Hubtel's server error (HTTP {$httpCode})\n";
    echo "   - Try again later\n";
} else {
    echo "⚠️  UNKNOWN: HTTP {$httpCode}\n";
}

echo "\n=== RECOMMENDATIONS ===\n";
echo "1. Check your server's firewall allows outbound HTTPS to gs-callback.hubtel.com:9055\n";
echo "2. Verify your server can resolve gs-callback.hubtel.com\n";
echo "3. Check if your hosting provider blocks port 9055\n";
echo "4. Contact Hubtel support with the SessionId and OrderId from actual transactions\n";
echo "5. Ask Hubtel to check their logs for incoming callbacks from your server IP\n";
