<?php

require_once __DIR__ . '/includes/autoloader.php';

use SmartCast\Core\Database;

echo "<h1>Transaction Lookup Debug</h1>\n";

// Use a recent payment reference for testing - get the most recent successful one
$testReference = '68efeb03ac'; // This one shows as 'success' in your data

try {
    $db = Database::getInstance();
    
    echo "<h2>1. Payment Transactions Table</h2>\n";
    $paymentTransactions = $db->select(
        "SELECT id, reference, gateway_reference, amount, status, created_at 
         FROM payment_transactions 
         ORDER BY created_at DESC LIMIT 10"
    );
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>ID</th><th>Reference</th><th>Gateway Ref</th><th>Amount</th><th>Status</th><th>Created</th></tr>\n";
    foreach ($paymentTransactions as $pt) {
        echo "<tr>";
        echo "<td>{$pt['id']}</td>";
        echo "<td>{$pt['reference']}</td>";
        echo "<td>{$pt['gateway_reference']}</td>";
        echo "<td>{$pt['amount']}</td>";
        echo "<td>{$pt['status']}</td>";
        echo "<td>{$pt['created_at']}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    echo "<h2>2. Voting Transactions Table</h2>\n";
    $votingTransactions = $db->select(
        "SELECT id, provider_reference, amount, status, created_at 
         FROM transactions 
         ORDER BY created_at DESC LIMIT 10"
    );
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%;'>\n";
    echo "<tr><th>ID</th><th>Provider Ref</th><th>Amount</th><th>Status</th><th>Created</th></tr>\n";
    foreach ($votingTransactions as $vt) {
        echo "<tr>";
        echo "<td>{$vt['id']}</td>";
        echo "<td>{$vt['provider_reference']}</td>";
        echo "<td>{$vt['amount']}</td>";
        echo "<td>{$vt['status']}</td>";
        echo "<td>{$vt['created_at']}</td>";
        echo "</tr>\n";
    }
    echo "</table>\n";
    
    echo "<h2>3. Test Reference Lookup</h2>\n";
    echo "<p><strong>Testing reference:</strong> {$testReference}</p>\n";
    
    // Test payment_transactions lookup
    $paymentTransaction = $db->selectOne(
        "SELECT id FROM payment_transactions WHERE gateway_reference = :ref OR reference = :ref2",
        ['ref' => $testReference, 'ref2' => $testReference]
    );
    
    if ($paymentTransaction) {
        echo "<p>✅ Found in payment_transactions: ID = {$paymentTransaction['id']}</p>\n";
    } else {
        echo "<p>❌ Not found in payment_transactions</p>\n";
    }
    
    // Test transactions lookup
    $votingTransaction = $db->selectOne(
        "SELECT id FROM transactions WHERE provider_reference = :ref",
        ['ref' => $testReference]
    );
    
    if ($votingTransaction) {
        echo "<p>✅ Found in transactions: ID = {$votingTransaction['id']}</p>\n";
    } else {
        echo "<p>❌ Not found in transactions</p>\n";
    }
    
    echo "<h2>4. Webhook Lookup Logic Test (Fixed Priority)</h2>\n";
    $transactionId = null;
    
    // Use the same logic as the fixed webhook - prioritize voting transaction
    if ($votingTransaction) {
        $transactionId = $votingTransaction['id'];
        echo "<p>✅ Using voting transaction ID (correct for receipt page): {$transactionId}</p>\n";
    } elseif ($paymentTransaction) {
        $transactionId = $paymentTransaction['id'];
        echo "<p>⚠️ Using payment transaction ID (fallback): {$transactionId}</p>\n";
        echo "<p><em>Note: Receipt page expects voting transaction ID, this might not work</em></p>\n";
    } else {
        echo "<p>❌ No transaction found with reference: {$testReference}</p>\n";
    }
    
    if ($transactionId) {
        echo "<p><strong>Receipt URL would be:</strong> /payment/receipt/{$transactionId}</p>\n";
        echo "<p><a href='/smartcast/payment/receipt/{$transactionId}' target='_blank'>🔗 Test Receipt Page</a></p>\n";
    }
    
} catch (\Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>\n";
}

echo "<hr>\n";
echo "<h2>5. Recommendations</h2>\n";
echo "<ul>\n";
echo "<li>Check if the payment reference from Paystack matches what's stored in the database</li>\n";
echo "<li>Verify that the payment verification process is creating the correct records</li>\n";
echo "<li>Check the error logs for transaction lookup failures</li>\n";
echo "<li>Consider using the voting transaction ID as fallback if payment transaction not found</li>\n";
echo "</ul>\n";
