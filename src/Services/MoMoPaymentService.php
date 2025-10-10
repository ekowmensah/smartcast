<?php

namespace SmartCast\Services;

/**
 * Mobile Money Payment Service (Demo Implementation)
 * This is a demo implementation that simulates MoMo payment flow
 * In production, this would integrate with actual MoMo APIs like MTN, Vodafone, AirtelTigo etc.
 */
class MoMoPaymentService
{
    private $apiKey;
    private $apiSecret;
    private $baseUrl;
    
    public function __construct()
    {
        // Demo configuration - in production these would be from config/environment
        $this->apiKey = 'demo_api_key_12345';
        $this->apiSecret = 'demo_secret_67890';
        $this->baseUrl = 'https://demo-momo-api.example.com/v1';
    }
    
    /**
     * Initiate a mobile money payment
     * 
     * @param array $paymentData
     * @return array Payment response
     */
    public function initiatePayment($paymentData)
    {
        // Validate required fields
        $required = ['amount', 'phone', 'reference', 'description'];
        foreach ($required as $field) {
            if (empty($paymentData[$field])) {
                throw new \Exception("Missing required field: {$field}");
            }
        }
        
        // Simulate payment initiation
        $transactionId = $this->generateTransactionId();
        
        // In a real implementation, this would make an HTTP request to MoMo API
        $response = $this->simulateMoMoApiCall('POST', '/payments/initiate', [
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'GHS',
            'phone' => $this->formatPhoneNumber($paymentData['phone']),
            'reference' => $paymentData['reference'],
            'description' => $paymentData['description'],
            'callback_url' => $paymentData['callback_url'] ?? null,
            'metadata' => $paymentData['metadata'] ?? []
        ]);
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'payment_reference' => $response['payment_reference'],
            'status' => 'pending',
            'message' => 'Payment initiated. Please check your phone for the payment prompt.',
            'expires_at' => date('Y-m-d H:i:s', strtotime('+5 minutes'))
        ];
    }
    
    /**
     * Check payment status
     * 
     * @param string $transactionId
     * @return array Status response
     */
    public function checkPaymentStatus($transactionId)
    {
        // Validate transaction ID
        if (empty($transactionId)) {
            throw new \Exception("Transaction ID is required");
        }
        
        // Simulate status checking
        try {
            $response = $this->simulateMoMoApiCall('GET', "/payments/{$transactionId}/status");
        } catch (\Exception $e) {
            error_log("MoMo API simulation error: " . $e->getMessage());
            throw new \Exception("Payment service temporarily unavailable");
        }
        
        // Demo: Randomly simulate different payment states
        $statuses = ['pending', 'success', 'failed', 'expired'];
        $weights = [30, 60, 5, 5]; // 60% success rate for demo
        
        $status = $this->getWeightedRandomStatus($statuses, $weights);
        
        $result = [
            'transaction_id' => $transactionId,
            'status' => $status,
            'amount' => $response['amount'] ?? 0,
            'phone' => $response['phone'] ?? '',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        switch ($status) {
            case 'success':
                $result['message'] = 'Payment completed successfully';
                $result['receipt_number'] = 'MP' . strtoupper(substr(md5($transactionId . time()), 0, 8));
                break;
                
            case 'failed':
                $result['message'] = 'Payment failed. Please try again.';
                $result['error_code'] = 'INSUFFICIENT_FUNDS';
                break;
                
            case 'expired':
                $result['message'] = 'Payment request expired. Please initiate a new payment.';
                break;
                
            default:
                $result['message'] = 'Payment is being processed. Please wait...';
        }
        
        return $result;
    }
    
    /**
     * Verify payment callback
     * 
     * @param array $callbackData
     * @return array Verification result
     */
    public function verifyCallback($callbackData)
    {
        // In production, this would verify the callback signature/hash
        $expectedSignature = hash_hmac('sha256', json_encode($callbackData), $this->apiSecret);
        $receivedSignature = $callbackData['signature'] ?? '';
        
        return [
            'valid' => true, // For demo purposes, always valid
            'transaction_id' => $callbackData['transaction_id'] ?? '',
            'status' => $callbackData['status'] ?? 'unknown',
            'amount' => $callbackData['amount'] ?? 0
        ];
    }
    
    /**
     * Get supported mobile networks
     * 
     * @return array List of supported networks
     */
    public function getSupportedNetworks()
    {
        return [
            'mtn' => [
                'name' => 'MTN Mobile Money',
                'prefixes' => ['024', '025', '053', '054', '055', '059'],
                'logo' => '/assets/images/mtn-logo.png'
            ],
            'vodafone' => [
                'name' => 'Vodafone Cash',
                'prefixes' => ['020', '050'],
                'logo' => '/assets/images/vodafone-logo.png'
            ],
            'airteltigo' => [
                'name' => 'AirtelTigo Money',
                'prefixes' => ['026', '027', '056', '057'],
                'logo' => '/assets/images/airteltigo-logo.png'
            ]
        ];
    }
    
    /**
     * Detect network from phone number
     * 
     * @param string $phone
     * @return string|null Network code
     */
    public function detectNetwork($phone)
    {
        $phone = $this->formatPhoneNumber($phone);
        $prefix = substr($phone, -9, 3); // Get first 3 digits after country code
        
        foreach ($this->getSupportedNetworks() as $code => $network) {
            if (in_array($prefix, $network['prefixes'])) {
                return $code;
            }
        }
        
        return null;
    }
    
    /**
     * Format phone number to standard format
     * 
     * @param string $phone
     * @return string Formatted phone number
     */
    private function formatPhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Handle Ghana phone numbers
        if (strlen($phone) === 10 && substr($phone, 0, 1) === '0') {
            // Convert 0XXXXXXXXX to 233XXXXXXXXX
            $phone = '233' . substr($phone, 1);
        } elseif (strlen($phone) === 9) {
            // Convert XXXXXXXXX to 233XXXXXXXXX
            $phone = '233' . $phone;
        }
        
        return $phone;
    }
    
    /**
     * Generate unique transaction ID
     * 
     * @return string Transaction ID
     */
    private function generateTransactionId()
    {
        return 'TXN_' . strtoupper(uniqid()) . '_' . time();
    }
    
    /**
     * Simulate MoMo API call (for demo purposes)
     * 
     * @param string $method
     * @param string $endpoint
     * @param array $data
     * @return array Simulated response
     */
    private function simulateMoMoApiCall($method, $endpoint, $data = [])
    {
        // Simulate API delay
        usleep(500000); // 0.5 second delay
        
        // Log the API call for debugging
        error_log("MoMo API Call: {$method} {$endpoint} - " . json_encode($data));
        
        // Return simulated response
        return [
            'status' => 'success',
            'payment_reference' => 'PAY_' . strtoupper(substr(md5(json_encode($data) . time()), 0, 10)),
            'amount' => $data['amount'] ?? 0,
            'phone' => $data['phone'] ?? '',
            'timestamp' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Get weighted random status for demo
     * 
     * @param array $statuses
     * @param array $weights
     * @return string Selected status
     */
    private function getWeightedRandomStatus($statuses, $weights)
    {
        $totalWeight = array_sum($weights);
        $random = mt_rand(1, $totalWeight);
        
        $currentWeight = 0;
        for ($i = 0; $i < count($statuses); $i++) {
            $currentWeight += $weights[$i];
            if ($random <= $currentWeight) {
                return $statuses[$i];
            }
        }
        
        return $statuses[0]; // Fallback
    }
}
