<?php
/**
 * Create SuperAdmin User
 */

require_once __DIR__ . '/config/config.php';

echo "=== Creating SuperAdmin User ===\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // Check if SuperAdmin already exists
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? AND role = 'platform_admin'");
    $stmt->execute(['ekowme@gmail.comm']);
    $existing = $stmt->fetch();
    
    if ($existing) {
        echo "✅ SuperAdmin user already exists (ID: {$existing['id']})\n";
    } else {
        echo "Creating SuperAdmin user...\n";
        
        $stmt = $pdo->prepare("
            INSERT INTO users (tenant_id, email, password_hash, role, active, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        $stmt->execute([
            null, // Platform admin has no tenant
            'ekowme@gmail.comm',
            password_hash('password123', PASSWORD_DEFAULT),
            'platform_admin',
            1,
            date('Y-m-d H:i:s'),
            date('Y-m-d H:i:s')
        ]);
        
        $userId = $pdo->lastInsertId();
        echo "✅ SuperAdmin user created (ID: $userId)\n";
    }
    
    echo "\nSuperAdmin Login Credentials:\n";
    echo "Email: ekowme@gmail.comm\n";
    echo "Password: password123\n";
    echo "Role: platform_admin\n";
    echo "\nAccess: /superadmin (full platform administration)\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
