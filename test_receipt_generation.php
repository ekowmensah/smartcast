<?php

require_once __DIR__ . '/includes/autoloader.php';

use SmartCast\Models\VoteReceipt;
use SmartCast\Core\Database;

echo "<h1>Receipt Generation & Verification Test</h1>\n";

try {
    $db = Database::getInstance();
    $receiptModel = new VoteReceipt();
    
    // Test with your successful transaction ID
    $testTransactionId = 259;
    
    echo "<h2>1. Check Existing Receipt</h2>\n";
    $existingReceipt = $receiptModel->getReceiptByTransaction($testTransactionId);
    
    if ($existingReceipt) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
        echo "<h4>✅ Receipt Already Exists:</h4>\n";
        echo "<ul>\n";
        echo "<li><strong>Receipt Code:</strong> {$existingReceipt['short_code']}</li>\n";
        echo "<li><strong>Transaction ID:</strong> {$existingReceipt['transaction_id']}</li>\n";
        echo "<li><strong>Created:</strong> {$existingReceipt['created_at']}</li>\n";
        echo "</ul>\n";
        echo "</div>\n";
    } else {
        echo "<div style='background: #fff3cd; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
        echo "<h4>⚠️ No Receipt Found</h4>\n";
        echo "<p>Generating receipt for transaction {$testTransactionId}...</p>\n";
        echo "</div>\n";
        
        // Generate receipt
        try {
            $newReceipt = $receiptModel->generateReceipt($testTransactionId);
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
            echo "<h4>✅ Receipt Generated Successfully:</h4>\n";
            echo "<ul>\n";
            echo "<li><strong>Receipt Code:</strong> {$newReceipt['short_code']}</li>\n";
            echo "<li><strong>Receipt ID:</strong> {$newReceipt['id']}</li>\n";
            echo "<li><strong>Public Hash:</strong> " . substr($newReceipt['public_hash'], 0, 20) . "...</li>\n";
            echo "</ul>\n";
            echo "</div>\n";
            $existingReceipt = $newReceipt;
        } catch (\Exception $e) {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
            echo "<h4>❌ Receipt Generation Failed:</h4>\n";
            echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>\n";
            echo "</div>\n";
        }
    }
    
    if ($existingReceipt) {
        $shortCode = $existingReceipt['short_code'];
        
        echo "<h2>2. Test Receipt Verification</h2>\n";
        
        // Test verification
        $verification = $receiptModel->verifyReceipt($shortCode);
        
        if ($verification['valid']) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
            echo "<h4>✅ Receipt Verification Successful:</h4>\n";
            echo "<ul>\n";
            echo "<li><strong>Receipt Code:</strong> {$shortCode}</li>\n";
            echo "<li><strong>Transaction ID:</strong> {$verification['transaction']['id']}</li>\n";
            echo "<li><strong>Event:</strong> {$verification['event']['name']}</li>\n";
            echo "<li><strong>Contestant:</strong> {$verification['contestant']['name']}</li>\n";
            echo "<li><strong>Amount:</strong> {$verification['transaction']['amount']}</li>\n";
            echo "<li><strong>Status:</strong> {$verification['transaction']['status']}</li>\n";
            echo "</ul>\n";
            echo "</div>\n";
            
            echo "<h2>3. Receipt Verification Links</h2>\n";
            echo "<div style='background: #cce5ff; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
            echo "<h4>🔗 Test Receipt Verification:</h4>\n";
            echo "<p><a href='/smartcast/verify-receipt?code={$shortCode}' target='_blank' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🔍 Verify Receipt: {$shortCode}</a></p>\n";
            echo "<p><a href='/smartcast/payment/receipt/{$testTransactionId}' target='_blank' style='background: #28a745; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>🧾 View Receipt Page</a></p>\n";
            echo "</div>\n";
            
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
            echo "<h4>❌ Receipt Verification Failed:</h4>\n";
            echo "<p>" . htmlspecialchars($verification['error']) . "</p>\n";
            echo "</div>\n";
        }
    }
    
    echo "<h2>4. Vote Receipts Table Status</h2>\n";
    
    // Check table structure
    try {
        $tableInfo = $db->select("DESCRIBE vote_receipts");
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
        echo "<h4>✅ vote_receipts Table Structure:</h4>\n";
        echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
        echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th></tr>\n";
        foreach ($tableInfo as $column) {
            echo "<tr>";
            echo "<td>{$column['Field']}</td>";
            echo "<td>{$column['Type']}</td>";
            echo "<td>{$column['Null']}</td>";
            echo "<td>{$column['Key']}</td>";
            echo "<td>{$column['Default']}</td>";
            echo "</tr>\n";
        }
        echo "</table>\n";
        echo "</div>\n";
        
        // Check recent receipts
        $recentReceipts = $db->select(
            "SELECT vr.*, t.amount, t.status 
             FROM vote_receipts vr 
             JOIN transactions t ON vr.transaction_id = t.id 
             ORDER BY vr.created_at DESC LIMIT 5"
        );
        
        echo "<h4>Recent Receipts:</h4>\n";
        if (empty($recentReceipts)) {
            echo "<p><em>No receipts found in database</em></p>\n";
        } else {
            echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
            echo "<tr><th>ID</th><th>Short Code</th><th>Transaction ID</th><th>Amount</th><th>Status</th><th>Created</th></tr>\n";
            foreach ($recentReceipts as $receipt) {
                echo "<tr>";
                echo "<td>{$receipt['id']}</td>";
                echo "<td><strong>{$receipt['short_code']}</strong></td>";
                echo "<td>{$receipt['transaction_id']}</td>";
                echo "<td>{$receipt['amount']}</td>";
                echo "<td>{$receipt['status']}</td>";
                echo "<td>{$receipt['created_at']}</td>";
                echo "</tr>\n";
            }
            echo "</table>\n";
        }
        
    } catch (\Exception $e) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
        echo "<h4>❌ Table Check Failed:</h4>\n";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>\n";
        echo "</div>\n";
    }
    
} catch (\Exception $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>\n";
    echo "<h4>❌ Error:</h4>\n";
    echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>\n";
    echo "</div>\n";
}

echo "<hr>\n";
echo "<h2>5. Summary</h2>\n";
echo "<p><strong>What was fixed:</strong></p>\n";
echo "<ul>\n";
echo "<li>✅ Added receipt generation to PaymentService vote processing</li>\n";
echo "<li>✅ Updated receipt page to use VoteReceipt model</li>\n";
echo "<li>✅ Added automatic receipt generation for existing transactions</li>\n";
echo "<li>✅ Receipts now stored in vote_receipts table with verification codes</li>\n";
echo "</ul>\n";

echo "<p><strong>Next steps:</strong></p>\n";
echo "<ul>\n";
echo "<li>Test a new payment to ensure receipt is generated automatically</li>\n";
echo "<li>Verify that receipt verification page works with the generated codes</li>\n";
echo "<li>Check that receipt page displays properly with real receipt data</li>\n";
echo "</ul>\n";
