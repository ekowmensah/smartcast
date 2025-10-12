<?php

namespace SmartCast\Core;

use SmartCast\Core\Router;
use SmartCast\Core\Database;
use SmartCast\Core\Session;

/**
 * Main Application Class
 */
class Application
{
    private $router;
    private $database;
    private $session;
    
    public function __construct()
    {
        $this->initializeDatabase();
        $this->initializeSession();
        $this->initializeRouter();
        $this->setupRoutes();
    }
    
    private function initializeDatabase()
    {
        $this->database = new Database();
    }
    
    private function initializeSession()
    {
        $this->session = new Session();
    }
    
    private function initializeRouter()
    {
        $this->router = new Router();
    }
    
    private function setupRoutes()
    {
        // Public routes
        $this->router->get('/', 'HomeController@index');
        $this->router->get('/pricing', 'AuthController@showPricing');
        $this->router->get('/login', 'AuthController@showLogin');
        $this->router->post('/login', 'AuthController@login');
        $this->router->get('/register', 'AuthController@showRegister');
        $this->router->post('/register', 'AuthController@register');
        $this->router->post('/logout', 'AuthController@logout');
        
        // Event routes
        $this->router->get('/events', 'EventController@index');
        $this->router->get('/events/{eventSlug}', 'EventController@show');
        // Voting routes - Support both slug and ID
        $this->router->get('/events/{eventSlug}/vote', 'VoteController@showVoting'); // Nominee selection page
        $this->router->get('/events/{eventSlug}/vote/{contestantSlug}', 'VoteController@showVoteForm'); // Vote form for specific nominee
        $this->router->post('/events/{eventSlug}/vote/process', 'VoteController@processVote'); // Process the vote
        
        // Shortcode voting
        $this->router->get('/vote-shortcode', 'VoteController@showShortcodeVoting'); // Shortcode voting page
        $this->router->get('/vote', 'VoteController@showDirectVoting'); // Direct voting with parameters
        $this->router->post('/vote/process', 'VoteController@processDirectVote'); // Process direct vote
        
        // Payment routes
        $this->router->get('/api/payment/status/{transactionId}', 'VoteController@checkPaymentStatus'); // Check payment status
        $this->router->get('/api/payment/test/{transactionId}', 'VoteController@testPaymentStatus'); // Test payment processing
        $this->router->post('/api/payment/callback/{transactionId}', 'VoteController@handlePaymentCallback'); // Handle payment callbacks
        $this->router->get('/api/payment/simulate/{transactionId}', 'VoteController@simulatePaymentCallback'); // Simulate payment callback for testing
        
        // Organizer routes (protected)
        $this->router->group(['middleware' => 'auth'], function($router) {
            $router->get('/organizer', 'OrganizerController@dashboard');
        });
        
        $this->router->group(['prefix' => '/organizer', 'middleware' => 'auth'], function($router) {
            $router->get('/', 'OrganizerController@dashboard');
            $router->get('/subscribe', 'OrganizerController@subscribeToPlan');
            $router->post('/subscribe', 'OrganizerController@subscribeToPlan');
            $router->get('/switch-plan', 'OrganizerController@switchPlan');
            $router->post('/switch-plan', 'OrganizerController@switchPlan');
            
            // Events - Specific routes MUST come before parameterized routes
            $router->get('/events', 'OrganizerController@events');
            $router->get('/events/create', 'OrganizerController@createEvent');
            $router->get('/events/wizard', 'OrganizerController@createEventWizard');
            $router->get('/events/demo', 'OrganizerController@shortcodeDemo');
            $router->get('/events/drafts', 'OrganizerController@draftEvents');
            $router->post('/events', 'OrganizerController@storeEventWizard'); // Redirect old route to wizard
            $router->post('/events/wizard', 'OrganizerController@storeEventWizard');
            // Parameterized routes MUST come last
            $router->get('/events/{id}', 'OrganizerController@showEvent');
            $router->get('/events/{id}/preview', 'OrganizerController@previewEvent');
            $router->get('/events/{id}/edit', 'OrganizerController@editEvent');
            $router->post('/events/{id}/edit', 'OrganizerController@editEvent');
            $router->get('/events/{id}/export-pdf', 'OrganizerController@exportEventPDF');
            $router->post('/events/{id}/publish', 'OrganizerController@publishEvent');
            $router->post('/events/{id}/toggle-results', 'OrganizerController@toggleResults');
            $router->post('/events/{id}/update-status', 'OrganizerController@updateEventStatus');
            
            // Contestants
            $router->get('/contestants', 'OrganizerController@contestants');
            $router->get('/contestants/create', 'OrganizerController@createContestant');
            $router->post('/contestants', 'OrganizerController@storeContestant');
            
            // Categories
            $router->get('/categories', 'OrganizerController@categories');
            $router->post('/categories', 'OrganizerController@storeCategory');
            
            // API endpoints for organizer
            $router->get('/api/events/{id}/categories', 'OrganizerController@getEventCategories');
            
            // Voting
            $router->get('/voting/live', 'OrganizerController@liveResults');
            $router->get('/voting/analytics', 'OrganizerController@votingAnalytics');
            $router->get('/voting/receipts', 'OrganizerController@votingReceipts');
            
            // Financial
            $router->get('/financial/overview', 'OrganizerController@financialOverview');
            $router->get('/financial/revenue', 'OrganizerController@revenueDashboard');
            $router->get('/financial/transactions', 'OrganizerController@transactions');
            $router->get('/financial/payouts', 'OrganizerController@payouts');
            $router->get('/financial/bundles', 'OrganizerController@bundles');
            $router->post('/financial/bundles/create', 'OrganizerController@createBundle');
            $router->post('/financial/bundles/{id}/update', 'OrganizerController@updateBundle');
            $router->post('/financial/bundles/{id}/delete', 'OrganizerController@deleteBundle');
            
            // Marketing
            $router->get('/marketing/coupons', 'OrganizerController@coupons');
            $router->get('/marketing/referrals', 'OrganizerController@referrals');
            $router->get('/marketing/campaigns', 'OrganizerController@campaigns');
            
            // Reports
            $router->get('/reports', 'OrganizerController@reports');
            
            // Settings
            $router->get('/settings/organization', 'OrganizerController@organizationSettings');
            $router->post('/settings/organization', 'OrganizerController@updateOrganizationSettings');
            $router->get('/settings/users', 'OrganizerController@userSettings');
            $router->post('/settings/users', 'OrganizerController@updateUserSettings');
            $router->get('/settings/integrations', 'OrganizerController@integrationSettings');
            $router->post('/settings/integrations', 'OrganizerController@updateIntegrationSettings');
            $router->get('/settings/security', 'OrganizerController@securitySettings');
            $router->post('/settings/security', 'OrganizerController@updateSecuritySettings');
        });
        
        // Admin routes (protected)
        $this->router->group(['prefix' => '/admin', 'middleware' => 'auth'], function($router) {
            $router->get('/', 'AdminController@dashboard');
            $router->get('/events', 'AdminController@events');
            $router->get('/events/create', 'AdminController@createEvent');
            $router->post('/events', 'AdminController@storeEvent');
            $router->get('/events/{id}/edit', 'AdminController@editEvent');
            $router->put('/events/{id}', 'AdminController@updateEvent');
            $router->delete('/events/{id}', 'AdminController@deleteEvent');
            
            $router->get('/contestants', 'AdminController@contestants');
            $router->get('/contestants/create', 'AdminController@createContestant');
            $router->post('/contestants', 'AdminController@storeContestant');
            
            $router->get('/categories', 'AdminController@categories');
            $router->post('/categories', 'AdminController@storeCategory');
            
            $router->get('/users', 'AdminController@users');
            $router->get('/reports', 'AdminController@reports');
        });
        
        // Super Admin routes (protected)
        $this->router->group(['middleware' => 'auth'], function($router) {
            $router->get('/superadmin', 'SuperAdminController@dashboard');
        });
        
        $this->router->group(['prefix' => '/superadmin', 'middleware' => 'auth'], function($router) {
            $router->get('/', 'SuperAdminController@dashboard');
            
            // Platform Management
            $router->get('/platform/overview', 'SuperAdminController@platformOverview');
            $router->get('/platform/analytics', 'SuperAdminController@platformAnalytics');
            $router->get('/platform/performance', 'SuperAdminController@platformPerformance');
            $router->get('/tenants', 'SuperAdminController@tenants');
            $router->get('/tenants/pending', 'SuperAdminController@pendingTenants');
            $router->get('/tenants/suspended', 'SuperAdminController@suspendedTenants');
            $router->get('/tenants/plans', 'SuperAdminController@tenantPlans');
            $router->post('/tenants/plans/create', 'SuperAdminController@createPlan');
            $router->post('/tenants/plans/{id}/update', 'SuperAdminController@updatePlan');
            $router->get('/tenants/plans/{id}/get', 'SuperAdminController@getPlan');
            $router->get('/tenants/plans/{id}/stats', 'SuperAdminController@getPlanStats');
            $router->get('/tenants/plans/{id}/export', 'SuperAdminController@exportPlanStats');
            $router->post('/tenants/plans/{id}/delete', 'SuperAdminController@deletePlan');
            $router->post('/tenants/{id}/change-plan', 'SuperAdminController@changeTenantPlan');
            $router->post('/tenants/{id}/reactivate', 'SuperAdminController@reactivateTenant');
            // Tenant Approval System
            $router->post('/tenants/approve', 'SuperAdminController@approveTenant');
            $router->post('/tenants/reject', 'SuperAdminController@rejectTenant');
            
            // User Management
            $router->get('/users/admins', 'SuperAdminController@platformAdmins');
            $router->get('/users/activity', 'SuperAdminController@userActivity');
            
            // Financial Management
            $router->get('/financial/overview', 'SuperAdminController@financialOverview');
            $router->get('/financial/revenue', 'SuperAdminController@platformRevenue');
            $router->get('/financial/distribution', 'SuperAdminController@revenueDistribution');
            $router->get('/financial/transactions', 'SuperAdminController@allTransactions');
            $router->get('/financial/payouts', 'SuperAdminController@platformPayouts');
            $router->get('/financial/fees', 'SuperAdminController@globalFeeRules');
            $router->post('/financial/fees/create', 'SuperAdminController@createFeeRule');
            $router->post('/financial/fees/update', 'SuperAdminController@updateFeeRule');
            $router->post('/financial/fees/toggle', 'SuperAdminController@toggleFeeRule');
            $router->post('/financial/fees/delete', 'SuperAdminController@deleteFeeRule');
            $router->get('/financial/fees/get', 'SuperAdminController@getFeeRule');
            $router->get('/financial/fees/plan-attachments', 'SuperAdminController@getFeeRulePlanAttachments');
            
            // Security & Compliance
            $router->get('/security/overview', 'SuperAdminController@securityOverview');
            $router->get('/security/fraud', 'SuperAdminController@fraudDetection');
            $router->get('/security/audit', 'SuperAdminController@auditLogs');
            $router->get('/security/blocks', 'SuperAdminController@riskBlocks');
            
            // System Administration
            $router->get('/system/settings', 'SuperAdminController@globalSettings');
            $router->get('/system/maintenance', 'SuperAdminController@systemMaintenance');
            $router->get('/system/backups', 'SuperAdminController@systemBackups');
            $router->get('/system/logs', 'SuperAdminController@systemLogs');
            
            // API Management
            $router->get('/api/overview', 'SuperAdminController@apiOverview');
            $router->get('/api/keys', 'SuperAdminController@apiKeys');
            $router->get('/api/webhooks', 'SuperAdminController@apiWebhooks');
            
            // Reports
            $router->get('/reports', 'SuperAdminController@platformReports');
        });
        
        // API routes
        $this->router->group(['prefix' => '/api'], function($router) {
            $router->get('/events', 'Api\\EventController@index');
            $router->get('/events/{id}', 'Api\\EventController@show');
            $router->get('/events/{id}/results', 'Api\\EventController@results');
            $router->get('/events/search', 'Api\\EventController@search');
            
            $router->post('/vote', 'Api\\VoteController@vote');
            $router->get('/vote/verify', 'Api\\VoteController@verifyReceipt');
            $router->get('/vote/bundles', 'Api\\VoteController@getBundles');
            $router->get('/vote/contestants', 'Api\\VoteController@getContestants');
            
            // Shortcode API routes
            $router->get('/shortcode-test', 'Api\\ShortcodeController@test');
            $router->post('/shortcode-lookup', 'Api\\ShortcodeController@lookup');
            $router->post('/shortcode-validate', 'Api\\ShortcodeController@validateShortcode');
            $router->get('/events/{id}/shortcodes', 'Api\\ShortcodeController@getEventShortcodes');
        });
    }
    
    public function run()
    {
        try {
            $this->router->dispatch();
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    private function handleException(\Exception $e)
    {
        if (APP_DEBUG) {
            echo '<h1>Error</h1>';
            echo '<p>' . $e->getMessage() . '</p>';
            echo '<pre>' . $e->getTraceAsString() . '</pre>';
        } else {
            http_response_code(500);
            echo '<h1>Internal Server Error</h1>';
        }
    }
    
    public function getDatabase()
    {
        return $this->database;
    }
    
    public function getSession()
    {
        return $this->session;
    }
}
