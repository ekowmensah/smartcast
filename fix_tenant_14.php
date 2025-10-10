<?php
/**
 * Fix Tenant 14 - Create Missing Balance Record
 */

require_once __DIR__ . '/config/config.php';

echo "=== Fixing Tenant 14 Issue ===\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Check if tenant 14 exists
    $stmt = $pdo->prepare("SELECT id, name, email FROM tenants WHERE id = 14");
    $stmt->execute();
    $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$tenant) {
        echo "❌ Tenant 14 does not exist. Creating it...\n";
        
        $stmt = $pdo->prepare("
            INSERT INTO tenants (id, name, email, phone, website, plan, active, verified, created_at, updated_at)
            VALUES (14, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'Test Organizer 14',
            'test14@organizer.com',
            '+233501234567',
            'https://test14-organizer.com',
            'basic',
            1,
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        echo "✅ Created tenant 14\n";
    } else {
        echo "✅ Tenant 14 exists: {$tenant['name']} ({$tenant['email']})\n";
    }
    
    // Check if tenant balance exists
    $stmt = $pdo->prepare("SELECT * FROM tenant_balances WHERE tenant_id = 14");
    $stmt->execute();
    $balance = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$balance) {
        echo "Creating missing tenant balance for tenant 14...\n";
        $stmt = $pdo->prepare("
            INSERT INTO tenant_balances (tenant_id, available, pending, total_earned, total_paid, created_at, updated_at)
            VALUES (14, 125.50, 0.00, 200.00, 74.50, ?, ?)
        ");
        $stmt->execute([
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        echo "✅ Created tenant balance for tenant 14\n";
    } else {
        echo "✅ Tenant balance already exists for tenant 14\n";
        echo "   Available: $" . number_format($balance['available'], 2) . "\n";
        echo "   Total Earned: $" . number_format($balance['total_earned'], 2) . "\n";
    }
    
    // Check if user exists for tenant 14
    $stmt = $pdo->prepare("SELECT id, email FROM users WHERE tenant_id = 14");
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        echo "Creating user for tenant 14...\n";
        $stmt = $pdo->prepare("
            INSERT INTO users (tenant_id, email, password_hash, role, active, created_at, updated_at)
            VALUES (14, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'test14@organizer.com',
            password_hash('password123', PASSWORD_DEFAULT),
            'owner',
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        echo "✅ Created user for tenant 14\n";
        echo "   Login: test14@organizer.com / password123\n";
    } else {
        echo "✅ User exists for tenant 14: {$user['email']}\n";
    }
    
    echo "\n=== Tenant 14 Issue Fixed ===\n";
    echo "You can now access the organizer dashboard without errors!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
