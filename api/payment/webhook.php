<?php

require_once __DIR__ . '/../../includes/autoloader.php';

use SmartCast\Services\PaymentService;

// Set JSON response header
header('Content-Type: application/json');

try {
    // Get the request method and provider
    $method = $_SERVER['REQUEST_METHOD'];
    $provider = $_GET['provider'] ?? 'paystack';
    
    if ($method !== 'POST') {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        exit;
    }
    
    // Get the raw POST data
    $input = file_get_contents('php://input');
    $payload = json_decode($input, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid JSON payload']);
        exit;
    }
    
    // Get signature from headers
    $signature = null;
    if ($provider === 'paystack') {
        $signature = $_SERVER['HTTP_X_PAYSTACK_SIGNATURE'] ?? null;
    }
    
    // Log the webhook for debugging
    error_log("Webhook received - Provider: {$provider}, Event: " . ($payload['event'] ?? 'unknown'));
    error_log("Webhook payload: " . $input);
    
    // Process the webhook
    $paymentService = new PaymentService();
    $result = $paymentService->processWebhook($provider, $payload, $signature);
    
    if ($result['success']) {
        http_response_code(200);
        echo json_encode([
            'status' => 'success',
            'message' => $result['message'],
            'action' => $result['action'] ?? 'processed'
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => $result['message'],
            'error_code' => $result['error_code'] ?? 'WEBHOOK_ERROR'
        ]);
    }
    
} catch (Exception $e) {
    error_log("Webhook processing exception: " . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => 'Internal server error',
        'error_code' => 'INTERNAL_ERROR'
    ]);
}
