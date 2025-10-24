# Multi-Tenant USSD Implementation Plan

## Architecture Overview

### **USSD Code Structure**
```
Base Code: *920#
Tenant Codes: *920*01#, *920*02#, *920*03#, etc.

Example:
- Tenant 1 (EventCo):   *920*01#
- Tenant 2 (AwardsGH):  *920*02#
- Tenant 3 (MusicVote): *920*03#
```

---

## Step 1: Database Changes

### A. Update Tenants Table
```sql
-- Add USSD configuration to tenants table
ALTER TABLE tenants 
ADD COLUMN ussd_code VARCHAR(10) UNIQUE COMMENT 'USSD suffix code (e.g., 01, 02, 03)',
ADD COLUMN ussd_enabled TINYINT(1) DEFAULT 0 COMMENT 'Enable USSD voting for tenant',
ADD COLUMN shortcode_prefix VARCHAR(10) COMMENT 'Prefix for contestant shortcodes (e.g., EC, AG)',
ADD COLUMN ussd_welcome_message TEXT COMMENT 'Custom USSD welcome message';

-- Add index
CREATE INDEX idx_ussd_code ON tenants(ussd_code);

-- Example data
UPDATE tenants SET 
    ussd_code = '01',
    ussd_enabled = 1,
    shortcode_prefix = 'EC',
    ussd_welcome_message = 'Welcome to EventCo Voting!'
WHERE id = 1;

UPDATE tenants SET 
    ussd_code = '02',
    ussd_enabled = 1,
    shortcode_prefix = 'AG',
    ussd_welcome_message = 'Welcome to AwardsGH!'
WHERE id = 2;
```

### B. Update USSD Sessions Table
```sql
-- Add tenant context to USSD sessions
ALTER TABLE ussd_sessions 
ADD COLUMN tenant_id INT(11) COMMENT 'Tenant ID for this session',
ADD COLUMN service_code VARCHAR(20) COMMENT 'Full USSD code dialed (e.g., *920*01#)';

-- Add foreign key
ALTER TABLE ussd_sessions 
ADD CONSTRAINT fk_ussd_tenant 
FOREIGN KEY (tenant_id) REFERENCES tenants(id) ON DELETE CASCADE;

-- Add index
CREATE INDEX idx_ussd_tenant ON ussd_sessions(tenant_id);
```

### C. Update Contestant Categories for Shortcodes
```sql
-- Ensure short_code column exists and is indexed
ALTER TABLE contestant_categories 
MODIFY COLUMN short_code VARCHAR(20) UNIQUE;

-- Add index for faster lookups
CREATE INDEX idx_short_code ON contestant_categories(short_code);

-- Add tenant_id for scoped shortcodes
ALTER TABLE contestant_categories 
ADD COLUMN tenant_id INT(11) COMMENT 'Tenant ID for scoped shortcodes';

CREATE INDEX idx_tenant_shortcode ON contestant_categories(tenant_id, short_code);
```

---

## Step 2: Create USSD Controller

### File: `src/Controllers/UssdController.php`

