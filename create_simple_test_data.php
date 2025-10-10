<?php
/**
 * Create Simple Test Data for Payouts
 */

require_once __DIR__ . '/config/config.php';

echo "=== Creating Simple Test Data ===\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $pdo->beginTransaction();
    
    // 1. Create tenant
    echo "1. Creating tenant...\n";
    $stmt = $pdo->prepare("
        INSERT INTO tenants (name, email, phone, website, plan, active, verified, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        'Test Organizer',
        'test@organizer.com',
        '+233501234567',
        'https://test-organizer.com',
        'basic',
        1,
        1,
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    $tenantId = $pdo->lastInsertId();
    echo "✅ Created tenant ID: $tenantId\n";
    
    // 2. Create tenant balance
    echo "2. Creating tenant balance...\n";
    $stmt = $pdo->prepare("
        INSERT INTO tenant_balances (tenant_id, available, pending, total_earned, total_paid, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $tenantId,
        125.50,
        0.00,
        200.00,
        74.50,
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    echo "✅ Created tenant balance\n";
    
    // 3. Create sample payouts
    echo "3. Creating sample payouts...\n";
    $payouts = [
        [
            'PO_20251010_001',
            50.00,
            'bank_transfer',
            json_encode(['bank_name' => 'Test Bank', 'account_number' => '1234567890']),
            'success',
            'BT_1728567890_1234',
            '2025-10-08 14:30:00',
            null,
            '2025-10-08 10:15:00'
        ],
        [
            'PO_20251010_002',
            24.50,
            'mobile_money',
            json_encode(['phone_number' => '+233501234567', 'network' => 'MTN']),
            'processing',
            null,
            null,
            null,
            '2025-10-09 16:45:00'
        ],
        [
            'PO_20251010_003',
            15.00,
            'paypal',
            json_encode(['email' => 'test@organizer.com']),
            'failed',
            null,
            null,
            'Invalid PayPal email address',
            '2025-10-07 09:20:00'
        ]
    ];
    
    $stmt = $pdo->prepare("
        INSERT INTO payouts (
            tenant_id, payout_id, amount, payout_method, recipient_details,
            status, provider_reference, processed_at, failure_reason, created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    foreach ($payouts as $payout) {
        $stmt->execute([
            $tenantId,
            $payout[0], // payout_id
            $payout[1], // amount
            $payout[2], // method
            $payout[3], // recipient_details
            $payout[4], // status
            $payout[5], // provider_reference
            $payout[6], // processed_at
            $payout[7], // failure_reason
            $payout[8], // created_at
            date('Y-m-d H:i:s') // updated_at
        ]);
        
        echo "✅ Created payout: {$payout[0]} (${$payout[1]} - {$payout[4]})\n";
    }
    
    $pdo->commit();
    
    echo "\n=== Test Data Created Successfully ===\n";
    echo "Tenant ID: $tenantId\n";
    echo "Available Balance: $125.50\n";
    echo "Payouts Created: 3\n";
    echo "\nNow visit: /organizer/financial/payouts\n";
    echo "Note: You may need to set your session tenant_id to $tenantId\n";
    
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollback();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
