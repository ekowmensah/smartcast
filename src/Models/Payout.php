<?php

namespace SmartCast\Models;

/**
 * Payout Model
 */
class Payout extends BaseModel
{
    protected $table = 'payouts';
    protected $fillable = [
        'tenant_id', 'payout_id', 'amount', 'payout_method', 'recipient_details',
        'status', 'provider_reference', 'failure_reason', 'processed_at'
    ];
    
    const STATUS_QUEUED = 'queued';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_CANCELLED = 'cancelled';
    
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_MOBILE_MONEY = 'mobile_money';
    const METHOD_PAYPAL = 'paypal';
    
    public function createPayout($tenantId, $amount, $method, $recipientDetails)
    {
        $balanceModel = new TenantBalance();
        
        // Check if tenant has sufficient balance
        if (!$balanceModel->canRequestPayout($tenantId, $amount)) {
            throw new \Exception('Insufficient balance for payout request');
        }
        
        // Check minimum payout amount
        $minimumAmount = $balanceModel->getMinimumPayoutAmount($tenantId);
        if ($amount < $minimumAmount) {
            throw new \Exception("Minimum payout amount is $minimumAmount");
        }
        
        // Generate unique payout ID
        $payoutId = $this->generatePayoutId();
        
        // Create payout record
        $id = $this->create([
            'tenant_id' => $tenantId,
            'payout_id' => $payoutId,
            'amount' => $amount,
            'payout_method' => $method,
            'recipient_details' => json_encode($recipientDetails),
            'status' => self::STATUS_QUEUED
        ]);
        
        // Reserve the amount from available balance
        $balanceModel->processPayout($tenantId, $amount);
        
        return $id;
    }
    
    public function processPayout($payoutId)
    {
        $payout = $this->find($payoutId);
        
        if (!$payout || $payout['status'] !== self::STATUS_QUEUED) {
            throw new \Exception('Invalid payout or payout already processed');
        }
        
        // Update status to processing
        $this->update($payoutId, ['status' => self::STATUS_PROCESSING]);
        
        try {
            // Process based on method
            $result = $this->processPayoutByMethod($payout);
            
            if ($result['success']) {
                // Mark as successful
                $this->update($payoutId, [
                    'status' => self::STATUS_SUCCESS,
                    'provider_reference' => $result['reference'] ?? null,
                    'processed_at' => date('Y-m-d H:i:s')
                ]);
                
                return true;
            } else {
                // Mark as failed and restore balance
                $this->handlePayoutFailure($payoutId, $result['error'] ?? 'Unknown error');
                return false;
            }
            
        } catch (\Exception $e) {
            $this->handlePayoutFailure($payoutId, $e->getMessage());
            return false;
        }
    }
    
    private function processPayoutByMethod($payout)
    {
        $recipientDetails = json_decode($payout['recipient_details'], true);
        
        switch ($payout['payout_method']) {
            case self::METHOD_BANK_TRANSFER:
                return $this->processBankTransfer($payout['amount'], $recipientDetails);
                
            case self::METHOD_MOBILE_MONEY:
                return $this->processMobileMoney($payout['amount'], $recipientDetails);
                
            case self::METHOD_PAYPAL:
                return $this->processPayPal($payout['amount'], $recipientDetails);
                
            default:
                throw new \Exception('Unsupported payout method');
        }
    }
    
    private function processBankTransfer($amount, $details)
    {
        // Simulate bank transfer processing
        // In real implementation, integrate with banking API
        
        if (empty($details['account_number']) || empty($details['bank_code'])) {
            return ['success' => false, 'error' => 'Invalid bank details'];
        }
        
        // Simulate processing delay and success
        sleep(1);
        
        return [
            'success' => true,
            'reference' => 'BT_' . time() . '_' . mt_rand(1000, 9999)
        ];
    }
    
    private function processMobileMoney($amount, $details)
    {
        // Simulate mobile money processing
        // In real implementation, integrate with mobile money API
        
        if (empty($details['phone_number'])) {
            return ['success' => false, 'error' => 'Invalid phone number'];
        }
        
        return [
            'success' => true,
            'reference' => 'MM_' . time() . '_' . mt_rand(1000, 9999)
        ];
    }
    
    private function processPayPal($amount, $details)
    {
        // Simulate PayPal processing
        // In real implementation, integrate with PayPal API
        
        if (empty($details['email'])) {
            return ['success' => false, 'error' => 'Invalid PayPal email'];
        }
        
        return [
            'success' => true,
            'reference' => 'PP_' . time() . '_' . mt_rand(1000, 9999)
        ];
    }
    
    private function handlePayoutFailure($payoutId, $reason)
    {
        $payout = $this->find($payoutId);
        
        // Update payout status
        $this->update($payoutId, [
            'status' => self::STATUS_FAILED,
            'failure_reason' => $reason
        ]);
        
        // Restore balance
        $balanceModel = new TenantBalance();
        $balanceModel->addEarnings($payout['tenant_id'], $payout['amount']);
    }
    
    public function cancelPayout($payoutId, $reason = null)
    {
        $payout = $this->find($payoutId);
        
        if (!$payout || !in_array($payout['status'], [self::STATUS_QUEUED, self::STATUS_PROCESSING])) {
            throw new \Exception('Cannot cancel payout');
        }
        
        // Update status
        $this->update($payoutId, [
            'status' => self::STATUS_CANCELLED,
            'failure_reason' => $reason
        ]);
        
        // Restore balance if not already processed
        if ($payout['status'] === self::STATUS_QUEUED) {
            $balanceModel = new TenantBalance();
            $balanceModel->addEarnings($payout['tenant_id'], $payout['amount']);
        }
        
        return true;
    }
    
    public function getPayoutsByTenant($tenantId, $status = null)
    {
        $conditions = ['tenant_id' => $tenantId];
        
        if ($status) {
            $conditions['status'] = $status;
        }
        
        return $this->findAll($conditions, 'created_at DESC');
    }
    
    public function getPayoutStats($tenantId = null)
    {
        $sql = "
            SELECT 
                status,
                COUNT(*) as count,
                SUM(amount) as total_amount
            FROM {$this->table}
        ";
        
        $params = [];
        
        if ($tenantId) {
            $sql .= " WHERE tenant_id = :tenant_id";
            $params['tenant_id'] = $tenantId;
        }
        
        $sql .= " GROUP BY status";
        
        return $this->db->select($sql, $params);
    }
    
    private function generatePayoutId()
    {
        return 'PO_' . date('Ymd') . '_' . strtoupper(uniqid());
    }
    
    public function getPendingPayouts()
    {
        return $this->findAll(['status' => self::STATUS_QUEUED], 'created_at ASC');
    }
    
    public function retryFailedPayout($payoutId)
    {
        $payout = $this->find($payoutId);
        
        if (!$payout || $payout['status'] !== self::STATUS_FAILED) {
            throw new \Exception('Invalid payout for retry');
        }
        
        // Reset status to queued
        $this->update($payoutId, [
            'status' => self::STATUS_QUEUED,
            'failure_reason' => null
        ]);
        
        return true;
    }
}
