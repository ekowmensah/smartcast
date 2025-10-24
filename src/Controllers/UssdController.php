<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Tenant;
use SmartCast\Models\UssdSession;
use SmartCast\Models\Event;

/**
 * USSD Controller
 * Handles incoming USSD requests from Hubtel with multi-tenant support
 */
class UssdController extends BaseController
{
    private $ussdSession;
    private $tenantModel;
    private $eventModel;
    
    public function __construct()
    {
        parent::__construct();
        $this->ussdSession = new UssdSession();
        $this->tenantModel = new Tenant();
        $this->eventModel = new Event();
    }
    
    /**
     * Handle incoming USSD request from Hubtel
     * 
     * Hubtel sends POST/GET with:
     * - sessionId: Unique session identifier
     * - serviceCode: USSD code dialed (e.g., *920*01#)
     * - phoneNumber: User's phone number
     * - text: User input (empty for first request)
     */
    public function handleRequest()
    {
        try {
            // Get Hubtel USSD parameters (support both POST and GET)
            $sessionId = $_POST['sessionId'] ?? $_GET['sessionId'] ?? null;
            $serviceCode = $_POST['serviceCode'] ?? $_GET['serviceCode'] ?? null;
            $phoneNumber = $_POST['phoneNumber'] ?? $_GET['phoneNumber'] ?? null;
            $text = $_POST['text'] ?? $_GET['text'] ?? '';
            
            // Log request for debugging
            error_log("USSD Request - Session: {$sessionId}, Code: {$serviceCode}, Phone: {$phoneNumber}, Text: '{$text}'");
            
            // Validate required parameters
            if (!$sessionId || !$serviceCode || !$phoneNumber) {
                error_log("USSD Error: Missing required parameters");
                return $this->ussdResponse('Invalid USSD request. Please try again.', true);
            }
            
            // Extract tenant from service code
            $tenant = $this->getTenantFromServiceCode($serviceCode);
            
            if (!$tenant) {
                error_log("USSD Error: No tenant found for service code: {$serviceCode}");
                return $this->ussdResponse('Service not available. Please contact support.', true);
            }
            
            if (!$tenant['ussd_enabled']) {
                error_log("USSD Error: USSD not enabled for tenant: {$tenant['id']}");
                return $this->ussdResponse('USSD voting is currently disabled for this service.', true);
            }
            
            // Check if session exists
            $session = $this->ussdSession->getSession($sessionId);
            
            if (!$session) {
                // New session - show welcome and event selection
                return $this->handleNewSession($sessionId, $phoneNumber, $serviceCode, $tenant);
            }
            
            // Existing session - process user input
            $response = $this->ussdSession->processUssdInput($sessionId, $text);
            
            return $this->ussdResponse($response['message'], $response['end']);
            
        } catch (\Exception $e) {
            error_log("USSD Error: " . $e->getMessage());
            error_log("Stack trace: " . $e->getTraceAsString());
            return $this->ussdResponse('An error occurred. Please try again later.', true);
        }
    }
    
    /**
     * Handle new USSD session
     */
    private function handleNewSession($sessionId, $phoneNumber, $serviceCode, $tenant)
    {
        // Get tenant's active events
        $events = $this->eventModel->findAll([
            'tenant_id' => $tenant['id'],
            'status' => 'active'
        ]);
        
        if (empty($events)) {
            error_log("USSD: No active events for tenant {$tenant['id']}");
            return $this->ussdResponse('No active voting events available at this time.', true);
        }
        
        // Create new session
        $this->ussdSession->createSession(
            $sessionId,
            $phoneNumber,
            UssdSession::STATE_WELCOME,
            [
                'tenant_id' => $tenant['id'],
                'service_code' => $serviceCode,
                'events' => $events
            ]
        );
        
        // Update session with tenant info
        $this->ussdSession->updateSessionColumns($sessionId, [
            'tenant_id' => $tenant['id'],
            'service_code' => $serviceCode
        ]);
        
        // Build welcome menu
        $welcomeMessage = $tenant['ussd_welcome_message'] ?? 'Welcome to SmartCast Voting!';
        
        $menu = $welcomeMessage . "\n\n";
        $menu .= "Select an event:\n";
        
        foreach ($events as $index => $event) {
            $menu .= ($index + 1) . ". " . $event['name'] . "\n";
        }
        $menu .= "0. Exit";
        
        // Update state to select event
        $this->ussdSession->updateSession($sessionId, UssdSession::STATE_SELECT_EVENT);
        
        error_log("USSD: New session created for tenant {$tenant['id']}, " . count($events) . " events available");
        
        return $this->ussdResponse($menu);
    }
    
    /**
     * Extract tenant from service code
     * 
     * Examples:
     * *920*01# → tenant with ussd_code = '01'
     * *920*02# → tenant with ussd_code = '02'
     * *920# → null (base code without tenant)
     */
    private function getTenantFromServiceCode($serviceCode)
    {
        // Extract tenant code from service code
        // Format: *920*XX# where XX is the tenant code
        
        if (preg_match('/\*920\*(\d+)#/', $serviceCode, $matches)) {
            $tenantCode = $matches[1];
            
            error_log("USSD: Extracted tenant code: {$tenantCode}");
            
            // Find tenant by USSD code
            $tenant = $this->tenantModel->findAll(['ussd_code' => $tenantCode], null, 1);
            
            if (!empty($tenant)) {
                error_log("USSD: Found tenant: {$tenant[0]['name']} (ID: {$tenant[0]['id']})");
                return $tenant[0];
            }
            
            error_log("USSD: No tenant found with code: {$tenantCode}");
        } else {
            error_log("USSD: Service code format not recognized: {$serviceCode}");
        }
        
        return null;
    }
    
    /**
     * Format USSD response for Hubtel
     * 
     * @param string $message Message to display to user
     * @param bool $end Whether to end the session
     * @return void
     */
    private function ussdResponse($message, $end = false)
    {
        // Hubtel expects plain text response with prefix:
        // CON = Continue session (show menu, wait for input)
        // END = End session (final message)
        
        $prefix = $end ? 'END' : 'CON';
        $response = $prefix . ' ' . $message;
        
        // Log response
        error_log("USSD Response ({$prefix}): " . substr($message, 0, 100) . (strlen($message) > 100 ? '...' : ''));
        
        // Set content type to plain text
        header('Content-Type: text/plain; charset=utf-8');
        
        // Output response and exit
        echo $response;
        exit;
    }
}
