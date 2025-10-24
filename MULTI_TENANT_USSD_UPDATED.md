# Multi-Tenant USSD Implementation - Updated Plan

## ✅ What's Already Working

### **Shortcode System (Already Implemented)**
- ✅ Auto-generated shortcodes for all contestants
- ✅ Format: `AB12` (2 letters + 2 numbers) or `ABC12` (3 letters + 2 numbers)
- ✅ Globally unique across ALL tenants and events
- ✅ Random generation (e.g., `KJ45`, `MN78`, `PQR23`)
- ✅ Stored in `contestant_categories.short_code`
- ✅ Auto-assigned when contestant is added to category

### **Current Shortcode Examples:**
```
Contestant 1: KJ45
Contestant 2: MN78
Contestant 3: PQR23
Contestant 4: AB12
```

**Note:** Shortcodes are NOT tenant-prefixed. They are random and globally unique.

---

## 🎯 Updated Implementation Plan

Since shortcodes are already working, we only need to:
1. Add USSD configuration to tenants
2. Create USSD controller with tenant routing
3. Update USSD session to include tenant context
4. Integrate with existing shortcode system

---

## Step 1: Database Changes (Simplified)

### A. Update Tenants Table
```sql
-- Add USSD configuration to tenants table
ALTER TABLE tenants 
ADD COLUMN ussd_code VARCHAR(10) UNIQUE COMMENT 'USSD suffix code (e.g., 01, 02, 03)',
ADD COLUMN ussd_enabled TINYINT(1) DEFAULT 0 COMMENT 'Enable USSD voting for tenant',
ADD COLUMN ussd_welcome_message TEXT COMMENT 'Custom USSD welcome message';

-- Add index
CREATE INDEX idx_ussd_code ON tenants(ussd_code);

-- Example data
UPDATE tenants SET 
    ussd_code = '01',
    ussd_enabled = 1,
    ussd_welcome_message = 'Welcome to EventCo Voting!'
WHERE id = 1;

UPDATE tenants SET 
    ussd_code = '02',
    ussd_enabled = 1,
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

**Note:** We do NOT need to modify `contestant_categories` table - shortcodes are already there!

---

## Step 2: USSD Controller (Same as before)

### File: `src/Controllers/UssdController.php`

```php
<?php

namespace SmartCast\Controllers;

use SmartCast\Models\Tenant;
use SmartCast\Models\UssdSession;
use SmartCast\Models\Event;

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
    
    public function handleRequest()
    {
        try {
            $sessionId = $_POST['sessionId'] ?? $_GET['sessionId'] ?? null;
            $serviceCode = $_POST['serviceCode'] ?? $_GET['serviceCode'] ?? null;
            $phoneNumber = $_POST['phoneNumber'] ?? $_GET['phoneNumber'] ?? null;
            $text = $_POST['text'] ?? $_GET['text'] ?? '';
            
            error_log("USSD Request - Session: {$sessionId}, Code: {$serviceCode}, Phone: {$phoneNumber}, Text: {$text}");
            
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
                // New session
                $welcomeMessage = $tenant['ussd_welcome_message'] ?? 'Welcome to SmartCast Voting!';
                
                $this->ussdSession->createSession(
                    $sessionId,
                    $phoneNumber,
                    UssdSession::STATE_WELCOME,
                    ['tenant_id' => $tenant['id'], 'service_code' => $serviceCode]
                );
                
                // Update with tenant info
                $this->ussdSession->db->update(
                    'ussd_sessions',
                    ['tenant_id' => $tenant['id'], 'service_code' => $serviceCode],
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
                
                $this->ussdSession->setSessionData($sessionId, 'events', $events);
                
                // Build menu
                $menu = $welcomeMessage . "\n\n";
                $menu .= "Select an event:\n";
                foreach ($events as $index => $event) {
                    $menu .= ($index + 1) . ". " . $event['name'] . "\n";
                }
                $menu .= "0. Exit";
                
                $this->ussdSession->updateSession($sessionId, UssdSession::STATE_SELECT_EVENT);
                
                return $this->ussdResponse($menu);
            }
            
            // Process existing session
            $response = $this->ussdSession->processUssdInput($sessionId, $text);
            return $this->ussdResponse($response['message'], $response['end']);
            
        } catch (\Exception $e) {
            error_log("USSD Error: " . $e->getMessage());
            return $this->ussdResponse('An error occurred. Please try again.', true);
        }
    }
    
    private function getTenantFromServiceCode($serviceCode)
    {
        // Extract: *920*01# → '01'
        if (preg_match('/\*920\*(\d+)#/', $serviceCode, $matches)) {
            $tenantCode = $matches[1];
            $tenant = $this->tenantModel->findAll(['ussd_code' => $tenantCode], null, 1);
            return !empty($tenant) ? $tenant[0] : null;
        }
        return null;
    }
    
    private function ussdResponse($message, $end = false)
    {
        $prefix = $end ? 'END' : 'CON';
        header('Content-Type: text/plain');
        echo $prefix . ' ' . $message;
        exit;
    }
}
```

---

## Step 3: Update UssdSession Model

### Key Changes to `src/Models/UssdSession.php`

```php
// Update handleWelcomeState to filter by tenant
private function handleWelcomeState($sessionId, $input)
{
    $sessionData = $this->getSessionData($sessionId);
    $tenantId = $sessionData['tenant_id'];
    
    // Get active events for THIS TENANT ONLY
    $eventModel = new Event();
    $events = $eventModel->findAll([
        'tenant_id' => $tenantId,
        'status' => 'active'
    ]);
    
    // ... rest of code
}

