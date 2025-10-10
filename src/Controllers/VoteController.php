<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Event;
use SmartCast\Models\Contestant;
use SmartCast\Models\Category;
use SmartCast\Models\VoteBundle;
use SmartCast\Models\Transaction;
use SmartCast\Models\Vote;
use SmartCast\Models\VoteReceipt;
use SmartCast\Models\VoteLedger;
use SmartCast\Models\AuditLog;
use SmartCast\Models\Coupon;
use SmartCast\Models\Referral;
use SmartCast\Models\RevenueShare;
use SmartCast\Models\TenantBalance;
use SmartCast\Services\MoMoPaymentService;
use SmartCast\Services\RevenueWebhookService;

/**
 * Vote Controller
 */
class VoteController extends BaseController
{
    private $eventModel;
    private $contestantModel;
    private $categoryModel;
    private $bundleModel;
    private $transactionModel;
    private $voteModel;
    private $receiptModel;
    private $ledgerModel;
    private $auditModel;
    private $revenueShareModel;
    private $tenantBalanceModel;
    private $webhookService;
    private $paymentService;
    
    public function __construct()
    {
        parent::__construct();
        $this->eventModel = new Event();
        $this->contestantModel = new Contestant();
        $this->categoryModel = new Category();
        $this->bundleModel = new VoteBundle();
        $this->transactionModel = new Transaction();
        $this->voteModel = new Vote();
        $this->receiptModel = new VoteReceipt();
        $this->ledgerModel = new VoteLedger();
        $this->auditModel = new AuditLog();
        $this->revenueShareModel = new RevenueShare();
        $this->tenantBalanceModel = new TenantBalance();
        $this->webhookService = new RevenueWebhookService();
        $this->paymentService = new MoMoPaymentService();
    }
    
    public function showVoting($eventSlug)
    {
        // Handle both slug and ID
        $event = $this->resolveEvent($eventSlug);
        
        if (!$event || !$this->eventModel->canVote($event['id'])) {
            $this->redirect('/events', 'Voting is not available for this event', 'error');
        }
        
        $contestants = $this->contestantModel->getContestantsByEvent($event['id']);
        $categories = $this->categoryModel->getCategoriesByEvent($event['id']);
        
        // Group contestants by category for better organization
        $contestantsByCategory = [];
        $seenContestants = []; // Track contestants we've already added
        
        foreach ($contestants as $contestant) {
            $categoryId = $contestant['category_id'] ?? 'uncategorized';
            $categoryName = $contestant['category_name'] ?? 'Uncategorized';
            $contestantId = $contestant['id'];
            
            if (!isset($contestantsByCategory[$categoryId])) {
                $contestantsByCategory[$categoryId] = [
                    'id' => $categoryId,
                    'name' => $categoryName,
                    'contestants' => []
                ];
            }
            
            // Create a unique key for this contestant-category combination
            $uniqueKey = $contestantId . '_' . $categoryId;
            
            // Only add if we haven't seen this exact combination before
            if (!isset($seenContestants[$uniqueKey])) {
                $contestantsByCategory[$categoryId]['contestants'][] = $contestant;
                $seenContestants[$uniqueKey] = true;
            }
        }
        
        $this->view('voting/select-nominee', [
            'event' => $event,
            'contestants' => $contestants,
            'categories' => $categories,
            'contestantsByCategory' => $contestantsByCategory,
            'title' => 'Select Nominee - ' . $event['name']
        ]);
    }
    
