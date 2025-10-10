<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Tenant;
use SmartCast\Models\User;
use SmartCast\Models\Event;
use SmartCast\Models\Transaction;
use SmartCast\Models\AuditLog;
use SmartCast\Models\FraudEvent;
use SmartCast\Models\RevenueShare;

/**
 * Super Admin Dashboard Controller
 */
class SuperAdminController extends BaseController
{
    private $tenantModel;
    private $userModel;
    private $eventModel;
    private $transactionModel;
    private $auditModel;
    private $fraudModel;
    private $revenueModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
        $this->requireRole('platform_admin');
        
        $this->tenantModel = new Tenant();
        $this->userModel = new User();
        $this->eventModel = new Event();
        $this->transactionModel = new Transaction();
        $this->auditModel = new AuditLog();
        $this->fraudModel = new FraudEvent();
        $this->revenueModel = new RevenueShare();
    }
    
    public function dashboard()
    {
        // Get platform-wide statistics
        $stats = $this->getPlatformStats();
        
        // Get recent activity
        $recentActivity = $this->auditModel->getRecentLogs(null, 20);
        
        // Get security alerts
        $securityAlerts = $this->fraudModel->getFraudEvents(null, null, 10);
        
        // Get revenue overview
        $revenueOverview = $this->revenueModel->getPlatformRevenue();
        
        $content = $this->renderView('superadmin/dashboard', [
            'stats' => $stats,
            'recentActivity' => $recentActivity,
            'securityAlerts' => $securityAlerts,
            'revenueOverview' => $revenueOverview,
            'title' => 'Platform Dashboard'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Dashboard'
        ]);
    }
    
    public function tenants()
    {
        // Get tenants with comprehensive data
        $sql = "
            SELECT 
                t.*,
                t.plan as plan_name,
                CASE 
                    WHEN t.plan = 'free' THEN 0
                    WHEN t.plan = 'basic' THEN 29.99
                    WHEN t.plan = 'premium' THEN 99.99
                    WHEN t.plan = 'enterprise' THEN 299.99
                    ELSE 0
                END as plan_price,
                COUNT(DISTINCT e.id) as total_events,
                COUNT(DISTINCT CASE WHEN e.status = 'active' THEN e.id END) as active_events,
                COUNT(DISTINCT c.id) as total_contestants,
                COALESCE(SUM(CASE WHEN tr.status = 'success' THEN tr.amount END), 0) as total_revenue,
                COALESCE(SUM(CASE WHEN tr.status = 'success' AND tr.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN tr.amount END), 0) as monthly_revenue,
                COUNT(DISTINCT tr.id) as total_transactions,
                MAX(tr.created_at) as last_transaction_date,
                COUNT(DISTINCT u.id) as user_count
            FROM tenants t
            LEFT JOIN events e ON t.id = e.tenant_id
            LEFT JOIN contestants c ON e.id = c.event_id AND c.active = 1
            LEFT JOIN transactions tr ON e.id = tr.event_id
            LEFT JOIN users u ON t.id = u.tenant_id AND u.active = 1
            GROUP BY t.id, t.name, t.email, t.phone, t.website, t.address, t.plan, t.active, t.verified, t.created_at, t.updated_at
            ORDER BY t.created_at DESC
        ";
        
        $tenants = $this->tenantModel->getDatabase()->select($sql);
        
        // Available plans (from ENUM)
        $availablePlans = [
            ['name' => 'Free', 'value' => 'free', 'price' => 0],
            ['name' => 'Basic', 'value' => 'basic', 'price' => 29.99],
            ['name' => 'Premium', 'value' => 'premium', 'price' => 99.99],
            ['name' => 'Enterprise', 'value' => 'enterprise', 'price' => 299.99]
        ];
        
        $content = $this->renderView('superadmin/tenants/index', [
            'tenants' => $tenants,
            'availablePlans' => $availablePlans,
            'title' => 'All Tenants'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Tenants',
            'breadcrumbs' => [
                ['title' => 'Tenants']
            ]
        ]);
    }
    
    public function approveTenant()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
        $tenantId = $_POST['tenant_id'] ?? null;
        
        if (!$tenantId || !is_numeric($tenantId)) {
            return $this->json(['success' => false, 'message' => 'Invalid tenant ID'], 400);
        }
        
        try {
            $tenant = $this->tenantModel->find($tenantId);
            
            if (!$tenant) {
                return $this->json(['success' => false, 'message' => 'Tenant not found'], 404);
            }
            
            if ($tenant['verified']) {
                return $this->json(['success' => false, 'message' => 'Tenant is already approved'], 400);
            }
            
            // Approve the tenant
            $this->tenantModel->update($tenantId, [
                'verified' => 1,
                'approved_at' => date('Y-m-d H:i:s'),
                'approved_by' => $this->session->getUserId()
            ]);
            
            // Log the approval
            $this->auditModel->create([
                'user_id' => $this->session->getUserId(),
                'action' => 'tenant_approved',
                'details' => json_encode([
                    'tenant_id' => $tenantId,
                    'tenant_name' => $tenant['name'],
                    'tenant_email' => $tenant['email']
                ]),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
            ]);
            
            // TODO: Send approval email notification to tenant
            
            return $this->json([
                'success' => true, 
                'message' => 'Tenant approved successfully',
                'tenant_name' => $tenant['name']
            ]);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Approval failed: ' . $e->getMessage()], 500);
        }
    }
    
    public function rejectTenant()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
        $tenantId = $_POST['tenant_id'] ?? null;
        $reason = $_POST['reason'] ?? 'No reason provided';
        
        if (!$tenantId || !is_numeric($tenantId)) {
            return $this->json(['success' => false, 'message' => 'Invalid tenant ID'], 400);
        }
        
        try {
            $tenant = $this->tenantModel->find($tenantId);
            
            if (!$tenant) {
                return $this->json(['success' => false, 'message' => 'Tenant not found'], 404);
            }
            
            if ($tenant['verified']) {
                return $this->json(['success' => false, 'message' => 'Cannot reject an approved tenant'], 400);
            }
            
            // Deactivate the tenant instead of deleting
            $this->tenantModel->update($tenantId, [
                'active' => 0,
                'rejection_reason' => $reason,
                'rejected_at' => date('Y-m-d H:i:s'),
                'rejected_by' => $this->session->getUserId()
            ]);
            
            // Log the rejection
            $this->auditModel->create([
                'user_id' => $this->session->getUserId(),
                'action' => 'tenant_rejected',
                'details' => json_encode([
                    'tenant_id' => $tenantId,
                    'tenant_name' => $tenant['name'],
                    'tenant_email' => $tenant['email'],
                    'reason' => $reason
                ]),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
            ]);
            
            // TODO: Send rejection email notification to tenant
            
            return $this->json([
                'success' => true, 
                'message' => 'Tenant rejected successfully',
                'tenant_name' => $tenant['name']
            ]);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Rejection failed: ' . $e->getMessage()], 500);
        }
    }
    
    public function suspendTenant()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
        $tenantId = $_POST['tenant_id'] ?? null;
        $reason = $_POST['reason'] ?? 'No reason provided';
        
        if (!$tenantId || !is_numeric($tenantId)) {
            return $this->json(['success' => false, 'message' => 'Invalid tenant ID'], 400);
        }
        
        try {
            $tenant = $this->tenantModel->find($tenantId);
            
            if (!$tenant) {
                return $this->json(['success' => false, 'message' => 'Tenant not found'], 404);
            }
            
            // Suspend the tenant
            $this->tenantModel->update($tenantId, [
                'active' => 0,
                'suspension_reason' => $reason,
                'suspended_at' => date('Y-m-d H:i:s'),
                'suspended_by' => $this->session->getUserId()
            ]);
            
            // Log the suspension
            $this->auditModel->create([
                'user_id' => $this->session->getUserId(),
                'action' => 'tenant_suspended',
                'details' => json_encode([
                    'tenant_id' => $tenantId,
                    'tenant_name' => $tenant['name'],
                    'tenant_email' => $tenant['email'],
                    'reason' => $reason
                ]),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
            ]);
            
            return $this->json([
                'success' => true, 
                'message' => 'Tenant suspended successfully',
                'tenant_name' => $tenant['name']
            ]);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Suspension failed: ' . $e->getMessage()], 500);
        }
    }
    
    public function reactivateTenant()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->json(['success' => false, 'message' => 'Invalid request method'], 405);
        }
        
        $tenantId = $_POST['tenant_id'] ?? null;
        
        if (!$tenantId || !is_numeric($tenantId)) {
            return $this->json(['success' => false, 'message' => 'Invalid tenant ID'], 400);
        }
        
        try {
            $tenant = $this->tenantModel->find($tenantId);
            
            if (!$tenant) {
                return $this->json(['success' => false, 'message' => 'Tenant not found'], 404);
            }
            
            // Reactivate the tenant
            $this->tenantModel->update($tenantId, [
                'active' => 1,
                'verified' => 1, // Ensure it's also verified
                'suspension_reason' => null,
                'rejection_reason' => null,
                'reactivated_at' => date('Y-m-d H:i:s'),
                'reactivated_by' => $this->session->getUserId()
            ]);
            
            // Log the reactivation
            $this->auditModel->create([
                'user_id' => $this->session->getUserId(),
                'action' => 'tenant_reactivated',
                'details' => json_encode([
                    'tenant_id' => $tenantId,
                    'tenant_name' => $tenant['name'],
                    'tenant_email' => $tenant['email']
                ]),
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
            ]);
            
            return $this->json([
                'success' => true, 
                'message' => 'Tenant reactivated successfully',
                'tenant_name' => $tenant['name']
            ]);
            
        } catch (\Exception $e) {
            return $this->json(['success' => false, 'message' => 'Reactivation failed: ' . $e->getMessage()], 500);
        }
    }
    
    public function users()
    {
        $users = $this->userModel->findAll([], 'created_at DESC');
        
        $content = $this->renderView('superadmin/users/index', [
            'users' => $users,
            'title' => 'All Users'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Users',
            'breadcrumbs' => [
                ['title' => 'Users']
            ]
        ]);
    }
    
    public function platformAnalytics()
    {
        // Get comprehensive platform analytics
        $analytics = $this->getPlatformAnalytics();
        
        $content = $this->renderView('superadmin/platform/analytics', [
            'analytics' => $analytics,
            'title' => 'Platform Analytics'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Analytics',
            'breadcrumbs' => [
                ['title' => 'Platform', 'url' => SUPERADMIN_URL . '/platform'],
                ['title' => 'Analytics']
            ]
        ]);
    }
    
    public function securityOverview()
    {
        // Get security metrics
        $securityMetrics = $this->getSecurityMetrics();
        
        // Get recent fraud events
        $fraudEvents = $this->fraudModel->getFraudEvents(null, null, 50);
        
        // Get fraud statistics
        $fraudStats = $this->fraudModel->getFraudStats();
        
        $content = $this->renderView('superadmin/security/overview', [
            'securityMetrics' => $securityMetrics,
            'fraudEvents' => $fraudEvents,
            'fraudStats' => $fraudStats,
            'title' => 'Security Overview'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Security Overview',
            'breadcrumbs' => [
                ['title' => 'Security', 'url' => SUPERADMIN_URL . '/security'],
                ['title' => 'Overview']
            ]
        ]);
    }
    
    public function financialOverview()
    {
        // Get platform revenue data
        $revenueData = $this->getRevenueAnalytics();
        
        // Get top performing tenants
        $topTenants = $this->getTopTenants();
        
        $content = $this->renderView('superadmin/financial/overview', [
            'revenueData' => $revenueData,
            'topTenants' => $topTenants,
            'title' => 'Financial Overview'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Financial Overview',
            'breadcrumbs' => [
                ['title' => 'Financial', 'url' => SUPERADMIN_URL . '/financial'],
                ['title' => 'Overview']
            ]
        ]);
    }
    
    
    private function getPlatformStats()
    {
        $stats = [];
        
        // Total tenants
        $stats['total_tenants'] = $this->tenantModel->count();
        $stats['active_tenants'] = $this->tenantModel->count(['active' => 1]);
        
        // Total users
        $stats['total_users'] = $this->userModel->count();
        $stats['active_users'] = $this->userModel->count(['active' => 1]);
        
        // Total events
        $stats['total_events'] = $this->eventModel->count();
        $stats['active_events'] = $this->eventModel->count(['status' => 'active']);
        
        // Total transactions
        $stats['total_transactions'] = $this->transactionModel->count();
        $stats['successful_transactions'] = $this->transactionModel->count(['status' => 'success']);
        
        // Platform revenue
        $revenueData = $this->revenueModel->getPlatformRevenue();
        $stats['total_revenue'] = $revenueData['total_revenue'] ?? 0;
        
        return $stats;
    }
    
    private function getPlatformAnalytics()
    {
        // Growth metrics
        $thisMonth = date('Y-m-01');
        $lastMonth = date('Y-m-01', strtotime('-1 month'));
        
        $analytics = [
            'tenant_growth' => $this->getTenantGrowth(),
            'revenue_growth' => $this->getRevenueGrowth(),
            'user_engagement' => $this->getUserEngagement(),
            'event_performance' => $this->getEventPerformance()
        ];
        
        return $analytics;
    }
    
    private function getSecurityMetrics()
    {
        $metrics = [];
        
        // Failed login attempts in last 24 hours
        $since24h = date('Y-m-d H:i:s', time() - 86400);
        $metrics['failed_logins'] = $this->auditModel->count([
            'action' => 'login_failed'
        ]);
        
        // Fraud events in last 7 days
        $since7d = date('Y-m-d H:i:s', time() - (7 * 86400));
        $metrics['fraud_events'] = $this->fraudModel->count();
        
        // Active risk blocks
        $riskBlockModel = new \SmartCast\Models\RiskBlock();
        $metrics['active_blocks'] = $riskBlockModel->count(['active' => 1]);
        
        return $metrics;
    }
    
    private function getRevenueAnalytics()
    {
        // Monthly revenue for the last 12 months
        $revenueByMonth = [];
        
        for ($i = 11; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $startDate = $month . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($startDate));
            
            $revenue = $this->revenueModel->getPlatformRevenue($startDate, $endDate);
            $revenueByMonth[] = [
                'month' => $month,
                'revenue' => $revenue['total_revenue'] ?? 0
            ];
        }
        
        return [
            'monthly_revenue' => $revenueByMonth,
            'total_revenue' => $this->revenueModel->getPlatformRevenue()
        ];
    }
    
    private function getTopTenants()
    {
        $sql = "
            SELECT t.*, 
                   COUNT(DISTINCT e.id) as event_count,
                   COALESCE(SUM(rs.amount), 0) as total_revenue
            FROM tenants t
            LEFT JOIN events e ON t.id = e.tenant_id
            LEFT JOIN transactions tr ON e.id = tr.event_id AND tr.status = 'success'
            LEFT JOIN revenue_shares rs ON tr.id = rs.transaction_id
            WHERE t.active = 1
            GROUP BY t.id
            ORDER BY total_revenue DESC
            LIMIT 10
        ";
        
        return $this->tenantModel->getDatabase()->select($sql);
    }
    
    private function getTenantGrowth()
    {
        // Tenant registrations by month for last 6 months
        $growth = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $month = date('Y-m', strtotime("-{$i} months"));
            $startDate = $month . '-01 00:00:00';
            $endDate = date('Y-m-t 23:59:59', strtotime($startDate));
            
            $count = $this->tenantModel->getDatabase()->selectOne(
                "SELECT COUNT(*) as count FROM tenants WHERE created_at BETWEEN :start AND :end",
                ['start' => $startDate, 'end' => $endDate]
            );
            
            $growth[] = [
                'month' => $month,
                'count' => $count['count'] ?? 0
            ];
        }
        
        return $growth;
    }
    
    private function getRevenueGrowth()
    {
        // Similar to tenant growth but for revenue
        return $this->getRevenueAnalytics()['monthly_revenue'];
    }
    
    private function getUserEngagement()
    {
        // Active users in last 30 days
        $since30d = date('Y-m-d H:i:s', time() - (30 * 86400));
        
        $activeUsers = $this->userModel->getDatabase()->selectOne(
            "SELECT COUNT(DISTINCT user_id) as count FROM audit_logs WHERE created_at >= :since",
            ['since' => $since30d]
        );
        
        return [
            'active_users_30d' => $activeUsers['count'] ?? 0,
            'total_users' => $this->userModel->count()
        ];
    }
    
    private function getEventPerformance()
    {
        // Events created vs events with votes
        $totalEvents = $this->eventModel->count();
        
        $eventsWithVotes = $this->eventModel->getDatabase()->selectOne(
            "SELECT COUNT(DISTINCT event_id) as count FROM votes"
        );
        
        return [
            'total_events' => $totalEvents,
            'events_with_votes' => $eventsWithVotes['count'] ?? 0,
            'engagement_rate' => $totalEvents > 0 ? (($eventsWithVotes['count'] ?? 0) / $totalEvents) * 100 : 0
        ];
    }
    
    private function renderView($view, $data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . '/../../views/' . $view . '.php';
        return ob_get_clean();
    }
    
    private function renderLayout($layout, $content, $data = [])
    {
        $data['content'] = $content;
        extract($data);
        ob_start();
        include __DIR__ . '/../../views/layout/' . $layout . '.php';
        return ob_get_clean();
    }
    
    // Platform Management Methods
    public function platformOverview()
    {
        $stats = $this->getPlatformStats();
        $analytics = $this->getPlatformAnalytics();
        
        $content = $this->renderView('superadmin/platform/overview', [
            'stats' => $stats,
            'analytics' => $analytics,
            'title' => 'Platform Overview'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Overview',
            'breadcrumbs' => [
                ['title' => 'Platform', 'url' => SUPERADMIN_URL . '/platform'],
                ['title' => 'Overview']
            ]
        ]);
    }
    
    public function platformPerformance()
    {
        $performance = $this->getPerformanceMetrics();
        
        $content = $this->renderView('superadmin/platform/performance', [
            'performance' => $performance,
            'title' => 'Platform Performance'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Performance',
            'breadcrumbs' => [
                ['title' => 'Platform', 'url' => SUPERADMIN_URL . '/platform'],
                ['title' => 'Performance']
            ]
        ]);
    }
    
    // Tenant Management Methods
    public function pendingTenants()
    {
        // Get tenants that are not verified (pending approval)
        $pendingTenants = $this->tenantModel->findAll(['verified' => 0], 'created_at DESC');
        
        $content = $this->renderView('superadmin/tenants/pending', [
            'pendingTenants' => $pendingTenants,
            'title' => 'Pending Tenant Approvals'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Pending Tenants',
            'breadcrumbs' => [
                ['title' => 'Tenants', 'url' => SUPERADMIN_URL . '/tenants'],
                ['title' => 'Pending Approval']
            ]
        ]);
    }
    
    public function suspendedTenants()
    {
        // Get tenants that are inactive (suspended)
        $suspendedTenants = $this->tenantModel->findAll(['active' => 0], 'updated_at DESC');
        
        $content = $this->renderView('superadmin/tenants/suspended', [
            'suspendedTenants' => $suspendedTenants,
            'title' => 'Suspended Tenants'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Suspended Tenants',
            'breadcrumbs' => [
                ['title' => 'Tenants', 'url' => SUPERADMIN_URL . '/tenants'],
                ['title' => 'Suspended']
            ]
        ]);
    }
    
    public function tenantPlans()
    {
        $planModel = new \SmartCast\Models\SubscriptionPlan();
        $feeRuleModel = new \SmartCast\Models\FeeRule();
        
        $plans = $planModel->getActivePlans();
        $feeRules = $feeRuleModel->findAll(['active' => 1], 'created_at DESC');
        
        // Add usage statistics to each plan
        foreach ($plans as &$plan) {
            $plan['usage_stats'] = $planModel->getPlanUsageStats($plan['id']);
            $plan['features'] = $planModel->getPlanFeatures($plan['id']);
            $plan['can_delete'] = $planModel->canDeletePlan($plan['id']);
        }
        
        $content = $this->renderView('superadmin/tenants/plans', [
            'plans' => $plans,
            'feeRules' => $feeRules,
            'title' => 'Subscription Plans'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Subscription Plans',
            'breadcrumbs' => [
                ['title' => 'Tenants', 'url' => SUPERADMIN_URL . '/tenants'],
                ['title' => 'Plans']
            ]
        ]);
    }
    
    public function createPlan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $planModel = new \SmartCast\Models\SubscriptionPlan();
                $data = $this->sanitizeInput($_POST);
                
                // Prepare plan data
                $planData = [
                    'name' => $data['name'],
                    'slug' => strtolower(str_replace(' ', '-', $data['name'])),
                    'description' => $data['description'],
                    'price' => floatval($data['price']),
                    'billing_cycle' => $data['billing_cycle'],
                    'max_events' => !empty($data['max_events']) ? intval($data['max_events']) : null,
                    'max_contestants_per_event' => !empty($data['max_contestants_per_event']) ? intval($data['max_contestants_per_event']) : null,
                    'max_votes_per_event' => !empty($data['max_votes_per_event']) ? intval($data['max_votes_per_event']) : null,
                    'fee_rule_id' => !empty($data['fee_rule_id']) ? intval($data['fee_rule_id']) : null,
                    'is_popular' => isset($data['is_popular']) ? 1 : 0,
                    'trial_days' => intval($data['trial_days'] ?? 0),
                    'sort_order' => intval($data['sort_order'] ?? 0)
                ];
                
                // Prepare features
                $features = [];
                if (!empty($data['features'])) {
                    foreach ($data['features'] as $feature) {
                        if (!empty($feature['name'])) {
                            $features[] = [
                                'key' => strtolower(str_replace(' ', '_', $feature['name'])),
                                'name' => $feature['name'],
                                'value' => $feature['value'] ?? null,
                                'is_boolean' => isset($feature['is_boolean']) ? 1 : 0,
                                'sort_order' => intval($feature['sort_order'] ?? 0)
                            ];
                        }
                    }
                }
                
                $planId = $planModel->createPlanWithFeatures($planData, $features);
                
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Plan created successfully', 'success');
                
            } catch (\Exception $e) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Failed to create plan: ' . $e->getMessage(), 'error');
            }
        }
    }
    
    public function changeTenantPlan($tenantId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = $this->sanitizeInput($_POST);
                $newPlanId = intval($data['plan_id']);
                $changeReason = $data['change_reason'] ?? 'Changed by admin';
                
                $subscriptionModel = new \SmartCast\Models\TenantSubscription();
                $subscriptionModel->changeSubscription($tenantId, $newPlanId, $changeReason);
                
                // Log the action
                $this->auditModel->create([
                    'user_id' => $this->session->getUserId(),
                    'action' => 'tenant_plan_changed',
                    'details' => json_encode([
                        'tenant_id' => $tenantId,
                        'new_plan_id' => $newPlanId,
                        'reason' => $changeReason
                    ]),
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
                ]);
                
                $this->redirect(SUPERADMIN_URL . '/tenants', 'Tenant plan changed successfully', 'success');
                
            } catch (\Exception $e) {
                $this->redirect(SUPERADMIN_URL . '/tenants', 'Failed to change plan: ' . $e->getMessage(), 'error');
            }
        }
    }
    
    // User Management Methods
    public function platformAdmins()
    {
        $admins = $this->userModel->findAll(['role' => 'platform_admin'], 'created_at DESC');
        
        $content = $this->renderView('superadmin/users/admins', [
            'admins' => $admins,
            'title' => 'Platform Administrators'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Admins',
            'breadcrumbs' => [
                ['title' => 'Users', 'url' => SUPERADMIN_URL . '/users'],
                ['title' => 'Admins']
            ]
        ]);
    }
    
    public function userActivity()
    {
        $activity = $this->getUserActivityData();
        
        $content = $this->renderView('superadmin/users/activity', [
            'activity' => $activity,
            'title' => 'User Activity Monitoring'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'User Activity',
            'breadcrumbs' => [
                ['title' => 'Users', 'url' => SUPERADMIN_URL . '/users'],
                ['title' => 'Activity']
            ]
        ]);
    }
    
    // Financial Management Methods
    public function platformRevenue()
    {
        $revenue = $this->getRevenueData();
        
        $content = $this->renderView('superadmin/financial/revenue', [
            'revenue' => $revenue,
            'title' => 'Platform Revenue'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Revenue',
            'breadcrumbs' => [
                ['title' => 'Financial', 'url' => SUPERADMIN_URL . '/financial'],
                ['title' => 'Revenue']
            ]
        ]);
    }
    
    public function revenueDistribution()
    {
        $distribution = $this->getRevenueDistributionData();
        
        $content = $this->renderView('superadmin/financial/distribution', [
            'distribution' => $distribution,
            'title' => 'Revenue Distribution'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Revenue Distribution',
            'breadcrumbs' => [
                ['title' => 'Financial', 'url' => SUPERADMIN_URL . '/financial'],
                ['title' => 'Revenue Distribution']
            ]
        ]);
    }
    
    public function allTransactions()
    {
        $transactions = $this->getTransactionData();
        
        $content = $this->renderView('superadmin/financial/transactions', [
            'transactions' => $transactions,
            'title' => 'All Transactions'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'All Transactions',
            'breadcrumbs' => [
                ['title' => 'Financial', 'url' => SUPERADMIN_URL . '/financial'],
                ['title' => 'Transactions']
            ]
        ]);
    }
    
    public function platformPayouts()
    {
        $payouts = $this->getPayoutData();
        
        $content = $this->renderView('superadmin/financial/payouts', [
            'payouts' => $payouts,
            'title' => 'Payouts'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Payouts',
            'breadcrumbs' => [
                ['title' => 'Financial', 'url' => SUPERADMIN_URL . '/financial'],
                ['title' => 'Payouts']
            ]
        ]);
    }
    
    public function globalFeeRules()
    {
        $fees = $this->getFeeRulesData();
        
        $content = $this->renderView('superadmin/financial/fees', [
            'fees' => $fees,
            'title' => 'Global Fee Rules'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Global Fee Rules',
            'breadcrumbs' => [
                ['title' => 'Financial', 'url' => SUPERADMIN_URL . '/financial'],
                ['title' => 'Fees']
            ]
        ]);
    }
    
    public function getPlan($planId = null)
    {
        error_log("getPlan called with planId: " . ($planId ?? 'null'));
        
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $planId = intval($planId ?? 0);
            error_log("Parsed planId: " . $planId);
            
            if (!$planId) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Plan ID is required']);
                return;
            }
            
            $planModel = new \SmartCast\Models\SubscriptionPlan();
            $plan = $planModel->find($planId);
            
            if ($plan) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'plan' => $plan]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Plan not found']);
            }
            
        } catch (\Exception $e) {
            error_log('Plan fetch error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error fetching plan: ' . $e->getMessage()]);
        }
    }
    
    public function getPlanStats($planId = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $planId = intval($planId ?? 0);
            
            if (!$planId) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Plan ID is required']);
                return;
            }
            
            $planModel = new \SmartCast\Models\SubscriptionPlan();
            $plan = $planModel->find($planId);
            
            if (!$plan) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Plan not found']);
                return;
            }
            
            // Get plan statistics
            $stats = $this->calculatePlanStats($planId);
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'plan' => $plan, 'stats' => $stats]);
            
        } catch (\Exception $e) {
            error_log('Plan stats error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error fetching plan statistics: ' . $e->getMessage()]);
        }
    }
    
    public function deletePlan($planId = null)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Invalid request method', 'error');
            return;
        }
        
        try {
            $planId = intval($planId ?? 0);
            
            if (!$planId) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Plan ID is required', 'error');
                return;
            }
            
            $planModel = new \SmartCast\Models\SubscriptionPlan();
            
            // Check if plan can be deleted
            if (!$planModel->canDeletePlan($planId)) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Cannot delete plan: Has active subscriptions', 'error');
                return;
            }
            
            $deleted = $planModel->delete($planId);
            
            if ($deleted) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Plan deleted successfully!', 'success');
            } else {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Failed to delete plan', 'error');
            }
            
        } catch (\Exception $e) {
            error_log('Plan deletion error: ' . $e->getMessage());
            $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Error deleting plan: ' . $e->getMessage(), 'error');
        }
    }
    
    public function exportPlanStats($planId = null)
    {
        try {
            $planId = intval($planId ?? 0);
            
            if (!$planId) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Plan ID is required', 'error');
                return;
            }
            
            $planModel = new \SmartCast\Models\SubscriptionPlan();
            $plan = $planModel->find($planId);
            
            if (!$plan) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Plan not found', 'error');
                return;
            }
            
            $stats = $this->calculatePlanStats($planId);
            
            // Generate CSV export
            $filename = 'plan_stats_' . $plan['slug'] . '_' . date('Y-m-d') . '.csv';
            
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '"');
            
            $output = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($output, ['Plan Statistics Report']);
            fputcsv($output, ['Generated on: ' . date('Y-m-d H:i:s')]);
            fputcsv($output, []);
            fputcsv($output, ['Plan Name', $plan['name']]);
            fputcsv($output, ['Plan Price', '$' . number_format($plan['price'], 2)]);
            fputcsv($output, ['Billing Cycle', $plan['billing_cycle']]);
            fputcsv($output, []);
            fputcsv($output, ['Metric', 'Value']);
            fputcsv($output, ['Active Subscriptions', $stats['active_subscriptions']]);
            fputcsv($output, ['Monthly Revenue', '$' . number_format($stats['monthly_revenue'], 2)]);
            fputcsv($output, ['Total Events', $stats['total_events']]);
            fputcsv($output, ['Churn Rate', $stats['churn_rate'] . '%']);
            fputcsv($output, ['Avg Events per Tenant', $stats['avg_events_per_tenant']]);
            fputcsv($output, ['Total Votes', number_format($stats['total_votes'])]);
            
            fclose($output);
            
        } catch (\Exception $e) {
            error_log('Plan export error: ' . $e->getMessage());
            $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Error exporting plan statistics: ' . $e->getMessage(), 'error');
        }
    }
    
    private function calculatePlanStats($planId)
    {
        try {
            // Get active subscriptions count
            $activeSubscriptionsSql = "
                SELECT COUNT(*) as count
                FROM tenant_subscriptions ts
                WHERE ts.plan_id = :plan_id
                AND ts.status = 'active'
            ";
            $activeSubscriptions = $this->tenantModel->getDatabase()->selectOne($activeSubscriptionsSql, ['plan_id' => $planId]);
            
            // Get monthly revenue
            $monthlyRevenueSql = "
                SELECT 
                    sp.price * COUNT(ts.id) as monthly_revenue
                FROM subscription_plans sp
                LEFT JOIN tenant_subscriptions ts ON sp.id = ts.plan_id AND ts.status = 'active'
                WHERE sp.id = :plan_id
                GROUP BY sp.id, sp.price
            ";
            $monthlyRevenue = $this->tenantModel->getDatabase()->selectOne($monthlyRevenueSql, ['plan_id' => $planId]);
            
            // Get total events created by tenants on this plan
            $totalEventsSql = "
                SELECT COUNT(e.id) as count
                FROM events e
                INNER JOIN tenants t ON e.tenant_id = t.id
                INNER JOIN tenant_subscriptions ts ON t.id = ts.tenant_id
                WHERE ts.plan_id = :plan_id
                AND ts.status = 'active'
            ";
            $totalEvents = $this->tenantModel->getDatabase()->selectOne($totalEventsSql, ['plan_id' => $planId]);
            
            // Get total votes
            $totalVotesSql = "
                SELECT COALESCE(SUM(v.quantity), 0) as count
                FROM votes v
                INNER JOIN events e ON v.event_id = e.id
                INNER JOIN tenants t ON e.tenant_id = t.id
                INNER JOIN tenant_subscriptions ts ON t.id = ts.tenant_id
                WHERE ts.plan_id = :plan_id
                AND ts.status = 'active'
            ";
            $totalVotes = $this->tenantModel->getDatabase()->selectOne($totalVotesSql, ['plan_id' => $planId]);
            
            // Get recent subscriptions
            $recentSubscriptionsSql = "
                SELECT 
                    ts.created_at,
                    ts.status,
                    t.name as tenant_name
                FROM tenant_subscriptions ts
                INNER JOIN tenants t ON ts.tenant_id = t.id
                WHERE ts.plan_id = :plan_id
                ORDER BY ts.created_at DESC
                LIMIT 10
            ";
            $recentSubscriptions = $this->tenantModel->getDatabase()->select($recentSubscriptionsSql, ['plan_id' => $planId]);
            
            $activeCount = $activeSubscriptions['count'] ?? 0;
            $eventsCount = $totalEvents['count'] ?? 0;
            
            return [
                'active_subscriptions' => $activeCount,
                'monthly_revenue' => $monthlyRevenue['monthly_revenue'] ?? 0,
                'total_events' => $eventsCount,
                'total_votes' => $totalVotes['count'] ?? 0,
                'churn_rate' => 0, // Would need historical data to calculate properly
                'avg_events_per_tenant' => $activeCount > 0 ? round($eventsCount / $activeCount, 1) : 0,
                'avg_contestants_per_event' => 0, // Would need additional query
                'revenue_per_subscription' => $activeCount > 0 ? round(($monthlyRevenue['monthly_revenue'] ?? 0) / $activeCount, 2) : 0,
                'recent_subscriptions' => $recentSubscriptions
            ];
            
        } catch (\Exception $e) {
            error_log('Plan stats calculation error: ' . $e->getMessage());
            return [
                'active_subscriptions' => 0,
                'monthly_revenue' => 0,
                'total_events' => 0,
                'total_votes' => 0,
                'churn_rate' => 0,
                'avg_events_per_tenant' => 0,
                'avg_contestants_per_event' => 0,
                'revenue_per_subscription' => 0,
                'recent_subscriptions' => []
            ];
        }
    }
    
    public function createFeeRule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Invalid request method', 'error');
            return;
        }
        
        try {
            $data = $this->sanitizeInput($_POST);
            
            // Validate required fields
            if (empty($data['name']) || empty($data['type']) || empty($data['rate'])) {
                $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Please fill in all required fields', 'error');
                return;
            }
            
            $feeRuleModel = new \SmartCast\Models\FeeRule();
            
            // Prepare fee rule data
            $feeRuleData = [
                'tenant_id' => null, // Global rule
                'event_id' => null,  // Global rule
                'rule_type' => $data['type'],
                'active' => isset($data['active']) ? 1 : 0
            ];
            
            // Set rate based on type
            if ($data['type'] === 'percentage') {
                $feeRuleData['percentage_rate'] = floatval($data['rate']);
                $feeRuleData['fixed_amount'] = null;
            } elseif ($data['type'] === 'fixed') {
                $feeRuleData['percentage_rate'] = null;
                $feeRuleData['fixed_amount'] = floatval($data['rate']);
            }
            
            // Create the fee rule
            $ruleId = $feeRuleModel->create($feeRuleData);
            
            if ($ruleId) {
                $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Fee rule created successfully!', 'success');
            } else {
                $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Failed to create fee rule', 'error');
            }
            
        } catch (\Exception $e) {
            error_log('Fee rule creation error: ' . $e->getMessage());
            $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Error creating fee rule: ' . $e->getMessage(), 'error');
        }
    }
    
    public function updateFeeRule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Invalid request method', 'error');
            return;
        }
        
        try {
            $data = $this->sanitizeInput($_POST);
            
            if (empty($data['rule_id'])) {
                $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Fee rule ID is required', 'error');
                return;
            }
            
            $feeRuleModel = new \SmartCast\Models\FeeRule();
            $ruleId = intval($data['rule_id']);
            
            // Prepare update data
            $updateData = [
                'rule_type' => $data['type'],
                'active' => isset($data['active']) ? 1 : 0
            ];
            
            // Set rate based on type
            if ($data['type'] === 'percentage') {
                $updateData['percentage_rate'] = floatval($data['rate']);
                $updateData['fixed_amount'] = null;
            } elseif ($data['type'] === 'fixed') {
                $updateData['percentage_rate'] = null;
                $updateData['fixed_amount'] = floatval($data['rate']);
            }
            
            $updated = $feeRuleModel->update($ruleId, $updateData);
            
            if ($updated) {
                $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Fee rule updated successfully!', 'success');
            } else {
                $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Failed to update fee rule', 'error');
            }
            
        } catch (\Exception $e) {
            error_log('Fee rule update error: ' . $e->getMessage());
            $this->redirect(SUPERADMIN_URL . '/financial/fees', 'Error updating fee rule: ' . $e->getMessage(), 'error');
        }
    }
    
    public function toggleFeeRule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $data = $this->sanitizeInput($_POST);
            
            if (empty($data['rule_id'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Fee rule ID is required']);
                return;
            }
            
            $feeRuleModel = new \SmartCast\Models\FeeRule();
            $ruleId = intval($data['rule_id']);
            
            // Get current rule
            $rule = $feeRuleModel->find($ruleId);
            if (!$rule) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Fee rule not found']);
                return;
            }
            
            // Toggle active status
            $newStatus = $rule['active'] ? 0 : 1;
            $updated = $feeRuleModel->update($ruleId, ['active' => $newStatus]);
            
            if ($updated) {
                $statusText = $newStatus ? 'activated' : 'deactivated';
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => "Fee rule {$statusText} successfully!"]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to update fee rule status']);
            }
            
        } catch (\Exception $e) {
            error_log('Fee rule toggle error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error updating fee rule: ' . $e->getMessage()]);
        }
    }
    
    public function deleteFeeRule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $data = $this->sanitizeInput($_POST);
            
            if (empty($data['rule_id'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Fee rule ID is required']);
                return;
            }
            
            $feeRuleModel = new \SmartCast\Models\FeeRule();
            $ruleId = intval($data['rule_id']);
            
            // Check if rule is being used
            $stats = $feeRuleModel->getFeeRuleStats($ruleId);
            if ($stats && $stats['usage_count'] > 0) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Cannot delete fee rule that has been used in transactions. Deactivate it instead.']);
                return;
            }
            
            $deleted = $feeRuleModel->delete($ruleId);
            
            if ($deleted) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Fee rule deleted successfully!']);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Failed to delete fee rule']);
            }
            
        } catch (\Exception $e) {
            error_log('Fee rule deletion error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error deleting fee rule: ' . $e->getMessage()]);
        }
    }
    
    public function getFeeRule()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }
        
        try {
            $ruleId = intval($_GET['rule_id'] ?? 0);
            
            if (!$ruleId) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Fee rule ID is required']);
                return;
            }
            
            $feeRuleModel = new \SmartCast\Models\FeeRule();
            $rule = $feeRuleModel->find($ruleId);
            
            if ($rule) {
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'rule' => $rule]);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Fee rule not found']);
            }
            
        } catch (\Exception $e) {
            error_log('Fee rule fetch error: ' . $e->getMessage());
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Error fetching fee rule: ' . $e->getMessage()]);
        }
    }
    
    // Security Methods
    public function fraudDetection()
    {
        $fraud = $this->getFraudData();
        
        $content = $this->renderView('superadmin/security/fraud', [
            'fraud' => $fraud,
            'title' => 'Fraud Detection'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Fraud Detection',
            'breadcrumbs' => [
                ['title' => 'Security', 'url' => SUPERADMIN_URL . '/security'],
                ['title' => 'Fraud Detection']
            ]
        ]);
    }
    
    public function auditLogs()
    {
        $audit = $this->getAuditData();
        
        $content = $this->renderView('superadmin/security/audit', [
            'audit' => $audit,
            'title' => 'Audit Logs'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Audit Logs',
            'breadcrumbs' => [
                ['title' => 'Security', 'url' => SUPERADMIN_URL . '/security'],
                ['title' => 'Audit Logs']
            ]
        ]);
    }
    
    public function riskBlocks()
    {
        $blocks = $this->getRiskBlocksData();
        
        $content = $this->renderView('superadmin/security/blocks', [
            'blocks' => $blocks,
            'title' => 'Risk Blocks'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Risk Blocks',
            'breadcrumbs' => [
                ['title' => 'Security', 'url' => SUPERADMIN_URL . '/security'],
                ['title' => 'Risk Blocks']
            ]
        ]);
    }
    
    // System Administration Methods
    public function globalSettings()
    {
        $settings = $this->getGlobalSettings();
        
        $content = $this->renderView('superadmin/system/settings', [
            'settings' => $settings,
            'title' => 'Global Settings'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Global Settings',
            'breadcrumbs' => [
                ['title' => 'System', 'url' => SUPERADMIN_URL . '/system'],
                ['title' => 'Settings']
            ]
        ]);
    }
    
    public function systemMaintenance()
    {
        $maintenance = $this->getMaintenanceData();
        
        $content = $this->renderView('superadmin/system/maintenance', [
            'maintenance' => $maintenance,
            'title' => 'System Maintenance'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'System Maintenance',
            'breadcrumbs' => [
                ['title' => 'System', 'url' => SUPERADMIN_URL . '/system'],
                ['title' => 'Maintenance']
            ]
        ]);
    }
    
    public function systemBackups()
    {
        $backups = $this->getBackupData();
        
        $content = $this->renderView('superadmin/system/backups', [
            'backups' => $backups,
            'title' => 'System Backups'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'System Backups',
            'breadcrumbs' => [
                ['title' => 'System', 'url' => SUPERADMIN_URL . '/system'],
                ['title' => 'Backups']
            ]
        ]);
    }
    
    public function systemLogs()
    {
        $logs = $this->getSystemLogsData();
        
        $content = $this->renderView('superadmin/system/logs', [
            'logs' => $logs,
            'title' => 'System Logs'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'System Logs',
            'breadcrumbs' => [
                ['title' => 'System', 'url' => SUPERADMIN_URL . '/system'],
                ['title' => 'Logs']
            ]
        ]);
    }
    
    // API Management Methods
    public function apiOverview()
    {
        $api = $this->getApiData();
        
        $content = $this->renderView('superadmin/api/overview', [
            'api' => $api,
            'title' => 'API Usage Overview'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'API Overview',
            'breadcrumbs' => [
                ['title' => 'API Management', 'url' => SUPERADMIN_URL . '/api'],
                ['title' => 'Overview']
            ]
        ]);
    }
    
    public function apiKeys()
    {
        $keys = $this->getApiKeysData();
        
        $content = $this->renderView('superadmin/api/keys', [
            'keys' => $keys,
            'title' => 'API Keys'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'API Keys',
            'breadcrumbs' => [
                ['title' => 'API Management', 'url' => SUPERADMIN_URL . '/api'],
                ['title' => 'Keys']
            ]
        ]);
    }
    
    public function apiWebhooks()
    {
        $webhooks = $this->getWebhooksData();
        
        $content = $this->renderView('superadmin/api/webhooks', [
            'webhooks' => $webhooks,
            'title' => 'Webhooks'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Webhooks',
            'breadcrumbs' => [
                ['title' => 'API Management', 'url' => SUPERADMIN_URL . '/api'],
                ['title' => 'Webhooks']
            ]
        ]);
    }
    
    // Reports Method
    public function platformReports()
    {
        $reports = $this->getReportsData();
        
        $content = $this->renderView('superadmin/reports', [
            'reports' => $reports,
            'title' => 'Platform Reports'
        ]);
        
        echo $this->renderLayout('superadmin_layout', $content, [
            'title' => 'Platform Reports',
            'breadcrumbs' => [
                ['title' => 'Reports']
            ]
        ]);
    }
    
    /**
     * Update a subscription plan
     */
    public function updatePlan($planId)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $planModel = new \SmartCast\Models\SubscriptionPlan();
                $data = $this->sanitizeInput($_POST);
                
                // Prepare plan data
                $planData = [
                    'name' => $data['name'],
                    'slug' => strtolower(str_replace(' ', '-', $data['name'])),
                    'description' => $data['description'],
                    'price' => floatval($data['price']),
                    'billing_cycle' => $data['billing_cycle'],
                    'max_events' => !empty($data['max_events']) ? intval($data['max_events']) : null,
                    'max_contestants_per_event' => !empty($data['max_contestants_per_event']) ? intval($data['max_contestants_per_event']) : null,
                    'max_votes_per_event' => !empty($data['max_votes_per_event']) ? intval($data['max_votes_per_event']) : null,
                    'fee_rule_id' => !empty($data['fee_rule_id']) ? intval($data['fee_rule_id']) : null,
                    'is_popular' => isset($data['is_popular']) ? 1 : 0,
                    'trial_days' => intval($data['trial_days'] ?? 0),
                    'sort_order' => intval($data['sort_order'] ?? 0)
                ];
                
                // Update plan and sync to all subscribers
                $planModel->updatePlanWithSubscriberSync($planId, $planData);
                
                // Log the action
                $this->auditModel->create([
                    'user_id' => $this->session->getUserId(),
                    'action' => 'plan_updated',
                    'details' => json_encode([
                        'plan_id' => $planId,
                        'plan_name' => $planData['name'],
                        'changes_applied_to_subscribers' => true
                    ]),
                    'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null
                ]);
                
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Plan updated successfully and changes applied to all subscribers', 'success');
                
            } catch (\Exception $e) {
                $this->redirect(SUPERADMIN_URL . '/tenants/plans', 'Failed to update plan: ' . $e->getMessage(), 'error');
            }
        }
    }
    
    // Helper methods for data retrieval
    private function getPerformanceMetrics()
    {
        return [
            'cpu_usage' => 45,
            'memory_usage' => 62,
            'disk_usage' => 38,
            'response_time' => 120,
            'uptime' => 99.9
        ];
    }
    
    private function getSubscriptionPlans()
    {
        return [
            ['id' => 1, 'name' => 'Basic', 'monthly_price' => 29, 'subscriber_count' => 150],
            ['id' => 2, 'name' => 'Professional', 'monthly_price' => 99, 'subscriber_count' => 75],
            ['id' => 3, 'name' => 'Enterprise', 'monthly_price' => 299, 'subscriber_count' => 25]
        ];
    }
    
    private function getUserActivityData()
    {
        return [
            'active_users_today' => 1250,
            'total_sessions' => 3400,
            'avg_session_duration' => '12:34',
            'page_views' => 15600,
            'recent_activities' => []
        ];
    }
    
    private function getRevenueData()
    {
        // Get real platform revenue from revenue_shares table
        $totalPlatformRevenue = $this->revenueModel->getPlatformRevenue();
        $monthlyRevenue = $this->revenueModel->getPlatformRevenue(
            date('Y-m-01 00:00:00'),
            date('Y-m-t 23:59:59')
        );
        
        // Get tenant payouts
        $totalPayouts = $this->getTotalPayouts();
        
        return [
            'total_revenue' => $totalPlatformRevenue['total_revenue'] ?? 0,
            'monthly_revenue' => $monthlyRevenue['total_revenue'] ?? 0,
            'total_payouts' => $totalPayouts,
            'net_profit' => ($totalPlatformRevenue['total_revenue'] ?? 0) - $totalPayouts,
            'active_tenants' => $totalPlatformRevenue['active_tenants'] ?? 0,
            'total_transactions' => $totalPlatformRevenue['total_transactions'] ?? 0
        ];
    }
    
    private function getTotalPayouts()
    {
        $sql = "SELECT COALESCE(SUM(total_paid), 0) as total_payouts FROM tenant_balances";
        $result = $this->tenantModel->getDatabase()->selectOne($sql);
        return $result['total_payouts'] ?? 0;
    }
    
    private function getTransactionData()
    {
        return [
            'total_count' => 5432,
            'total_volume' => 125000,
            'today_count' => 156,
            'success_rate' => 98.5,
            'list' => []
        ];
    }
    
    private function getPayoutData()
    {
        return [
            'total_paid' => 85000,
            'pending_amount' => 5500,
            'this_month' => 25,
            'avg_payout' => 2200,
            'pending' => [],
            'history' => []
        ];
    }
    
    private function getFeeRulesData()
    {
        // Get total fees collected from revenue_shares
        $totalCollectedSql = "
            SELECT COALESCE(SUM(rs.amount), 0) as total_collected
            FROM revenue_shares rs
            INNER JOIN transactions t ON rs.transaction_id = t.id
            WHERE t.status = 'success'
        ";
        $totalCollected = $this->tenantModel->getDatabase()->selectOne($totalCollectedSql);
        
        // Get monthly fees (current month)
        $monthlyFeesSql = "
            SELECT COALESCE(SUM(rs.amount), 0) as monthly_fees
            FROM revenue_shares rs
            INNER JOIN transactions t ON rs.transaction_id = t.id
            WHERE t.status = 'success'
            AND MONTH(rs.created_at) = MONTH(NOW())
            AND YEAR(rs.created_at) = YEAR(NOW())
        ";
        $monthlyFees = $this->tenantModel->getDatabase()->selectOne($monthlyFeesSql);
        
        // Get average fee rate
        $avgFeeRateSql = "
            SELECT AVG((rs.amount / t.amount) * 100) as avg_fee_rate
            FROM revenue_shares rs
            INNER JOIN transactions t ON rs.transaction_id = t.id
            WHERE t.status = 'success' AND t.amount > 0
        ";
        $avgFeeRate = $this->tenantModel->getDatabase()->selectOne($avgFeeRateSql);
        
        // Get active fee rules with usage statistics
        $activeRulesSql = "
            SELECT 
                fr.id,
                fr.rule_type,
                fr.percentage_rate,
                fr.fixed_amount,
                fr.tenant_id,
                fr.event_id,
                fr.created_at,
                fr.active,
                t.name as tenant_name,
                e.name as event_name,
                COUNT(rs.id) as usage_count,
                COALESCE(SUM(rs.amount), 0) as total_collected_rule,
                COALESCE(AVG(rs.amount), 0) as avg_fee_amount
            FROM fee_rules fr
            LEFT JOIN tenants t ON fr.tenant_id = t.id
            LEFT JOIN events e ON fr.event_id = e.id
            LEFT JOIN revenue_shares rs ON fr.id = rs.fee_rule_id
            WHERE fr.active = 1
            GROUP BY fr.id
            ORDER BY total_collected_rule DESC, fr.created_at DESC
        ";
        $activeRules = $this->tenantModel->getDatabase()->select($activeRulesSql);
        
        // Format active rules for the view
        $formattedRules = [];
        foreach ($activeRules as $rule) {
            $appliesTo = 'Global (All Tenants)';
            if ($rule['tenant_id'] && $rule['event_id']) {
                $appliesTo = 'Event: ' . $rule['event_name'];
            } elseif ($rule['tenant_id']) {
                $appliesTo = 'Tenant: ' . $rule['tenant_name'];
            }
            
            $rateDisplay = '';
            if ($rule['rule_type'] === 'percentage') {
                $rateDisplay = $rule['percentage_rate'] . '%';
            } elseif ($rule['rule_type'] === 'fixed') {
                $rateDisplay = '$' . number_format($rule['fixed_amount'], 2);
            } elseif ($rule['rule_type'] === 'blend') {
                $rateDisplay = $rule['percentage_rate'] . '% + $' . number_format($rule['fixed_amount'], 2);
            }
            
            $formattedRules[] = [
                'id' => $rule['id'],
                'name' => $this->generateRuleName($rule),
                'description' => $this->generateRuleDescription($rule),
                'type' => $rule['rule_type'],
                'rate' => $rule['rule_type'] === 'percentage' ? $rule['percentage_rate'] : $rule['fixed_amount'],
                'rate_display' => $rateDisplay,
                'applies_to' => $appliesTo,
                'conditions' => $this->generateRuleConditions($rule),
                'created_at' => $rule['created_at'],
                'usage_count' => $rule['usage_count'],
                'total_collected' => $rule['total_collected_rule'],
                'avg_fee_amount' => $rule['avg_fee_amount']
            ];
        }
        
        return [
            'total_collected' => $totalCollected['total_collected'] ?? 0,
            'monthly_fees' => $monthlyFees['monthly_fees'] ?? 0,
            'avg_fee_rate' => round($avgFeeRate['avg_fee_rate'] ?? 0, 1),
            'active_rules' => $formattedRules
        ];
    }
    
    private function generateRuleName($rule)
    {
        if ($rule['tenant_id'] && $rule['event_id']) {
            return 'Event-Specific Rule';
        } elseif ($rule['tenant_id']) {
            return 'Tenant-Specific Rule';
        } else {
            return 'Global Platform Rule';
        }
    }
    
    private function generateRuleDescription($rule)
    {
        $desc = ucfirst($rule['rule_type']) . ' fee rule';
        
        if ($rule['rule_type'] === 'percentage') {
            $desc .= ' (' . $rule['percentage_rate'] . '% of transaction amount)';
        } elseif ($rule['rule_type'] === 'fixed') {
            $desc .= ' ($' . number_format($rule['fixed_amount'], 2) . ' per transaction)';
        } elseif ($rule['rule_type'] === 'blend') {
            $desc .= ' (' . $rule['percentage_rate'] . '% + $' . number_format($rule['fixed_amount'], 2) . ')';
        }
        
        return $desc;
    }
    
    private function generateRuleConditions($rule)
    {
        $conditions = [];
        
        if ($rule['tenant_id']) {
            $conditions[] = 'Tenant: ' . $rule['tenant_name'];
        }
        
        if ($rule['event_id']) {
            $conditions[] = 'Event: ' . $rule['event_name'];
        }
        
        if ($rule['usage_count'] > 0) {
            $conditions[] = 'Used ' . $rule['usage_count'] . ' times';
        }
        
        return implode(', ', $conditions);
    }
    
    private function getFraudData()
    {
        return [
            'detected_today' => 5,
            'prevented_amount' => 2500,
            'detection_rate' => 94.5,
            'false_positive_rate' => 2.1,
            'active_alerts' => [],
            'rules' => [],
            'recent_events' => []
        ];
    }
    
    private function getAuditData()
    {
        return [
            'total_logs' => 15000,
            'today_logs' => 450,
            'critical_events' => 3,
            'unique_users' => 125,
            'logs' => []
        ];
    }
    
    private function getRiskBlocksData()
    {
        return [
            'active_blocks' => 15,
            'ip_blocks' => 8,
            'user_blocks' => 5,
            'blocked_attempts' => 45,
            'active' => [],
            'recent_activity' => []
        ];
    }
    
    private function getGlobalSettings()
    {
        return [
            'platform_name' => 'SmartCast',
            'platform_url' => 'https://smartcast.example.com',
            'default_timezone' => 'UTC',
            'allow_registration' => true,
            'maintenance_mode' => false
        ];
    }
    
    private function getMaintenanceData()
    {
        return [
            'last_backup' => '2024-01-15 02:00:00',
            'system_health' => 'good',
            'scheduled_tasks' => []
        ];
    }
    
    private function getBackupData()
    {
        return [
            'last_backup' => '2024-01-15 02:00:00',
            'backup_size' => '2.5 GB',
            'backups' => []
        ];
    }
    
    private function getSystemLogsData()
    {
        return [
            'error_count' => 12,
            'warning_count' => 45,
            'logs' => []
        ];
    }
    
    private function getApiData()
    {
        return [
            'total_requests' => 15000,
            'success_rate' => 98.5,
            'avg_response_time' => 120,
            'active_keys' => 25,
            'endpoints' => [],
            'top_consumers' => [],
            'popular_endpoints' => [],
            'recent_activity' => []
        ];
    }
    
    private function getApiKeysData()
    {
        return [
            'total_keys' => 25,
            'active_keys' => 22,
            'keys' => []
        ];
    }
    
    private function getWebhooksData()
    {
        return [
            'total_webhooks' => 8,
            'active_webhooks' => 6,
            'webhooks' => []
        ];
    }
    
    private function getReportsData()
    {
        return [
            'recent' => [],
            'scheduled' => []
        ];
    }
    
    private function getRevenueDistributionData()
    {
        // Get real-time revenue distribution analytics
        $totalRevenue = $this->revenueModel->getPlatformRevenue();
        $tenantBalances = $this->getTenantBalancesSummary();
        $recentDistributions = $this->getRecentRevenueDistributions();
        $distributionStats = $this->getDistributionStats();
        
        return [
            'total_platform_revenue' => $totalRevenue['total_revenue'] ?? 0,
            'total_tenant_earnings' => $tenantBalances['total_available'] + $tenantBalances['total_paid'],
            'pending_payouts' => $tenantBalances['total_available'],
            'completed_payouts' => $tenantBalances['total_paid'],
            'active_tenants' => $tenantBalances['tenant_count'],
            'distribution_rate' => $distributionStats['avg_distribution_rate'],
            'recent_distributions' => $recentDistributions,
            'tenant_balances' => $tenantBalances['balances'],
            'fee_breakdown' => $this->getFeeBreakdown(),
            'distribution_trends' => $this->getDistributionTrends()
        ];
    }
    
    private function getTenantBalancesSummary()
    {
        $sql = "
            SELECT 
                SUM(available) as total_available,
                SUM(pending) as total_pending,
                SUM(total_earned) as total_earned,
                SUM(total_paid) as total_paid,
                COUNT(*) as tenant_count
            FROM tenant_balances
        ";
        
        $summary = $this->tenantModel->getDatabase()->selectOne($sql);
        
        // Get individual tenant balances
        $balancesSql = "
            SELECT tb.*, t.name as tenant_name, t.email as tenant_email
            FROM tenant_balances tb
            INNER JOIN tenants t ON tb.tenant_id = t.id
            WHERE t.active = 1
            ORDER BY tb.available DESC
            LIMIT 10
        ";
        
        $balances = $this->tenantModel->getDatabase()->select($balancesSql);
        
        return array_merge($summary, ['balances' => $balances]);
    }
    
    private function getRecentRevenueDistributions($limit = 20)
    {
        $sql = "
            SELECT 
                rs.id,
                rs.amount as platform_fee,
                rs.created_at,
                t.amount as total_amount,
                (t.amount - rs.amount) as tenant_amount,
                ten.name as tenant_name,
                e.name as event_name,
                c.name as contestant_name
            FROM revenue_shares rs
            INNER JOIN transactions t ON rs.transaction_id = t.id
            INNER JOIN tenants ten ON rs.tenant_id = ten.id
            INNER JOIN events e ON t.event_id = e.id
            INNER JOIN contestants c ON t.contestant_id = c.id
            ORDER BY rs.created_at DESC
            LIMIT :limit
        ";
        
        return $this->tenantModel->getDatabase()->select($sql, ['limit' => $limit]);
    }
    
    private function getDistributionStats()
    {
        $sql = "
            SELECT 
                AVG((rs.amount / t.amount) * 100) as avg_distribution_rate,
                COUNT(*) as total_distributions,
                SUM(rs.amount) as total_platform_fees,
                SUM(t.amount - rs.amount) as total_tenant_earnings
            FROM revenue_shares rs
            INNER JOIN transactions t ON rs.transaction_id = t.id
            WHERE t.status = 'success'
        ";
        
        return $this->tenantModel->getDatabase()->selectOne($sql);
    }
    
    private function getFeeBreakdown()
    {
        $sql = "
            SELECT 
                fr.rule_type,
                fr.percentage_rate,
                fr.fixed_amount,
                COUNT(rs.id) as usage_count,
                SUM(rs.amount) as total_collected,
                AVG(rs.amount) as avg_fee
            FROM fee_rules fr
            LEFT JOIN revenue_shares rs ON fr.id = rs.fee_rule_id
            WHERE fr.active = 1
            GROUP BY fr.id, fr.rule_type, fr.percentage_rate, fr.fixed_amount
            ORDER BY total_collected DESC
        ";
        
        return $this->tenantModel->getDatabase()->select($sql);
    }
    
    private function getDistributionTrends($days = 30)
    {
        $trends = [];
        
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-{$i} days"));
            
            $sql = "
                SELECT 
                    COALESCE(SUM(rs.amount), 0) as platform_fees,
                    COALESCE(SUM(t.amount - rs.amount), 0) as tenant_earnings,
                    COUNT(rs.id) as distribution_count
                FROM revenue_shares rs
                INNER JOIN transactions t ON rs.transaction_id = t.id
                WHERE DATE(rs.created_at) = :date
                AND t.status = 'success'
            ";
            
            $result = $this->tenantModel->getDatabase()->selectOne($sql, ['date' => $date]);
            
            $trends[] = [
                'date' => $date,
                'platform_fees' => floatval($result['platform_fees'] ?? 0),
                'tenant_earnings' => floatval($result['tenant_earnings'] ?? 0),
                'distribution_count' => intval($result['distribution_count'] ?? 0)
            ];
        }
        
        return $trends;
    }
}
