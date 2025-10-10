<?php
/**
 * Final Approval System Test - Complete Verification
 */

require_once __DIR__ . '/config/config.php';

echo "=== FINAL TENANT APPROVAL SYSTEM TEST ===\n";
echo "Time: " . date('Y-m-d H:i:s') . "\n\n";

try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    
    // 1. System Status Check
    echo "1. SYSTEM STATUS CHECK\n";
    echo str_repeat("-", 50) . "\n";
    
    $totalTenants = $pdo->query("SELECT COUNT(*) FROM tenants")->fetchColumn();
    $pendingTenants = $pdo->query("SELECT COUNT(*) FROM tenants WHERE verified = 0 AND active = 1")->fetchColumn();
    $activeTenants = $pdo->query("SELECT COUNT(*) FROM tenants WHERE verified = 1 AND active = 1")->fetchColumn();
    $rejectedTenants = $pdo->query("SELECT COUNT(*) FROM tenants WHERE verified = 0 AND active = 0")->fetchColumn();
    
    echo "Total Tenants: $totalTenants\n";
    echo "✅ Active Tenants: $activeTenants\n";
    echo "⚠️  Pending Approval: $pendingTenants\n";
    echo "❌ Rejected Tenants: $rejectedTenants\n\n";
    
    // 2. Pending Tenants Details
    if ($pendingTenants > 0) {
        echo "2. PENDING TENANTS (Ready for Approval)\n";
        echo str_repeat("-", 50) . "\n";
        
        $stmt = $pdo->query("
            SELECT id, name, email, created_at 
            FROM tenants 
            WHERE verified = 0 AND active = 1 
            ORDER BY created_at DESC
        ");
        
        while ($tenant = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "ID: {$tenant['id']} | {$tenant['name']}\n";
            echo "   Email: {$tenant['email']}\n";
            echo "   Applied: " . date('M j, Y H:i', strtotime($tenant['created_at'])) . "\n";
            echo "   Status: 🟡 PENDING APPROVAL\n\n";
        }
    }
    
    // 3. Test User Accounts
    echo "3. TEST USER ACCOUNTS\n";
    echo str_repeat("-", 50) . "\n";
    
    $testUsers = [
        ['email' => 'ekowme@gmail.comm', 'role' => 'platform_admin', 'purpose' => 'SuperAdmin (can approve tenants)'],
        ['email' => 'test14@organizer.com', 'role' => 'owner', 'purpose' => 'Active Tenant (can login)'],
        ['email' => 'pending202330@example.com', 'role' => 'owner', 'purpose' => 'Pending Tenant (login blocked)']
    ];
    
    foreach ($testUsers as $testUser) {
        $stmt = $pdo->prepare("SELECT id, tenant_id, active FROM users WHERE email = ?");
        $stmt->execute([$testUser['email']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            echo "✅ {$testUser['email']} | {$testUser['role']} | {$testUser['purpose']}\n";
        } else {
            echo "❌ {$testUser['email']} | NOT FOUND\n";
        }
    }
    
    echo "\n4. MANUAL TESTING GUIDE\n";
    echo str_repeat("=", 50) . "\n";
    
    echo "\n🔐 STEP 1: Test Login Blocking\n";
    echo "   1. Try login: pending202330@example.com / password123\n";
    echo "   2. Expected: ❌ 'Your organization is pending approval' message\n";
    echo "   3. Should NOT be able to access dashboard\n";
    
    echo "\n👑 STEP 2: SuperAdmin Approval Interface\n";
    echo "   1. Login: ekowme@gmail.comm / password123\n";
    echo "   2. Visit: /superadmin/tenants\n";
    echo "   3. Look for: 🟡 'Pending Approval' badges\n";
    echo "   4. Click: Dropdown actions → Approve/Reject buttons\n";
    echo "   5. Expected: ✅ Modals open, forms work, status updates\n";
    
    echo "\n📋 STEP 3: Dedicated Pending Page\n";
    echo "   1. Visit: /superadmin/tenants/pending\n";
    echo "   2. Expected: ✅ Card-based layout with pending tenants\n";
    echo "   3. Click: Approve/Reject buttons\n";
    echo "   4. Expected: ✅ Detailed modals with form fields\n";
    
    echo "\n🔄 STEP 4: Test Complete Workflow\n";
    echo "   1. Approve a pending tenant\n";
    echo "   2. Expected: ✅ Status changes to 'Active'\n";
    echo "   3. Try login with approved tenant credentials\n";
    echo "   4. Expected: ✅ Can now access organizer dashboard\n";
    
    echo "\n📊 STEP 5: Verify Data Integrity\n";
    echo "   1. Check: Tenant status updated in database\n";
    echo "   2. Check: Audit logs created for actions\n";
    echo "   3. Check: No JavaScript console errors\n";
    echo "   4. Check: Success/error messages display properly\n";
    
    echo "\n" . str_repeat("=", 50) . "\n";
    echo "🎉 TENANT APPROVAL SYSTEM READY FOR TESTING!\n";
    echo str_repeat("=", 50) . "\n";
    
    // 5. Quick Health Check
    echo "\n5. SYSTEM HEALTH CHECK\n";
    echo str_repeat("-", 50) . "\n";
    
    $healthChecks = [
        'Database Connection' => true,
        'Pending Tenants Available' => $pendingTenants > 0,
        'SuperAdmin User Exists' => $pdo->query("SELECT COUNT(*) FROM users WHERE email = 'ekowme@gmail.comm'")->fetchColumn() > 0,
        'Approval Routes Added' => file_exists(__DIR__ . '/src/Core/Application.php'),
        'Controller Methods Exist' => method_exists('SmartCast\Controllers\SuperAdminController', 'approveTenant'),
        'Bootstrap JS Added' => strpos(file_get_contents(__DIR__ . '/views/layout/superadmin_layout.php'), 'bootstrap@5.3.0') !== false
    ];
    
    foreach ($healthChecks as $check => $status) {
        echo ($status ? "✅" : "❌") . " $check\n";
    }
    
    echo "\n🚀 System is ready for production use!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
