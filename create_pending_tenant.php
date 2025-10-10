<?php
/**
 * Create Pending Tenant for Testing Approval System
 */

require_once __DIR__ . '/config/config.php';

echo "=== Creating Pending Tenant for Approval Testing ===\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    $pdo->beginTransaction();
    
    // Create a pending tenant (verified = 0)
    echo "1. Creating pending tenant...\n";
    $stmt = $pdo->prepare("
        INSERT INTO tenants (name, email, phone, website, plan, active, verified, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        'Pending Organization ' . date('His'),
        'pending' . date('His') . '@example.com',
        '+233501234567',
        'https://pending-org.com',
        'basic',
        1,        // Active but not verified
        0,        // NOT VERIFIED - needs approval
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    $tenantId = $pdo->lastInsertId();
    echo "✅ Created pending tenant ID: $tenantId\n";
    
    // Create user for this tenant
    echo "2. Creating user for pending tenant...\n";
    $stmt = $pdo->prepare("
        INSERT INTO users (tenant_id, email, password_hash, role, active, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $tenantId,
        'pending' . date('His') . '@example.com',
        password_hash('password123', PASSWORD_DEFAULT),
        'owner',
        1,
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    
    $userId = $pdo->lastInsertId();
    echo "✅ Created user ID: $userId\n";
    
    // Create tenant balance (empty for new tenant)
    echo "3. Creating tenant balance...\n";
    $stmt = $pdo->prepare("
        INSERT INTO tenant_balances (tenant_id, available, pending, total_earned, total_paid, created_at, updated_at)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $tenantId,
        0.00,
        0.00,
        0.00,
        0.00,
        date('Y-m-d H:i:s'),
        date('Y-m-d H:i:s')
    ]);
    echo "✅ Created tenant balance\n";
    
    $pdo->commit();
    
    echo "\n=== Pending Tenant Created Successfully ===\n";
    echo "Tenant ID: $tenantId\n";
    echo "Email: pending" . date('His') . "@example.com\n";
    echo "Password: password123\n";
    echo "Status: PENDING APPROVAL (verified = 0)\n";
    echo "\nThis tenant will:\n";
    echo "❌ NOT be able to login (pending approval)\n";
    echo "⚠️  Show as 'Pending Approval' in SuperAdmin tenants list\n";
    echo "✅ Have Approve/Reject actions available to SuperAdmin\n";
    echo "\nTo test:\n";
    echo "1. Try logging in with the credentials above (should be blocked)\n";
    echo "2. Login as SuperAdmin and visit /superadmin/tenants\n";
    echo "3. Use Approve/Reject actions on this tenant\n";
    
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollback();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