// Update processVote to integrate with payment
private function processVote($sessionId)
{
    $session = $this->getSession($sessionId);
    $sessionData = $session['data'];
    
    try {
        // Create transaction
        $transactionModel = new Transaction();
        $transactionId = $transactionModel->createTransaction([
            'tenant_id' => $sessionData['tenant_id'], // IMPORTANT: Add tenant_id
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

## Step 4: Shortcode Integration

### The shortcode system already works! Just ensure USSD can look up contestants:

```php
// In UssdSession or UssdController
public function findContestantByShortCode($shortCode, $tenantId)
{
    $contestantCategoryModel = new ContestantCategory();
    
    // Find contestant by shortcode
    $result = $contestantCategoryModel->findByShortCode($shortCode);
    
    if (!$result) {
        return null;
    }
    
    // Verify contestant belongs to tenant's event
    $sql = "
        SELECT c.*, cc.short_code, e.tenant_id
        FROM contestants c
        JOIN contestant_categories cc ON cc.contestant_id = c.id
        JOIN events e ON e.id = c.event_id
        WHERE cc.short_code = :short_code
        AND e.tenant_id = :tenant_id
    ";
    
    return $this->db->selectOne($sql, [
        'short_code' => $shortCode,
        'tenant_id' => $tenantId
    ]);
}
```

---

## Step 5: Tenant Management UI

### Add to Tenant Form (views/organizer/tenants/form.php or similar)

```php
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
        </div>
        
        <div class="mb-3">
            <label class="form-label">Welcome Message</label>
            <textarea class="form-control" 
                      name="ussd_welcome_message" 
                      rows="3"
                      placeholder="Welcome to [Your Brand] Voting!"><?= $tenant['ussd_welcome_message'] ?? '' ?></textarea>
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

## Step 6: Routes

### Add to `src/Core/Application.php`

```php
// USSD routes
$this->router->post('/api/ussd/callback', 'UssdController@handleRequest');
$this->router->get('/api/ussd/callback', 'UssdController@handleRequest');
```

---

## Step 7: Hubtel Setup

### Register USSD Codes

1. **Login to Hubtel Dashboard**
2. **Navigate to USSD → Applications**
3. **Create Application:**
   - Name: SmartCast Multi-Tenant Voting
   - Callback URL: `https://yourdomain.com/api/ussd/callback`
   
4. **Request USSD Codes:**
   - *920*01# → Tenant 1
   - *920*02# → Tenant 2
   - *920*03# → Tenant 3

---

## How It Works

### Example Flow for Tenant 1 (*920*01#):

```
User dials: *920*01#

System:
1. Receives: serviceCode = "*920*01#"
2. Extracts: tenantCode = "01"
3. Finds: Tenant with ussd_code = "01"
4. Shows: Tenant's welcome message
5. Lists: Only Tenant 1's active events
6. User selects event → category → contestant
7. Contestant has shortcode: "KJ45" (already auto-generated)
8. User confirms → Payment initiated
9. User approves on phone → Vote recorded
```

### Shortcode Lookup (Bonus Feature):

```
User dials: *920*01#
Menu: "Enter shortcode or select event"
User enters: KJ45
System: Finds contestant with shortcode "KJ45" in Tenant 1's events
Shows: Contestant details → Vote packages
```

---

## Key Differences from Original Plan

### ✅ **What We Keep:**
- Existing random shortcode generation (`KJ45`, `MN78`)
- Global uniqueness across all tenants
- Auto-generation on contestant assignment

### ❌ **What We Don't Need:**
- ~~Tenant-prefixed shortcodes~~ (e.g., ~~`T1SA001`~~)
- ~~Shortcode prefix in tenant settings~~
- ~~Auto-generate shortcodes function~~ (already exists!)
- ~~Modify contestant_categories table~~ (already has short_code)

### ✅ **What We Add:**
- USSD code per tenant (`01`, `02`, `03`)
- Tenant routing in USSD controller
- Tenant filtering in USSD sessions
- Payment integration in USSD flow

---

## Summary

**Simplified Implementation:**

1. ✅ Add 3 columns to `tenants` table
2. ✅ Add 2 columns to `ussd_sessions` table
3. ✅ Create `UssdController.php`
4. ✅ Update `UssdSession.php` (add tenant filtering + payment)
5. ✅ Add tenant USSD settings UI
6. ✅ Register USSD codes with Hubtel
7. ✅ Test and deploy

**Timeline:** 5-7 days (reduced from 1-2 weeks)

**Shortcodes:** Already working! No changes needed. 🎉

---

Ready to implement? Let me know which step to start with! 🚀
