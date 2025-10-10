<?php
/**
 * Fix Session Issue - Create Missing Tenant or Clear Session
 */

require_once __DIR__ . '/config/config.php';

echo "=== Fixing Session Issue ===\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Check if tenant 16 exists
    $stmt = $pdo->prepare("SELECT id, name FROM tenants WHERE id = 16");
    $stmt->execute();
    $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($tenant) {
        echo "✅ Tenant 16 exists: {$tenant['name']}\n";
        
        // Check if tenant_balance exists
        $stmt = $pdo->prepare("SELECT * FROM tenant_balances WHERE tenant_id = 16");
        $stmt->execute();
        $balance = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$balance) {
            echo "Creating missing tenant balance for tenant 16...\n";
            $stmt = $pdo->prepare("
                INSERT INTO tenant_balances (tenant_id, available, pending, total_earned, total_paid, created_at, updated_at)
                VALUES (16, 0.00, 0.00, 0.00, 0.00, NOW(), NOW())
            ");
            $stmt->execute();
            echo "✅ Created tenant balance for tenant 16\n";
        } else {
            echo "✅ Tenant balance already exists for tenant 16\n";
        }
        
    } else {
        echo "❌ Tenant 16 does not exist\n";
        echo "Creating tenant 16...\n";
        
        $pdo->beginTransaction();
        
        // Create tenant 16
        $stmt = $pdo->prepare("
            INSERT INTO tenants (id, name, email, phone, website, plan, active, verified, created_at, updated_at)
            VALUES (16, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'Current Session Tenant',
            'session@tenant.com',
            '+233501234567',
            'https://session-tenant.com',
            'basic',
            1,
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        // Create tenant balance
        $stmt = $pdo->prepare("
            INSERT INTO tenant_balances (tenant_id, available, pending, total_earned, total_paid, created_at, updated_at)
            VALUES (16, 0.00, 0.00, 0.00, 0.00, ?, ?)
        ");
        $stmt->execute([
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        // Create a user for this tenant
        $stmt = $pdo->prepare("
            INSERT INTO users (tenant_id, email, password_hash, role, active, created_at, updated_at)
            VALUES (16, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'session@tenant.com',
            password_hash('password123', PASSWORD_DEFAULT),
            'owner',
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        $pdo->commit();
        
        echo "✅ Created tenant 16 with basic setup\n";
        echo "Login: session@tenant.com / password123\n";
    }
    
    echo "\n=== Session Issue Fixed ===\n";
    echo "You can now access /organizer/financial/payouts\n";
    
} catch (Exception $e) {
    if (isset($pdo)) {
        $pdo->rollback();
    }
    echo "❌ Error: " . $e->getMessage() . "\n";
    
    // Alternative: Clear session by redirecting to logout
    echo "\nAlternative solution: Visit /logout to clear your session, then login with:\n";
    echo "- test@organizer.com / password123 (if you ran the setup script)\n";
    echo "- Or any existing user credentials\n";
    
    exit(1);
}