```php
<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Tenant;
use SmartCast\Models\UssdSession;
use SmartCast\Models\Event;

/**
 * USSD Controller
 * Handles incoming USSD requests from Hubtel
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
     * Hubtel sends:
     * - sessionId: Unique session identifier
     * - serviceCode: USSD code dialed (e.g., *920*01#)
     * - phoneNumber: User's phone number
     * - text: User input (empty for first request)
     */
    public function handleRequest()
    {
        try {
            // Get Hubtel USSD parameters
            $sessionId = $_POST['sessionId'] ?? $_GET['sessionId'] ?? null;
            $serviceCode = $_POST['serviceCode'] ?? $_GET['serviceCode'] ?? null;
            $phoneNumber = $_POST['phoneNumber'] ?? $_GET['phoneNumber'] ?? null;
            $text = $_POST['text'] ?? $_GET['text'] ?? '';
            
            // Log request for debugging
            error_log("USSD Request - Session: {$sessionId}, Code: {$serviceCode}, Phone: {$phoneNumber}, Text: {$text}");
            
            // Validate required parameters
            if (!$sessionId || !$serviceCode || !$phoneNumber) {
                return $this->ussdResponse('Invalid USSD request', true);
            }
            
            // Extract tenant from service code
            $tenant = $this->getTenantFromServiceCode($serviceCode);
            
            if (!$tenant) {
                return $this->ussdResponse('Service not available', true);
            }
            
            if (!$tenant['ussd_enabled']) {
                return $this->ussdResponse('USSD voting is not enabled for this service', true);
            }
            
            // Check if session exists
            $session = $this->ussdSession->getSession($sessionId);
            
            if (!$session) {
                // New session - create it
                $welcomeMessage = $tenant['ussd_welcome_message'] ?? 'Welcome to SmartCast Voting!';
                
                $session = $this->ussdSession->createSession(
                    $sessionId,
                    $phoneNumber,
                    UssdSession::STATE_WELCOME,
                    [
                        'tenant_id' => $tenant['id'],
                        'service_code' => $serviceCode
                    ]
                );
                
                // Update session with tenant and service code
                $this->ussdSession->db->update(
                    'ussd_sessions',
                    [
                        'tenant_id' => $tenant['id'],
                        'service_code' => $serviceCode
                    ],
                    'session_id = :session_id',
                    ['session_id' => $sessionId]
                );
                
                // Get tenant's active events
                $events = $this->eventModel->findAll([
                    'tenant_id' => $tenant['id'],
                    'status' => 'active'
                ]);
                
                if (empty($events)) {
                    return $this->ussdResponse('No active events available', true);
                }
                
                // Store events in session
                $this->ussdSession->setSessionData($sessionId, 'events', $events);
                
                // Build welcome menu
                $menu = $welcomeMessage . "\n\n";
                $menu .= "Select an event:\n";
                
                foreach ($events as $index => $event) {
                    $menu .= ($index + 1) . ". " . $event['name'] . "\n";
                }
                $menu .= "0. Exit";
                
                // Update state to select event
                $this->ussdSession->updateSession($sessionId, UssdSession::STATE_SELECT_EVENT);
                
                return $this->ussdResponse($menu);
            }
            
            // Existing session - process input
            $response = $this->ussdSession->processUssdInput($sessionId, $text);
            
            return $this->ussdResponse($response['message'], $response['end']);
            
        } catch (\Exception $e) {
            error_log("USSD Error: " . $e->getMessage());
            return $this->ussdResponse('An error occurred. Please try again.', true);
        }
    }
    
    /**
     * Extract tenant from service code
     * 
     * Examples:
     * *920*01# → tenant with ussd_code = '01'
     * *920*02# → tenant with ussd_code = '02'
     */
    private function getTenantFromServiceCode($serviceCode)
    {
        // Extract tenant code from service code
        // Format: *920*XX# where XX is the tenant code
        
        if (preg_match('/\*920\*(\d+)#/', $serviceCode, $matches)) {
            $tenantCode = $matches[1];
            
            // Find tenant by USSD code
            $tenant = $this->tenantModel->findAll(['ussd_code' => $tenantCode], null, 1);
            
            if (!empty($tenant)) {
                return $tenant[0];
            }
        }
        
        return null;
    }
    
    /**
     * Format USSD response for Hubtel
     * 
     * @param string $message Message to display
     * @param bool $end Whether to end the session
     * @return void
     */
    private function ussdResponse($message, $end = false)
    {
        // Hubtel expects plain text response
        // CON = Continue session
        // END = End session
        
        $prefix = $end ? 'END' : 'CON';
        $response = $prefix . ' ' . $message;
        
        // Set content type
        header('Content-Type: text/plain');
        
        // Output response
        echo $response;
        exit;
    }
}
```

---

## Step 3: Update UssdSession Model

