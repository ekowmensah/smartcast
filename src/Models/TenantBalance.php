<?php

namespace SmartCast\Models;

/**
 * Tenant Balance Model
 */
class TenantBalance extends BaseModel
{
    protected $table = 'tenant_balances';
    protected $fillable = [
        'tenant_id', 'available', 'pending', 'total_earned', 'total_paid'
    ];
    
    public function getBalance($tenantId)
    {
        $balance = $this->findAll(['tenant_id' => $tenantId], null, 1);
        
        if (empty($balance)) {
            // Create initial balance record
            $this->create([
                'tenant_id' => $tenantId,
                'available' => 0.00,
                'pending' => 0.00,
                'total_earned' => 0.00,
                'total_paid' => 0.00
            ]);
            
            return $this->getBalance($tenantId);
        }
        
        return $balance[0];
    }
    
    public function updateBalance($tenantId, $totalEarned, $totalPaid = null)
    {
        $currentBalance = $this->getBalance($tenantId);
        
        $newAvailable = $totalEarned - ($totalPaid ?? $currentBalance['total_paid']);
        
        $updateData = [
            'available' => max(0, $newAvailable),
            'total_earned' => $totalEarned
        ];
        
        if ($totalPaid !== null) {
            $updateData['total_paid'] = $totalPaid;
        }
        
        return $this->update($currentBalance['id'], $updateData);
    }
    
    public function addEarnings($tenantId, $amount)
    {
        $balance = $this->getBalance($tenantId);
        
        return $this->update($balance['id'], [
            'available' => $balance['available'] + $amount,
            'total_earned' => $balance['total_earned'] + $amount
        ]);
    }
    
    public function addPendingAmount($tenantId, $amount)
    {
        $balance = $this->getBalance($tenantId);
        
        return $this->update($balance['id'], [
            'pending' => $balance['pending'] + $amount
        ]);
    }
    
    public function movePendingToAvailable($tenantId, $amount = null)
    {
        $balance = $this->getBalance($tenantId);
        
        $amountToMove = $amount ?? $balance['pending'];
        $amountToMove = min($amountToMove, $balance['pending']);
        
        return $this->update($balance['id'], [
            'available' => $balance['available'] + $amountToMove,
            'pending' => $balance['pending'] - $amountToMove,
            'total_earned' => $balance['total_earned'] + $amountToMove
        ]);
    }
    
    public function processPayout($tenantId, $amount)
    {
        $balance = $this->getBalance($tenantId);
        
        if ($balance['available'] < $amount) {
            throw new \Exception('Insufficient balance for payout');
        }
        
        return $this->update($balance['id'], [
            'available' => $balance['available'] - $amount,
            'total_paid' => $balance['total_paid'] + $amount
        ]);
    }
    
    public function getBalanceHistory($tenantId, $limit = 50)
    {
        // This would require a separate balance_history table in a real implementation
        // For now, we'll return the current balance
        return [$this->getBalance($tenantId)];
    }
    
    public function getAllBalances($activeOnly = true)
    {
        $sql = "
            SELECT tb.*, t.name as tenant_name, t.email as tenant_email
            FROM {$this->table} tb
            INNER JOIN tenants t ON tb.tenant_id = t.id
        ";
        
        if ($activeOnly) {
            $sql .= " WHERE t.active = 1";
        }
        
        $sql .= " ORDER BY tb.available DESC";
        
        return $this->db->select($sql);
    }
    
    public function getTotalPlatformBalance()
    {
        $sql = "
            SELECT 
                SUM(available) as total_available,
                SUM(pending) as total_pending,
                SUM(total_earned) as total_earned,
                SUM(total_paid) as total_paid,
                COUNT(*) as tenant_count
            FROM {$this->table}
        ";
        
        return $this->db->selectOne($sql);
    }
    
    public function getBalanceStats($tenantId)
    {
        $balance = $this->getBalance($tenantId);
        
        // Calculate additional stats
        $stats = [
            'current_balance' => $balance,
            'payout_percentage' => $balance['total_earned'] > 0 
                ? ($balance['total_paid'] / $balance['total_earned']) * 100 
                : 0,
            'pending_percentage' => $balance['total_earned'] > 0 
                ? ($balance['pending'] / $balance['total_earned']) * 100 
                : 0
        ];
        
        return $stats;
    }
    
    public function canRequestPayout($tenantId, $amount)
    {
        $balance = $this->getBalance($tenantId);
        return $balance['available'] >= $amount;
    }
    
    public function getMinimumPayoutAmount($tenantId)
    {
        // This could be configurable per tenant or globally
        return 10.00; // Minimum $10 payout
    }
}