    public function showVoteForm($eventSlug, $contestantSlug)
    {
        $event = $this->resolveEvent($eventSlug);
        
        if (!$event || !$this->eventModel->canVote($event['id'])) {
            $this->redirect('/events', 'Voting is not available for this event', 'error');
        }
        
        $contestant = $this->resolveContestant($contestantSlug);
        
        if (!$contestant || $contestant['event_id'] != $event['id'] || !$contestant['active']) {
            // Generate event slug for redirect
            require_once __DIR__ . '/../Helpers/SlugHelper.php';
            $eventSlugForRedirect = \SmartCast\Helpers\SlugHelper::generateEventSlug($event);
            $this->redirect("/events/$eventSlugForRedirect/vote", 'Invalid contestant selected', 'error');
        }
        
        $bundles = $this->bundleModel->getBundlesByEvent($event['id'], $event['tenant_id']);
        
        // Create default bundles if none exist
        if (empty($bundles)) {
            $votePrice = $event['vote_price'] ?? 0.50;
            $eventId = $event['id'];
            
            // Create default vote bundles
            $defaultBundles = [
                ['name' => 'Single Vote', 'votes' => 1, 'price' => $votePrice],
                ['name' => 'Vote Pack (5)', 'votes' => 5, 'price' => $votePrice * 5 * 0.9], // 10% discount
                ['name' => 'Vote Pack (10)', 'votes' => 10, 'price' => $votePrice * 10 * 0.8], // 20% discount
                ['name' => 'Vote Pack (25)', 'votes' => 25, 'price' => $votePrice * 25 * 0.7], // 30% discount
            ];
            
            foreach ($defaultBundles as $bundleData) {
                $this->bundleModel->create([
                    'event_id' => $eventId,
                    'name' => $bundleData['name'],
                    'votes' => $bundleData['votes'],
                    'price' => $bundleData['price'],
                    'active' => 1
                ]);
            }
            
            // Reload bundles after creation
            $bundles = $this->bundleModel->getBundlesByEvent($eventId, $event['tenant_id']);
        }
        
        // Get category information if provided, or from contestant
        $categoryId = $_GET['category'] ?? $contestant['category_id'] ?? null;
        $category = null;
        if ($categoryId) {
            $category = $this->categoryModel->find($categoryId);
        }
        
        // If no category found but contestant has category_id, use that
        if (!$category && !empty($contestant['category_id'])) {
            $category = $this->categoryModel->find($contestant['category_id']);
        }
        
        // Debug logging
        if (APP_DEBUG) {
            error_log("Vote Form Debug - Category ID: " . ($categoryId ?? 'null') . ", Category found: " . ($category ? 'yes' : 'no'));
            if ($category) {
                error_log("Category data: " . print_r($category, true));
            }
        }
        
        $this->view('voting/vote-form', [
            'event' => $event,
            'contestant' => $contestant,
            'bundles' => $bundles,
            'category' => $category,
            'vote_price' => $event['vote_price'] ?? 0.50,
            'title' => 'Vote for ' . $contestant['name']
        ]);
    }
    