### File: `src/Models/UssdSession.php`

Add tenant filtering to all queries:

```php
// Update handleWelcomeState to filter by tenant
private function handleWelcomeState($sessionId, $input)
{
    $sessionData = $this->getSessionData($sessionId);
    $tenantId = $sessionData['tenant_id'];
    
    // Get active events for this tenant only
    $eventModel = new Event();
    $events = $eventModel->findAll([
        'tenant_id' => $tenantId,
        'status' => 'active'
    ]);
    
    // ... rest of the code
}

// Update processVote to include tenant_id
private function processVote($sessionId)
{
    $session = $this->getSession($sessionId);
    $sessionData = $session['data'];
    
    try {
        // Create transaction with tenant_id
        $transactionModel = new Transaction();
        $transactionId = $transactionModel->createTransaction([
            'tenant_id' => $sessionData['tenant_id'], // Add this
            'event_id' => $sessionData['selected_event']['id'],
            'contestant_id' => $sessionData['selected_contestant']['id'],
            'bundle_id' => $sessionData['selected_bundle']['id'],
            'amount' => $sessionData['selected_bundle']['price'],
            'msisdn' => $session['msisdn'],
            'status' => 'pending', // Changed from 'success'
            'provider' => 'ussd'
        ]);
        
        // Initiate mobile money payment
        $paymentService = new \SmartCast\Services\PaymentService();
        $paymentResult = $paymentService->initializeMobileMoneyPayment([
            'amount' => $sessionData['selected_bundle']['price'],
            'phone' => $session['msisdn'],
            'description' => "Vote for {$sessionData['selected_contestant']['name']}",
            'callback_url' => APP_URL . "/api/payment/callback/{$transactionId}",
            'tenant_id' => $sessionData['tenant_id'],
            'voting_transaction_id' => $transactionId,
            'related_id' => $transactionId,
            'metadata' => [
                'transaction_id' => $transactionId,
                'event_id' => $sessionData['selected_event']['id'],
                'contestant_id' => $sessionData['selected_contestant']['id'],
                'votes' => $sessionData['selected_bundle']['votes'],
                'source' => 'ussd'
            ]
        ]);
        
        if ($paymentResult['success']) {
            $this->updateSession($sessionId, self::STATE_PAYMENT);
            
            $message = "Payment initiated!\n";
            $message .= "Please approve the payment on your phone.\n";
            $message .= "Amount: GHS " . number_format($sessionData['selected_bundle']['price'], 2) . "\n";
            $message .= "Your vote will be recorded after payment approval.";
            
            return $this->createResponse($message, true);
        } else {
            throw new \Exception($paymentResult['message']);
        }
        
    } catch (\Exception $e) {
        error_log("USSD Vote Error: " . $e->getMessage());
        $this->updateSession($sessionId, self::STATE_ERROR);
        return $this->createResponse('Vote failed. Please try again later.', true);
    }
}
```

---

## Step 4: Add Routes

### File: `src/Core/Application.php`

```php
// Add USSD routes
$this->router->post('/api/ussd/callback', 'UssdController@handleRequest');
$this->router->get('/api/ussd/callback', 'UssdController@handleRequest');
```

---

## Step 5: Tenant Management UI

### Add USSD Settings to Tenant Form

