<?php

namespace SmartCast\Services;

use SmartCast\Models\Payout;
use SmartCast\Models\PayoutMethod;
use SmartCast\Models\PayoutSchedule;
use SmartCast\Models\TenantBalance;
use SmartCast\Models\RevenueTransaction;

/**
 * Payout Service
 * Handles all payout-related operations and calculations
 */
class PayoutService
{
    private $payoutModel;
    private $payoutMethodModel;
    private $payoutScheduleModel;
    private $balanceModel;
    private $revenueModel;
    
    public function __construct()
    {
        $this->payoutModel = new Payout();
        $this->payoutMethodModel = new PayoutMethod();
        $this->payoutScheduleModel = new PayoutSchedule();
        $this->balanceModel = new TenantBalance();
        $this->revenueModel = new RevenueTransaction();
    }
    
    /**
     * Process revenue from a successful transaction
     */
    public function processTransactionRevenue($transactionId, $tenantId, $eventId, $grossAmount, $feeRules = null)
    {
        try {
            // Create revenue transaction record
            $revenueTransactionId = $this->revenueModel->createRevenueTransaction(
                $transactionId, 
                $tenantId, 
                $eventId, 
                $grossAmount, 
                $feeRules
            );
            
            // Get the revenue breakdown
            $revenueTransaction = $this->revenueModel->find($revenueTransactionId);
            
            // Update tenant balance
            $this->balanceModel->addEarnings($tenantId, $revenueTransaction['net_tenant_amount']);
            
            // Check for instant payout eligibility
            $this->checkInstantPayoutEligibility($tenantId);
            
            return [
                'success' => true,
                'revenue_transaction_id' => $revenueTransactionId,
                'net_amount' => $revenueTransaction['net_tenant_amount'],
                'platform_fee' => $revenueTransaction['platform_fee'],
                'processing_fee' => $revenueTransaction['processing_fee']
            ];
            
        } catch (\Exception $e) {
            error_log('Revenue processing failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Request a manual payout
     */
    public function requestPayout($tenantId, $amount, $payoutMethodId = null)
    {
        try {
            // Get payout method
            $payoutMethod = $payoutMethodId 
                ? $this->payoutMethodModel->find($payoutMethodId)
                : $this->payoutMethodModel->getDefaultMethod($tenantId);
            
            if (!$payoutMethod) {
                throw new \Exception('No payout method available');
            }
            
            // Debug logging
            error_log("Payout method found: " . json_encode($payoutMethod));
            
            // Check payout eligibility
            $schedule = $this->payoutScheduleModel->getScheduleByTenant($tenantId);
            $eligibility = $this->payoutScheduleModel->canRequestPayout($tenantId, $amount);
            
            if (!$eligibility['allowed']) {
                throw new \Exception($eligibility['reason']);
            }
            
            // Check balance
            if (!$this->balanceModel->canRequestPayout($tenantId, $amount)) {
                throw new \Exception('Insufficient balance for payout request');
            }
            
            // Calculate processing fee
            $processingFee = $this->calculateProcessingFee($amount, $payoutMethod['method_type']);
            $netAmount = $amount - $processingFee;
            
            // Create payout record
            $payoutId = $this->payoutModel->create([
                'tenant_id' => $tenantId,
                'payout_id' => $this->generatePayoutId(),
                'amount' => $amount,
                'processing_fee' => $processingFee,
                'net_amount' => $netAmount,
                'payout_method' => $payoutMethod['method_type'],
                'payout_method_id' => $payoutMethod['id'],
                'payout_type' => 'manual',
                'recipient_details' => $payoutMethod['account_details'],
                'status' => Payout::STATUS_QUEUED
            ]);
            
            // Reserve the amount from available balance (move to pending, not paid)
            $this->balanceModel->reserveForPayout($tenantId, $amount);
            
            return [
                'success' => true,
                'payout_id' => $payoutId,
                'amount' => $amount,
                'processing_fee' => $processingFee,
                'net_amount' => $netAmount
            ];
            
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Process automatic payouts
     */
    public function processAutomaticPayouts()
    {
        $results = [
            'processed' => 0,
            'failed' => 0,
            'total_amount' => 0,
            'errors' => []
        ];
        
        try {
            // Get tenants eligible for automatic payout
            $eligibleTenants = $this->payoutScheduleModel->getTenantsForAutoPayout();
            
            foreach ($eligibleTenants as $tenant) {
                try {
                    $result = $this->processAutomaticPayout($tenant);
                    
                    if ($result['success']) {
                        $results['processed']++;
                        $results['total_amount'] += $result['amount'];
                    } else {
                        $results['failed']++;
                        $results['errors'][] = "Tenant {$tenant['tenant_id']}: " . $result['error'];
                    }
                    
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Tenant {$tenant['tenant_id']}: " . $e->getMessage();
                }
            }
            
        } catch (\Exception $e) {
            $results['errors'][] = 'General error: ' . $e->getMessage();
        }
        
        return $results;
    }
    
    /**
     * Process instant payouts
     */
    public function processInstantPayouts()
    {
        $results = [
            'processed' => 0,
            'failed' => 0,
            'total_amount' => 0,
            'errors' => []
        ];
        
        try {
            // Get tenants eligible for instant payout
            $eligibleTenants = $this->payoutScheduleModel->getTenantsForInstantPayout();
            
            foreach ($eligibleTenants as $tenant) {
                try {
                    $result = $this->processInstantPayout($tenant);
                    
                    if ($result['success']) {
                        $results['processed']++;
                        $results['total_amount'] += $result['amount'];
                    } else {
                        $results['failed']++;
                        $results['errors'][] = "Tenant {$tenant['tenant_id']}: " . $result['error'];
                    }
                    
                } catch (\Exception $e) {
                    $results['failed']++;
                    $results['errors'][] = "Tenant {$tenant['tenant_id']}: " . $e->getMessage();
                }
            }
            
        } catch (\Exception $e) {
            $results['errors'][] = 'General error: ' . $e->getMessage();
        }
        
        return $results;
    }
    
    /**
     * Get payout dashboard data
     */
    public function getPayoutDashboard($tenantId)
    {
        $balance = $this->balanceModel->getBalance($tenantId);
        $schedule = $this->payoutScheduleModel->getScheduleByTenant($tenantId);
        // Get recent payouts excluding cancelled ones
        $allPayouts = $this->payoutModel->getPayoutsByTenant($tenantId, null);
        $recentPayouts = array_filter($allPayouts, function($payout) {
            return $payout['status'] !== 'cancelled';
        });
        $payoutMethods = $this->payoutMethodModel->getMethodsByTenant($tenantId);
        $revenueStats = $this->revenueModel->getRevenueByTenant($tenantId);
        
        return [
            'balance' => $balance,
            'schedule' => $schedule,
            'recent_payouts' => array_slice($recentPayouts, 0, 10),
            'payout_methods' => $payoutMethods,
            'revenue_stats' => $revenueStats,
            'can_request_payout' => $this->balanceModel->canRequestPayout($tenantId, $schedule['minimum_amount'])
        ];
    }
    
    /**
     * Get platform payout analytics
     */
    public function getPlatformAnalytics($startDate = null, $endDate = null)
    {
        $platformRevenue = $this->revenueModel->getPlatformRevenue($startDate, $endDate);
        $payoutStats = $this->payoutModel->getPayoutStats();
        $topEarners = $this->revenueModel->getTopEarningTenants(10, $startDate, $endDate);
        $scheduleStats = $this->payoutScheduleModel->getScheduleStats();
        
        return [
            'platform_revenue' => $platformRevenue,
            'payout_stats' => $payoutStats,
            'top_earners' => $topEarners,
            'schedule_stats' => $scheduleStats
        ];
    }
    
    private function processAutomaticPayout($tenant)
    {
        // Get default payout method
        $payoutMethod = $this->payoutMethodModel->getDefaultMethod($tenant['tenant_id']);
        
        if (!$payoutMethod || !$payoutMethod['is_verified']) {
            return [
                'success' => false,
                'error' => 'No verified payout method available'
            ];
        }
        
        // Calculate payout amount (use available balance)
        $amount = $tenant['available'];
        $processingFee = $this->calculateProcessingFee($amount, $payoutMethod['method_type']);
        $netAmount = $amount - $processingFee;
        
        // Create payout record
        $payoutId = $this->payoutModel->create([
            'tenant_id' => $tenant['tenant_id'],
            'payout_id' => $this->generatePayoutId(),
            'amount' => $amount,
            'processing_fee' => $processingFee,
            'net_amount' => $netAmount,
            'payout_method' => $payoutMethod['method_type'],
            'payout_method_id' => $payoutMethod['id'],
            'payout_type' => 'automatic',
            'recipient_details' => $payoutMethod['account_details'],
            'status' => Payout::STATUS_QUEUED
        ]);
        
        // Process the payout
        $processed = $this->payoutModel->processPayout($payoutId);
        
        if ($processed) {
            // Update next payout date
            $this->payoutScheduleModel->updateNextPayoutDate($tenant['tenant_id']);
        }
        
        return [
            'success' => $processed,
            'amount' => $amount,
            'payout_id' => $payoutId
        ];
    }
    
    private function processInstantPayout($tenant)
    {
        // Similar to automatic payout but for instant threshold
        return $this->processAutomaticPayout($tenant);
    }
    
    private function checkInstantPayoutEligibility($tenantId)
    {
        $schedule = $this->payoutScheduleModel->getScheduleByTenant($tenantId);
        $balance = $this->balanceModel->getBalance($tenantId);
        
        if ($schedule['auto_payout_enabled'] && 
            $balance['available'] >= $schedule['instant_payout_threshold']) {
            
            // Trigger instant payout
            $this->processInstantPayout([
                'tenant_id' => $tenantId,
                'available' => $balance['available']
            ]);
        }
    }
    
    private function calculateProcessingFee($amount, $methodType)
    {
        // Processing fees by method type
        $feeStructure = [
            'bank_transfer' => ['percentage' => 1.0, 'fixed' => 0.50],
            'mobile_money' => ['percentage' => 1.5, 'fixed' => 0.25],
            'paypal' => ['percentage' => 2.9, 'fixed' => 0.30],
            'stripe' => ['percentage' => 2.9, 'fixed' => 0.30]
        ];
        
        // Default to bank_transfer if method type is null or not found
        $methodType = $methodType ?? 'bank_transfer';
        $fees = $feeStructure[$methodType] ?? $feeStructure['bank_transfer'];
        
        // Log for debugging
        error_log("Calculating fee for method: $methodType, amount: $amount");
        
        $calculatedFee = round(($amount * $fees['percentage'] / 100) + $fees['fixed'], 2);
        error_log("Calculated fee: $calculatedFee");
        
        return $calculatedFee;
    }
    
    private function generatePayoutId()
    {
        return 'PO_' . date('Ymd') . '_' . strtoupper(uniqid());
    }
    
    /**
     * Recalculate processing fee for existing payout
     */
    public function recalculateProcessingFee($payoutId)
    {
        try {
            $payout = $this->payoutModel->find($payoutId);
            error_log("Recalculate: Found payout: " . json_encode($payout));
            
            if (!$payout) {
                throw new \Exception('Payout not found');
            }
            
            // Get payout method
            $payoutMethod = null;
            if (!empty($payout['payout_method_id'])) {
                $payoutMethod = $this->payoutMethodModel->find($payout['payout_method_id']);
                error_log("Recalculate: Found payout method by ID: " . json_encode($payoutMethod));
            }
            
            if (!$payoutMethod) {
                // Try to get default method for tenant
                $payoutMethod = $this->payoutMethodModel->getDefaultMethod($payout['tenant_id']);
                error_log("Recalculate: Found default payout method: " . json_encode($payoutMethod));
            }
            
            if (!$payoutMethod) {
                // Try to get any active method for tenant
                $methods = $this->payoutMethodModel->getMethodsByTenant($payout['tenant_id']);
                if (!empty($methods)) {
                    $payoutMethod = $methods[0];
                    error_log("Recalculate: Using first available method: " . json_encode($payoutMethod));
                }
            }
            
            if (!$payoutMethod) {
                throw new \Exception('No payout method available for fee calculation');
            }
            
            // Recalculate processing fee
            $methodType = $payoutMethod['method_type'] ?? 'bank_transfer';
            error_log("Recalculate: Using method type: $methodType for amount: {$payout['amount']}");
            
            $processingFee = $this->calculateProcessingFee($payout['amount'], $methodType);
            $netAmount = $payout['amount'] - $processingFee;
            
            error_log("Recalculate: Calculated fee: $processingFee, net: $netAmount");
            
            // Update payout with correct fees
            $updateResult = $this->payoutModel->update($payoutId, [
                'processing_fee' => $processingFee,
                'net_amount' => $netAmount,
                'payout_method' => $methodType
            ]);
            
            error_log("Recalculate: Update result: " . ($updateResult ? 'success' : 'failed'));
            
            // Verify the update worked
            $updatedPayout = $this->payoutModel->find($payoutId);
            error_log("Recalculate: Updated payout: " . json_encode($updatedPayout));
            
            return [
                'success' => true,
                'processing_fee' => $processingFee,
                'net_amount' => $netAmount,
                'method_type' => $methodType
            ];
            
        } catch (\Exception $e) {
            error_log('Recalculate processing fee error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Retry failed payout
     */
    public function retryPayout($payoutId)
    {
        try {
            return $this->payoutModel->retryFailedPayout($payoutId);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Cancel pending payout
     */
    public function cancelPayout($payoutId, $reason = null)
    {
        try {
            return $this->payoutModel->cancelPayout($payoutId, $reason);
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Approve a payout request (Super Admin)
     */
    public function approvePayout($payoutId)
    {
        try {
            $payout = $this->payoutModel->find($payoutId);
            
            if (!$payout) {
                return ['success' => false, 'error' => 'Payout not found'];
            }
            
            // Can only approve queued payouts
            if ($payout['status'] !== Payout::STATUS_QUEUED) {
                return ['success' => false, 'error' => 'Payout cannot be approved in current status'];
            }
            
            // Update payout status to processing
            $this->payoutModel->update($payoutId, [
                'status' => Payout::STATUS_PROCESSING,
                'approved_at' => date('Y-m-d H:i:s')
            ]);
            
            // Process the actual payout
            $result = $this->processPayoutByMethod($payout);
            
            if ($result['success']) {
                $this->payoutModel->update($payoutId, [
                    'status' => Payout::STATUS_SUCCESS,
                    'provider_reference' => $result['reference'] ?? null,
                    'processed_at' => date('Y-m-d H:i:s')
                ]);
                
                return [
                    'success' => true,
                    'payout_id' => $payout['payout_id'],
                    'reference' => $result['reference'] ?? null
                ];
            } else {
                $this->payoutModel->update($payoutId, [
                    'status' => Payout::STATUS_FAILED,
                    'failure_reason' => $result['error'],
                    'processed_at' => date('Y-m-d H:i:s')
                ]);
                
                // Restore balance
                $this->balanceModel->reversePayout($payout['tenant_id'], $payout['amount']);
                
                return ['success' => false, 'error' => $result['error']];
            }
            
        } catch (\Exception $e) {
            error_log('Approve payout error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to approve payout'];
        }
    }
    
    /**
     * Reject a payout request (Super Admin)
     */
    public function rejectPayout($payoutId, $reason)
    {
        try {
            $payout = $this->payoutModel->find($payoutId);
            
            if (!$payout) {
                return ['success' => false, 'error' => 'Payout not found'];
            }
            
            // Can only reject queued or processing payouts
            if (!in_array($payout['status'], [Payout::STATUS_QUEUED, Payout::STATUS_PROCESSING])) {
                return ['success' => false, 'error' => 'Payout cannot be rejected in current status'];
            }
            
            // Update payout status
            $this->payoutModel->update($payoutId, [
                'status' => Payout::STATUS_CANCELLED,
                'failure_reason' => $reason,
                'processed_at' => date('Y-m-d H:i:s')
            ]);
            
            // Restore balance to tenant
            $this->balanceModel->reversePayout($payout['tenant_id'], $payout['amount']);
            
            return ['success' => true];
            
        } catch (\Exception $e) {
            error_log('Reject payout error: ' . $e->getMessage());
            return ['success' => false, 'error' => 'Failed to reject payout'];
        }
    }
}
