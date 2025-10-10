<?php

namespace SmartCast\Models;

/**
 * Fee Rule Model
 */
class FeeRule extends BaseModel
{
    protected $table = 'fee_rules';
    protected $fillable = [
        'tenant_id', 'event_id', 'rule_type', 'percentage_rate', 'fixed_amount', 'active'
    ];
    
    const RULE_TYPE_PERCENTAGE = 'percentage';
    const RULE_TYPE_FIXED = 'fixed';
    const RULE_TYPE_BLEND = 'blend';
    
    public function getApplicableFeeRule($tenantId, $eventId = null)
    {
        // Priority: Event-specific > Tenant-specific > Global
        
        // 1. Check for event-specific rule
        if ($eventId) {
            $sql = "SELECT * FROM {$this->table} WHERE tenant_id = ? AND event_id = ? AND active = 1 ORDER BY created_at DESC LIMIT 1";
            $eventRule = $this->db->select($sql, [$tenantId, $eventId]);
            
            if (!empty($eventRule)) {
                return $eventRule[0];
            }
        }
        
        // 2. Check for tenant-specific rule (event_id IS NULL)
        $sql = "SELECT * FROM {$this->table} WHERE tenant_id = ? AND event_id IS NULL AND active = 1 ORDER BY created_at DESC LIMIT 1";
        $tenantRule = $this->db->select($sql, [$tenantId]);
        
        if (!empty($tenantRule)) {
            return $tenantRule[0];
        }
        
        // 3. Check for global rule (tenant_id IS NULL AND event_id IS NULL)
        $sql = "SELECT * FROM {$this->table} WHERE tenant_id IS NULL AND event_id IS NULL AND active = 1 ORDER BY created_at DESC LIMIT 1";
        $globalRule = $this->db->select($sql, []);
        
        if (!empty($globalRule)) {
            return $globalRule[0];
        }
        
        return null;
    }
    
    public function createPercentageRule($tenantId, $percentage, $eventId = null)
    {
        return $this->create([
            'tenant_id' => $tenantId,
            'event_id' => $eventId,
            'rule_type' => self::RULE_TYPE_PERCENTAGE,
            'percentage_rate' => $percentage,
            'fixed_amount' => null,
            'active' => 1
        ]);
    }
    
    public function createFixedRule($tenantId, $fixedAmount, $eventId = null)
    {
        return $this->create([
            'tenant_id' => $tenantId,
            'event_id' => $eventId,
            'rule_type' => self::RULE_TYPE_FIXED,
            'percentage_rate' => null,
            'fixed_amount' => $fixedAmount,
            'active' => 1
        ]);
    }
    
    public function createBlendRule($tenantId, $percentage, $fixedAmount, $eventId = null)
    {
        return $this->create([
            'tenant_id' => $tenantId,
            'event_id' => $eventId,
            'rule_type' => self::RULE_TYPE_BLEND,
            'percentage_rate' => $percentage,
            'fixed_amount' => $fixedAmount,
            'active' => 1
        ]);
    }
    
    public function calculateFee($amount, $feeRule)
    {
        if (!$feeRule) {
            return 0;
        }
        
        switch ($feeRule['rule_type']) {
            case self::RULE_TYPE_PERCENTAGE:
                return $amount * ($feeRule['percentage_rate'] / 100);
                
            case self::RULE_TYPE_FIXED:
                return $feeRule['fixed_amount'];
                
            case self::RULE_TYPE_BLEND:
                $percentageFee = $amount * ($feeRule['percentage_rate'] / 100);
                return $percentageFee + $feeRule['fixed_amount'];
                
            default:
                return 0;
        }
    }
    
    public function getFeeRulesByTenant($tenantId)
    {
        $sql = "
            SELECT fr.*, e.name as event_name
            FROM {$this->table} fr
            LEFT JOIN events e ON fr.event_id = e.id
            WHERE fr.tenant_id = :tenant_id OR fr.tenant_id IS NULL
            ORDER BY 
                CASE 
                    WHEN fr.event_id IS NOT NULL THEN 1
                    WHEN fr.tenant_id IS NOT NULL THEN 2
                    ELSE 3
                END,
                fr.created_at DESC
        ";
        
        return $this->db->select($sql, ['tenant_id' => $tenantId]);
    }
    
    public function getGlobalFeeRules()
    {
        return $this->findAll(['tenant_id' => null], 'created_at DESC');
    }
    
    public function activateRule($ruleId)
    {
        return $this->update($ruleId, ['active' => 1]);
    }
    
    public function deactivateRule($ruleId)
    {
        return $this->update($ruleId, ['active' => 0]);
    }
    
    public function getFeeRuleStats($ruleId)
    {
        $sql = "
            SELECT 
                COUNT(rs.id) as usage_count,
                SUM(rs.amount) as total_fees_collected,
                AVG(rs.amount) as avg_fee_per_transaction,
                MIN(rs.created_at) as first_used,
                MAX(rs.created_at) as last_used
            FROM revenue_shares rs
            WHERE rs.fee_rule_id = :rule_id
        ";
        
        return $this->db->selectOne($sql, ['rule_id' => $ruleId]);
    }
    
    public function simulateFee($amount, $ruleType, $percentage = null, $fixedAmount = null)
    {
        $mockRule = [
            'rule_type' => $ruleType,
            'percentage_rate' => $percentage,
            'fixed_amount' => $fixedAmount
        ];
        
        return $this->calculateFee($amount, $mockRule);
    }
}