```php
<!-- In tenant create/edit form -->
<div class="card mb-4">
    <div class="card-header">
        <h5><i class="fas fa-mobile-alt me-2"></i>USSD Configuration</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">USSD Code</label>
                    <div class="input-group">
                        <span class="input-group-text">*920*</span>
                        <input type="text" 
                               class="form-control" 
                               name="ussd_code" 
                               placeholder="01"
                               pattern="[0-9]{2}"
                               maxlength="2"
                               value="<?= $tenant['ussd_code'] ?? '' ?>">
                        <span class="input-group-text">#</span>
                    </div>
                    <small class="text-muted">2-digit code (e.g., 01, 02, 03)</small>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Shortcode Prefix</label>
                    <input type="text" 
                           class="form-control" 
                           name="shortcode_prefix" 
                           placeholder="EC"
                           maxlength="10"
                           value="<?= $tenant['shortcode_prefix'] ?? '' ?>">
                    <small class="text-muted">Prefix for contestant codes (e.g., EC, AG)</small>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label class="form-label">Welcome Message</label>
            <textarea class="form-control" 
                      name="ussd_welcome_message" 
                      rows="3"
                      placeholder="Welcome to [Your Brand] Voting!"><?= $tenant['ussd_welcome_message'] ?? '' ?></textarea>
            <small class="text-muted">Custom message shown when users dial your USSD code</small>
        </div>
        
        <div class="form-check">
            <input class="form-check-input" 
                   type="checkbox" 
                   name="ussd_enabled" 
                   id="ussd_enabled"
                   value="1"
                   <?= ($tenant['ussd_enabled'] ?? 0) ? 'checked' : '' ?>>
            <label class="form-check-label" for="ussd_enabled">
                Enable USSD Voting
            </label>
        </div>
        
        <?php if (!empty($tenant['ussd_code'])): ?>
        <div class="alert alert-info mt-3">
            <i class="fas fa-info-circle me-2"></i>
            <strong>Your USSD Code:</strong> *920*<?= $tenant['ussd_code'] ?>#
        </div>
        <?php endif; ?>
    </div>
</div>
```

---

## Step 6: Shortcode Auto-Generation

### Update ContestantCategory Model

```php
/**
 * Generate unique shortcode for contestant in category
 */
public function generateShortCode($contestantId, $categoryId, $tenantId)
{
    // Get tenant prefix
    $tenantModel = new Tenant();
    $tenant = $tenantModel->find($tenantId);
    $prefix = $tenant['shortcode_prefix'] ?? 'SC';
    
    // Get category code (first 2 letters)
    $categoryModel = new Category();
    $category = $categoryModel->find($categoryId);
    $categoryCode = strtoupper(substr($category['name'], 0, 2));
    
    // Find next available number
    $sql = "
        SELECT short_code 
        FROM contestant_categories 
        WHERE tenant_id = :tenant_id 
        AND short_code LIKE :pattern 
        ORDER BY short_code DESC 
        LIMIT 1
    ";
    
    $lastCode = $this->db->selectOne($sql, [
        'tenant_id' => $tenantId,
        'pattern' => $prefix . $categoryCode . '%'
    ]);
    
    if ($lastCode) {
        // Extract number and increment
        preg_match('/(\d+)$/', $lastCode['short_code'], $matches);
        $number = isset($matches[1]) ? (int)$matches[1] + 1 : 1;
    } else {
        $number = 1;
    }
    
    // Format: PREFIX + CATEGORY + NUMBER (e.g., ECSA001)
    $shortCode = $prefix . $categoryCode . str_pad($number, 3, '0', STR_PAD_LEFT);
    
    return $shortCode;
}

/**
 * Auto-generate shortcodes for all contestants in an event
 */
public function generateShortCodesForEvent($eventId)
{
    $sql = "
        SELECT cc.id, cc.contestant_id, cc.category_id, e.tenant_id
        FROM contestant_categories cc
        JOIN events e ON e.id = :event_id
        WHERE cc.event_id = :event_id
        AND (cc.short_code IS NULL OR cc.short_code = '')
    ";
    
    $contestants = $this->db->select($sql, ['event_id' => $eventId]);
    
    foreach ($contestants as $contestant) {
        $shortCode = $this->generateShortCode(
            $contestant['contestant_id'],
            $contestant['category_id'],
            $contestant['tenant_id']
        );
        
        $this->db->update(
            'contestant_categories',
            [
                'short_code' => $shortCode,
                'tenant_id' => $contestant['tenant_id']
            ],
            'id = :id',
            ['id' => $contestant['id']]
        );
    }
    
    return count($contestants);
}
```