    public function processVote($eventSlug)
    {
        // Resolve event from slug or ID
        $event = $this->resolveEvent($eventSlug);
        
        // Handle fallback from POST data if needed
        if (!$event && isset($_POST['event_id'])) {
            $event = $this->eventModel->find($_POST['event_id']);
        }
        
        if (!$event || !$this->eventModel->canVote($event['id'])) {
            return $this->json(['success' => false, 'message' => 'Voting not available'], 400);
        }
        
        $eventId = $event['id'];
        $data = $this->sanitizeInput($_POST);
        
        // Get category from URL parameter if available
        $categoryId = $_GET['category'] ?? $data['category_id'] ?? null;
        if ($categoryId) {
            $data['category_id'] = $categoryId;
        }
        
        // Validate input - bundle_id is optional for custom votes
        $validationRules = [
            'contestant_id' => ['required' => true, 'numeric' => true],
            'msisdn' => ['required' => true, 'min' => 10],
            'category_id' => ['required' => true, 'numeric' => true]
        ];
        
        // If it's not a custom vote, require bundle_id
        if (empty($data['vote_method']) || $data['vote_method'] !== 'custom') {
            $validationRules['bundle_id'] = ['required' => true, 'numeric' => true];
        }
        
        $errors = $this->validateInput($data, $validationRules);
        
        if (!empty($errors)) {
            return $this->json([
                'success' => false, 
                'message' => 'Validation failed',
                'errors' => $errors,
                'received_data' => array_keys($data) // Debug: show what data was received
            ], 400);
        }
        
        // Verify contestant, bundle, and category
        $contestant = $this->contestantModel->find($data['contestant_id']);
        $category = $this->categoryModel->find($data['category_id']);
        
        // Debug logging
        if (APP_DEBUG) {
            error_log("Process Vote Debug - Category ID from form: " . ($data['category_id'] ?? 'null'));
            error_log("Category found: " . ($category ? 'yes' : 'no'));
            error_log("Contestant category_id: " . ($contestant['category_id'] ?? 'not set'));
            if ($category) {
                error_log("Category data: " . print_r($category, true));
            }
            if ($contestant) {
                error_log("Contestant data keys: " . implode(', ', array_keys($contestant)));
            }
        }
        
        if (!$contestant || $contestant['event_id'] != $eventId || !$contestant['active']) {
            return $this->json(['success' => false, 'message' => 'Invalid contestant'], 400);
        }
        
        // Handle custom votes vs package votes
        if (!empty($data['vote_method']) && $data['vote_method'] === 'custom') {
            // For custom votes, we'll use the smallest existing bundle as reference
            // but calculate the price based on actual vote count
            $customVotes = intval($data['custom_votes'] ?? 1);
            if ($customVotes < 1 || $customVotes > 10000) {
                return $this->json(['success' => false, 'message' => 'Invalid vote count (1-10000)'], 400);
            }
            
            // Get any existing bundle for this event to use as a reference
            $bundles = $this->bundleModel->getBundlesByEvent($eventId, $event['tenant_id']);
            if (empty($bundles)) {
                // Create a default bundle for custom votes if none exist
                $votePrice = $event['vote_price'] ?? 0.50;
                $defaultBundleId = $this->bundleModel->create([
                    'event_id' => $eventId,
                    'name' => 'Single Vote',
                    'votes' => 1,
                    'price' => $votePrice,
                    'active' => 1
                ]);
                
                $referenceBundle = ['id' => $defaultBundleId];
            } else {
                $referenceBundle = $bundles[0];
            }
            
            $votePrice = $event['vote_price'] ?? 0.50;
            $bundle = [
                'id' => $referenceBundle['id'], // Use existing bundle ID to satisfy foreign key
                'votes' => $customVotes, // Override with custom vote count
                'price' => $customVotes * $votePrice, // Calculate custom price
                'name' => 'Custom Votes (' . $customVotes . ' votes)',
                'event_id' => $eventId,
                'active' => true
            ];
        } else {
            // Regular bundle validation
            $bundle = $this->bundleModel->find($data['bundle_id']);
            if (!$bundle || $bundle['event_id'] != $eventId || !$bundle['active']) {
                return $this->json(['success' => false, 'message' => 'Invalid vote bundle'], 400);
            }
        }
        
        if (!$category) {
            return $this->json([
                'success' => false, 
                'message' => 'Category not found',
                'debug' => ['category_id' => $data['category_id']]
            ], 400);
        }
        
        if ($category['event_id'] != $eventId) {
            return $this->json([
                'success' => false, 
                'message' => 'Category does not belong to this event',
                'debug' => ['category_event_id' => $category['event_id'], 'expected_event_id' => $eventId]
            ], 400);
        }
        
        // Check active status only if the field exists
        if (isset($category['active']) && !$category['active']) {
            return $this->json(['success' => false, 'message' => 'Category is not active'], 400);
        }
        
        // Verify contestant is in the selected category using the many-to-many relationship
        $contestantInCategory = $this->contestantModel->isContestantInCategory($data['contestant_id'], $data['category_id']);
        
        if (!$contestantInCategory) {
            return $this->json([
                'success' => false, 
                'message' => 'Contestant not in selected category',
                'debug' => [
                    'contestant_id' => $data['contestant_id'],
                    'selected_category_id' => $data['category_id']
                ]
            ], 400);
        }
        
        try {
            // Start transaction
            $this->transactionModel->getDatabase()->beginTransaction();
            
            // Create pending transaction
            $transactionData = [
                'tenant_id' => $event['tenant_id'],
                'event_id' => $eventId,
                'contestant_id' => $data['contestant_id'],
                'category_id' => $data['category_id'], // Add category context
                'bundle_id' => $bundle['id'] ?? null, // Handle virtual bundles
                'amount' => $bundle['price'],
                'msisdn' => $data['msisdn'],
                'status' => 'pending', // Set as pending until payment confirms
                'provider' => 'momo',
                'coupon_code' => $data['coupon_code'] ?? null,
                'referral_code' => $data['referral_code'] ?? null
            ];
            
            $transactionId = $this->transactionModel->createTransaction($transactionData);
            
            // Initiate MoMo payment
            $paymentData = [
                'amount' => $bundle['price'],
                'currency' => 'GHS',
                'phone' => $data['msisdn'],
                'reference' => 'VOTE_' . $transactionId,
                'description' => "Vote for {$contestant['name']} - {$bundle['votes']} vote(s)",
                'callback_url' => APP_URL . "/api/payment/callback/{$transactionId}",
                'metadata' => [
                    'event_id' => $eventId,
                    'contestant_id' => $data['contestant_id'],
                    'category_id' => $data['category_id'],
                    'votes' => $bundle['votes']
                ]
            ];
            
            $paymentResult = $this->paymentService->initiatePayment($paymentData);
            
            if (!$paymentResult['success']) {
                throw new \Exception('Payment initiation failed: ' . $paymentResult['message']);
            }
            
            // Update transaction with payment reference
            $this->transactionModel->update($transactionId, [
                'provider_reference' => $paymentResult['payment_reference']
            ]);
            
            $this->transactionModel->getDatabase()->commit();
            
            return $this->json([
                'success' => true,
                'payment_initiated' => true,
                'transaction_id' => $transactionId,
                'payment_reference' => $paymentResult['payment_reference'],
                'message' => $paymentResult['message'],
                'status_check_url' => APP_URL . "/api/payment/status/{$transactionId}",
                'expires_at' => $paymentResult['expires_at']
            ]);
            
        } catch (\Exception $e) {
            $this->transactionModel->getDatabase()->rollback();
            
            return $this->json([
                'success' => false,
                'message' => 'Vote failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function checkPaymentStatus($transactionId)
    {
        // Basic validation
        if (empty($transactionId) || !is_numeric($transactionId)) {
            return $this->json([
                'success' => false, 
                'message' => 'Invalid transaction ID',
                'transaction_id' => $transactionId
            ], 400);
        }
        
        try {
            $transaction = $this->transactionModel->find($transactionId);
            
            if (!$transaction) {
                return $this->json(['success' => false, 'message' => 'Transaction not found'], 404);
            }
            
            // Debug logging
            if (APP_DEBUG) {
                error_log("Checking payment status for transaction: " . $transactionId);
                error_log("Transaction data: " . print_r($transaction, true));
            }
            
            // Check if provider_reference exists
            if (empty($transaction['provider_reference'])) {
                // If no provider reference, the payment initiation likely failed
                // Update transaction status and return failed status
                $this->transactionModel->update($transactionId, [
                    'status' => 'failed',
                    'failure_reason' => 'Payment initiation failed - no provider reference'
                ]);
                
                return $this->json([
                    'success' => true, 
                    'transaction_id' => $transactionId,
                    'payment_status' => 'failed',
                    'message' => 'Payment initiation failed. Please try again.',
                    'amount' => $transaction['amount'],
                    'timestamp' => date('Y-m-d H:i:s')
                ]);
            }
            
            // Check payment status with MoMo service
            try {
                $paymentStatus = $this->paymentService->checkPaymentStatus($transaction['provider_reference']);
            } catch (\Exception $e) {
                error_log("MoMo service error: " . $e->getMessage());
                return $this->json([
                    'success' => false,
                    'message' => 'Payment service unavailable',
                    'transaction_id' => $transactionId,
                    'payment_status' => 'pending'
                ], 500);
            }
            
            // Debug payment status
            if (APP_DEBUG) {
                error_log("Payment status: " . $paymentStatus['status']);
                error_log("Transaction status: " . $transaction['status']);
                error_log("Payment status data: " . print_r($paymentStatus, true));
            }
            
            // Update transaction status based on payment result
            if ($paymentStatus['status'] === 'success' && $transaction['status'] === 'pending') {
                // Payment successful - process the vote
                error_log("Processing successful payment for transaction: " . $transactionId);
                try {
                    $this->processSuccessfulPayment($transaction, $paymentStatus);
                    error_log("Vote processing completed successfully for transaction: " . $transactionId);
                } catch (\Exception $e) {
                    error_log("Vote processing error: " . $e->getMessage());
                    error_log("Vote processing stack trace: " . $e->getTraceAsString());
                    // Return success for payment but note processing issue
                    return $this->json([
                        'success' => true,
                        'transaction_id' => $transactionId,
                        'payment_status' => 'success',
                        'message' => 'Payment successful, vote processing failed: ' . $e->getMessage(),
                        'receipt_number' => $paymentStatus['receipt_number'] ?? null,
                        'amount' => $paymentStatus['amount'],
                        'timestamp' => $paymentStatus['timestamp']
                    ]);
                }
            } elseif (in_array($paymentStatus['status'], ['failed', 'expired'])) {
                // Payment failed - update transaction status
                $this->transactionModel->update($transactionId, [
                    'status' => $paymentStatus['status'],
                    'failure_reason' => $paymentStatus['message'] ?? 'Payment failed'
                ]);
            }
            
            return $this->json([
                'success' => true,
                'transaction_id' => $transactionId,
                'payment_status' => $paymentStatus['status'],
                'message' => $paymentStatus['message'],
                'receipt_number' => $paymentStatus['receipt_number'] ?? null,
                'amount' => $transaction['amount'], // Use actual transaction amount
                'timestamp' => $paymentStatus['timestamp']
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Status check failed: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function processSuccessfulPayment($transaction, $paymentStatus)
    {
        try {
            error_log("Starting vote processing for transaction: " . $transaction['id']);
            $this->transactionModel->getDatabase()->beginTransaction();
            
            // Update transaction status
            error_log("Updating transaction status to success");
            $this->transactionModel->update($transaction['id'], [
                'status' => 'success',
                'provider_reference' => $paymentStatus['receipt_number'] ?? $transaction['provider_reference']
            ]);
            
            // Get vote count
            $voteCount = $this->getVoteCountFromTransaction($transaction);
            error_log("Vote count calculated: " . $voteCount);
            
            if ($voteCount <= 0) {
                throw new \Exception("Invalid vote count: " . $voteCount);
            }
            
            // Cast votes
            error_log("Casting votes - Count: " . $voteCount);
            $voteId = $this->voteModel->castVote(
                $transaction['id'],
                $transaction['tenant_id'],
                $transaction['event_id'],
                $transaction['contestant_id'],
                $transaction['category_id'],
                $voteCount
            );
            error_log("Vote cast successfully with ID: " . $voteId);
            
            // Create vote ledger entry
            $this->ledgerModel->createLedgerEntry(
                $voteId,
                $transaction['id'],
                $transaction['tenant_id'],
                $transaction['event_id'],
                $transaction['contestant_id'],
                $this->getVoteCountFromTransaction($transaction)
            );
            
            // Generate receipt
            $receipt = $this->receiptModel->generateReceipt($transaction['id']);
            
            // ✅ INSTANT REVENUE DISTRIBUTION - NEW!
            error_log("Processing revenue distribution for transaction: " . $transaction['id']);
            $this->processRevenueDistribution($transaction);
            
            // Log the vote
            $this->auditModel->logVoteCast(
                $transaction['id'], 
                $transaction['event_id'], 
                $transaction['contestant_id'], 
                $transaction['amount']
            );
            
            $this->transactionModel->getDatabase()->commit();
            
            return true;
            
        } catch (\Exception $e) {
            $this->transactionModel->getDatabase()->rollback();
            throw $e;
        }
    }
    
    private function getVoteCountFromTransaction($transaction)
    {
        error_log("Getting vote count for transaction: " . print_r($transaction, true));
        
        // If we have a bundle_id, always use bundle votes (handles discounted packages)
        if ($transaction['bundle_id']) {
            $bundle = $this->bundleModel->find($transaction['bundle_id']);
            error_log("Bundle found: " . print_r($bundle, true));
            $bundleVotes = $bundle['votes'] ?? 1;
            
            // Check if the transaction amount matches the bundle price (true bundle purchase)
            if (abs($transaction['amount'] - $bundle['price']) < 0.01) {
                error_log("Bundle purchase detected - using bundle votes: $bundleVotes");
                return $bundleVotes;
            } else {
                // Amount doesn't match bundle price - this is a custom vote using bundle as reference
                $event = $this->eventModel->find($transaction['event_id']);
                $votePrice = $event['vote_price'] ?? 0.50;
                $calculatedVotes = (int) ($transaction['amount'] / $votePrice);
                
                error_log("Custom vote with bundle reference - calculated: $calculatedVotes, bundle: $bundleVotes. Using calculated.");
                error_log("Transaction amount: {$transaction['amount']}, Bundle price: {$bundle['price']}, Vote price: $votePrice");
                return $calculatedVotes;
            }
        }
        
        // No bundle_id, calculate from amount
        $event = $this->eventModel->find($transaction['event_id']);
        $votePrice = $event['vote_price'] ?? 0.50;
        $calculatedVotes = (int) ($transaction['amount'] / $votePrice);
        
        error_log("No bundle - using calculated votes: $calculatedVotes");
        error_log("Event vote price: $votePrice, Transaction amount: {$transaction['amount']}");
        return $calculatedVotes;
    }
    
    /**
     * Process instant revenue distribution when a vote is successfully paid
     */
    private function processRevenueDistribution($transaction)
    {
        try {
            error_log("Starting revenue distribution for transaction: " . $transaction['id']);
            
            // Create revenue share record (this calculates platform fee)
            $revenueShare = $this->revenueShareModel->calculateAndCreateShare(
                $transaction['id'],
                $transaction['amount'],
                $transaction['tenant_id'],
                $transaction['event_id']  // Pass event ID for event-specific fee rules
            );
            
            if ($revenueShare) {
                error_log("Revenue share created: Platform fee = " . $revenueShare['amount']);
                
                // Calculate tenant's net amount (total - platform fee)
                $tenantNetAmount = $transaction['amount'] - $revenueShare['amount'];
                
                error_log("Tenant net amount: $tenantNetAmount (from {$transaction['amount']} - {$revenueShare['amount']})");
                
                // Update tenant balance immediately
                $this->tenantBalanceModel->addEarnings($transaction['tenant_id'], $tenantNetAmount);
                
                error_log("Revenue distribution completed successfully");
                
                $revenueBreakdown = [
                    'platform_fee' => $revenueShare['amount'],
                    'tenant_amount' => $tenantNetAmount,
                    'total_amount' => $transaction['amount']
                ];
                
                // 🚀 Send real-time webhook notifications
                $this->sendRevenueWebhooks($transaction, $revenueBreakdown);
                
                return $revenueBreakdown;
            } else {
                error_log("No revenue share created - no applicable fee rules");
                
                // If no fee rules, tenant gets full amount
                $this->tenantBalanceModel->addEarnings($transaction['tenant_id'], $transaction['amount']);
                
                return [
                    'platform_fee' => 0,
                    'tenant_amount' => $transaction['amount'],
                    'total_amount' => $transaction['amount']
                ];
            }
            
        } catch (\Exception $e) {
            error_log("Revenue distribution failed: " . $e->getMessage());
            throw new \Exception("Revenue distribution failed: " . $e->getMessage());
        }
    }
    
    /**
     * Send webhook notifications for revenue distribution
     */
    private function sendRevenueWebhooks($transaction, $revenueBreakdown)
    {
        try {
            // Get additional transaction details
            $event = $this->eventModel->find($transaction['event_id']);
            $contestant = $this->contestantModel->find($transaction['contestant_id']);
            
            $transactionData = array_merge($transaction, [
                'event_name' => $event['name'] ?? 'Unknown Event',
                'contestant_name' => $contestant['name'] ?? 'Unknown Contestant',
                'vote_count' => $this->getVoteCountFromTransaction($transaction)
            ]);
            
            // Send tenant revenue notification
            $this->webhookService->sendRevenueEarned(
                $transaction['tenant_id'],
                $transactionData,
                $revenueBreakdown
            );
            
            // Send platform revenue notification
            $this->webhookService->sendPlatformRevenue(
                $transactionData,
                $revenueBreakdown
            );
            
            error_log("Revenue webhooks sent successfully");
            
        } catch (\Exception $e) {
            error_log("Failed to send revenue webhooks: " . $e->getMessage());
            // Don't throw - webhooks are not critical for core functionality
        }
    }
    
    /**
     * Resolve event by slug or ID
     */
    private function resolveEvent($eventSlug)
    {
        // First try to find by code (slug)
        $event = $this->eventModel->findByCode($eventSlug);
        
        // If not found and it's numeric, try by ID
        if (!$event && is_numeric($eventSlug)) {
            $event = $this->eventModel->find($eventSlug);
        }
        
        return $event;
    }
    
    /**
     * Resolve contestant by slug or ID
     */
    private function resolveContestant($contestantSlug)
    {
        // Extract ID from slug (format: name-id)
        require_once __DIR__ . '/../Helpers/SlugHelper.php';
        $id = \SmartCast\Helpers\SlugHelper::extractIdFromSlug($contestantSlug);
        
        if ($id) {
            return $this->contestantModel->find($id);
        }
        
        // If not a slug format and numeric, try direct ID
        if (is_numeric($contestantSlug)) {
            return $this->contestantModel->find($contestantSlug);
        }
        
        return null;
    }
    
    public function testPaymentStatus($transactionId)
    {
        // Debug endpoint to test payment status directly
        $transaction = $this->transactionModel->find($transactionId);
        
        if (!$transaction) {
            return $this->json(['error' => 'Transaction not found'], 404);
        }
        
        // Force a successful payment status for testing
        $testPaymentStatus = [
            'status' => 'success',
            'receipt_number' => 'TEST_' . time(),
            'amount' => $transaction['amount'],
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        try {
            $this->processSuccessfulPayment($transaction, $testPaymentStatus);
            return $this->json([
                'success' => true,
                'message' => 'Vote processed successfully',
                'transaction' => $transaction,
                'test_payment_status' => $testPaymentStatus
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
    
    public function verifyReceipt()
    {
        $shortCode = $_GET['code'] ?? '';
        
        if (empty($shortCode)) {
            return $this->json(['success' => false, 'message' => 'Receipt code required'], 400);
        }
        
        $verification = $this->receiptModel->verifyReceipt($shortCode);
        
        if ($verification['valid']) {
            $details = $this->receiptModel->getReceiptDetails($shortCode);
            return $this->json(['success' => true, 'receipt' => $details]);
        } else {
            return $this->json(['success' => false, 'message' => $verification['error']], 404);
        }
    }
}
