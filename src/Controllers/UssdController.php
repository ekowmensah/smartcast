<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Tenant;
use SmartCast\Models\UssdSession;
use SmartCast\Models\Event;
use SmartCast\Helpers\UssdHelper;

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
            // Log raw input for debugging
            $rawInput = file_get_contents('php://input');
            error_log("USSD Raw Input: " . $rawInput);
            
            // Hubtel sends JSON data
            $input = json_decode($rawInput, true);
            
            // If JSON parsing failed, try form data (for testing)
            if (!$input) {
                $input = $_POST ?: $_GET;
                error_log("USSD: Using form data - " . json_encode($input));
            } else {
                error_log("USSD: Parsed JSON - " . json_encode($input));
            }
            
            // Get Hubtel USSD parameters (Hubtel uses capitalized keys)
            $sessionId = $input['SessionId'] ?? $input['sessionId'] ?? null;
            $serviceCode = $input['ServiceCode'] ?? $input['serviceCode'] ?? null;
            $phoneNumber = $input['Mobile'] ?? $input['phoneNumber'] ?? null;
            $text = $input['Message'] ?? $input['text'] ?? '';
            $type = $input['Type'] ?? $input['type'] ?? 'Initiation';
            
            // Log parsed parameters
            error_log("USSD Parsed - Type: {$type}, Session: {$sessionId}, Code: {$serviceCode}, Phone: {$phoneNumber}, Text: '{$text}'");
            
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
        
        // Build main menu
        if (!empty($tenant['ussd_welcome_message'])) {
            // Truncate custom welcome message if too long
            $customMessage = $tenant['ussd_welcome_message'];
            // If it starts with "Welcome to", extract the part after it
            if (stripos($customMessage, 'Welcome to ') === 0) {
                $namePart = substr($customMessage, 11); // Remove "Welcome to "
                if (strlen($namePart) > 15) {
                    $namePart = substr($namePart, 0, 15);
                }
                $welcomeMessage = "Welcome to {$namePart}";
            } else {
                // If custom format, just truncate to reasonable length
                if (strlen($customMessage) > 30) {
                    $customMessage = substr($customMessage, 0, 30);
                }
                $welcomeMessage = $customMessage;
            }
        } else {
            // Use tenant name, truncate if too long
            $tenantName = $tenant['name'] ?? 'SmartCastGH';
            if (strlen($tenantName) > 15) {
                $tenantName = substr($tenantName, 0, 15);
            }
            $welcomeMessage = "Welcome to {$tenantName}!";
        }
        
        $menu = $welcomeMessage . "\n\n";
        $menu .= "1. Vote for Nominee\n";
        $menu .= "2. Vote on an Event\n";
        $menu .= "3. Get Support\n";
        $menu .= "4. Exit";
        
        // Update state to main menu
        $this->ussdSession->updateSession($sessionId, UssdSession::STATE_MAIN_MENU);
        
        error_log("USSD: New session created for tenant {$tenant['id']}, " . count($events) . " events available");
        
        return $this->ussdResponse($menu);
    }
    
    /**
     * Extract tenant from service code
     * 
     * Examples:
     * *711*01# → tenant with ussd_code = '01'
     * *711*02# → tenant with ussd_code = '02'
     * *711# → null (base code without tenant)
     * 
     * Uses dynamic base code from config
     */
    private function getTenantFromServiceCode($serviceCode)
    {
        // Use UssdHelper to extract tenant code dynamically
        $tenantCode = UssdHelper::extractTenantCode($serviceCode);
        
        if ($tenantCode) {
            error_log("USSD: Extracted tenant code: {$tenantCode} from service code: {$serviceCode}");
            
            // Find tenant by USSD code
            $tenant = $this->tenantModel->findAll(['ussd_code' => $tenantCode], null, 1);
            
            if (!empty($tenant)) {
                error_log("USSD: Found tenant: {$tenant[0]['name']} (ID: {$tenant[0]['id']})");
                return $tenant[0];
            }
            
            error_log("USSD: No tenant found with code: {$tenantCode}");
        } else {
            error_log("USSD: Could not extract tenant code from service code: {$serviceCode}");
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