---

## Step 7: Hubtel USSD Setup

### A. Register USSD Code with Hubtel

1. **Login to Hubtel Dashboard**
2. **Navigate to USSD Services**
3. **Create New USSD Application**
   - Name: SmartCast Voting
   - Short Code: *920# (base code)
   - Callback URL: `https://yourdomain.com/api/ussd/callback`

4. **Configure Sub-codes**
   - *920*01# → Tenant 1
   - *920*02# → Tenant 2
   - *920*03# → Tenant 3

### B. Webhook Configuration

Hubtel will send POST requests to your callback URL with:
```
sessionId: Unique session ID
serviceCode: *920*01# (full code dialed)
phoneNumber: User's phone number
text: User input (empty for first request)
```

### C. Testing

Use Hubtel's USSD Simulator:
1. Go to Hubtel Dashboard → USSD → Simulator
2. Enter phone number
3. Dial *920*01#
4. Test the flow

---

## Step 8: Deployment Checklist

### Before Going Live:

- [ ] Database migrations completed
- [ ] USSD controller created
- [ ] Routes added
- [ ] Tenant USSD codes configured
- [ ] Shortcodes auto-generated
- [ ] Hubtel USSD code registered
- [ ] Webhook URL configured in Hubtel
- [ ] SSL certificate active (HTTPS required)
- [ ] Test with Hubtel simulator
- [ ] Test with real phone numbers
- [ ] Monitor logs for errors
- [ ] Set up error alerts

---

## Usage Examples

### Tenant 1 (EventCo) - *920*01#

```
User dials: *920*01#

Response:
"Welcome to EventCo Voting!

Select an event:
1. Music Awards 2025
2. Sports Gala
0. Exit"

User enters: 1

Response:
"Music Awards 2025
Select a category:
1. Best Artist
2. Best Song
0. Back"

... and so on
```

### Tenant 2 (AwardsGH) - *920*02#

```
User dials: *920*02#

Response:
"Welcome to AwardsGH!

Select an event:
1. Ghana Awards 2025
0. Exit"
```

---

## Monitoring & Analytics

### Track USSD Usage

```php
// Add to UssdController
private function logUssdActivity($sessionId, $action, $data = [])
{
    $sql = "
        INSERT INTO ussd_activity_log 
        (session_id, action, data, created_at) 
        VALUES (:session_id, :action, :data, NOW())
    ";
    
    $this->db->execute($sql, [
        'session_id' => $sessionId,
        'action' => $action,
        'data' => json_encode($data)
    ]);
}
```

### Analytics Queries

```sql
-- USSD sessions by tenant
SELECT 
    t.name as tenant_name,
    COUNT(DISTINCT us.session_id) as total_sessions,
    COUNT(DISTINCT us.msisdn) as unique_users,
    SUM(CASE WHEN us.state = 'success' THEN 1 ELSE 0 END) as successful_votes
FROM ussd_sessions us
JOIN tenants t ON t.id = us.tenant_id
WHERE us.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
GROUP BY t.id;

-- Most popular USSD codes
SELECT 
    service_code,
    COUNT(*) as usage_count
FROM ussd_sessions
GROUP BY service_code
ORDER BY usage_count DESC;
```

---

## Cost Estimates

### Hubtel USSD Pricing (Ghana):
- **Registration:** ~GHS 500-1000 per code
- **Per Session:** ~GHS 0.01-0.02
- **Monthly Fee:** ~GHS 100-200 per code

### Example Monthly Cost (3 Tenants):
- 3 USSD codes: GHS 300-600/month
- 10,000 sessions: GHS 100-200
- **Total:** ~GHS 400-800/month

---

## Next Steps

1. **Run database migrations**
2. **Create UssdController**
3. **Update UssdSession model**
4. **Add tenant USSD settings UI**
5. **Test locally**
6. **Register with Hubtel**
7. **Deploy and test live**

Ready to start implementation? 🚀
