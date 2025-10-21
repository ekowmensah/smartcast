# Hubtel Direct Receive Money Integration Recommendation
## SmartCast Voting Platform

**Date:** October 21, 2025  
**Security Feature:** OTP Verification for Unregistered Users (Option 2)

---

## Executive Summary

This document provides a comprehensive recommendation for integrating Hubtel's Direct Receive Money API into the SmartCast voting platform using the **OTP security feature for unregistered users**. The integration will complement the existing Paystack gateway and provide a robust, secure mobile money payment solution.

---

## Current System Analysis

### Existing Architecture
1. **Payment Gateway System**
   - Multi-gateway support via `payment_gateways` table
   - Currently active: Paystack (priority 1)
   - Hubtel gateway exists but inactive (priority 2)
   - Gateway abstraction through `PaymentService` class

2. **Payment Flow**
   - `PaymentService` → Gateway Selection → Gateway Implementation → Webhook Processing
   - Supports mobile money, card, and bank transfer
   - Transaction tracking via `payment_transactions` table
   - Vote processing after successful payment verification

3. **Existing OTP Infrastructure**
   - `OtpRequest` model with full OTP lifecycle management
   - 6-digit OTP generation with password hashing
   - 5-minute expiry window
   - Rate limiting (3 attempts per 60 minutes)
   - SMS integration capability

4. **Voting Flow**
   - User selects contestant and vote bundle
   - Payment initiated via mobile money
   - Popup-based payment for Paystack
   - Webhook/callback verification
   - Vote casting upon successful payment

---

## Recommended Integration Architecture

### 1. Gateway Implementation Layer

#### Create HubtelGateway Service
**File:** `src/Services/Gateways/HubtelGateway.php`

**Key Responsibilities:**
- Implement Hubtel Direct Receive Money API
- Handle OTP verification flow for unregistered users
- Process Hubtel callbacks
- Map Hubtel response codes to internal status
- Implement IP whitelisting validation

**Core Methods:**
```php
- initializeMobileMoneyPayment($data)
- verifyPayment($reference)
- processWebhook($payload, $signature)
- checkTransactionStatus($clientReference)
- formatPhoneNumber($phone)
- detectMobileMoneyProvider($phone)
- mapHubtelChannel($provider)
```

**Channel Mapping:**
```
MTN Ghana    → mtn-gh
Telecel      → vodafone-gh (formerly Vodafone)
AirtelTigo   → tigo-gh
```

---

### 2. OTP Security Implementation

#### Two-Step Payment Flow for Unregistered Users

**Step 1: OTP Generation & Verification**
```
User enters phone number → Check if registered user
  ├─ Registered: Skip OTP, proceed to payment
  └─ Unregistered: Generate OTP → Send via SMS → Verify OTP
```

**Step 2: Payment Initiation**
```
OTP Verified → Lock phone number (non-editable)
  → Initiate Hubtel payment → User approves on phone
  → Callback received → Process vote
```

#### Implementation Components

**A. OTP Controller Extension**
- Add `sendPaymentOtp($phone)` method
- Add `verifyPaymentOtp($phone, $otp)` method
- Session management for verified phone numbers
- 5-minute OTP validity window

**B. Frontend OTP Flow**
```javascript
1. User enters phone number
2. Click "Send OTP" button
3. Enter 6-digit OTP code
4. Verify OTP → Lock phone field
5. Proceed to payment
```

**C. Security Features**
- Rate limiting: 3 OTP requests per hour per phone
- OTP expiry: 5 minutes
- One-time use OTPs (consumed flag)
- Phone number locking after verification
- Session timeout: 10 minutes after OTP verification

---

### 3. Payment Service Integration

#### Modify PaymentService Class

**Gateway Selection Logic:**
```php
public function getActiveGateway($paymentMethod, $preferences = [])
{
    // Priority-based selection
    // 1. Check tenant-specific gateway preference
    // 2. Check user's last successful gateway
    // 3. Fall back to default priority order
    // 4. Support gateway failover
}
```

**Failover Strategy:**
```
Primary Gateway Fails → Log Error → Try Secondary Gateway
  → Both Fail → Return user-friendly error
```

#### Gateway Configuration Update

**Update `payment_gateways` table:**
```sql
UPDATE payment_gateways 
SET 
  config = '{
    "client_id": "YOUR_HUBTEL_CLIENT_ID",
    "client_secret": "YOUR_HUBTEL_CLIENT_SECRET",
    "merchant_account": "YOUR_POS_SALES_ID",
    "base_url": "https://rmp.hubtel.com",
    "status_check_url": "https://api-txnstatus.hubtel.com",
    "currency": "GHS",
    "callback_url": "https://yourdomain.com/api/payment/webhook/hubtel",
    "ip_whitelist": ["YOUR_SERVER_IP_1", "YOUR_SERVER_IP_2"]
  }',
  is_active = 1
WHERE provider = 'hubtel';
```

---

### 4. Webhook & Callback Handling

#### Hubtel Callback Processing

**Endpoint:** `/api/payment/webhook/hubtel`

**Callback Validation:**
1. Verify IP address is whitelisted
2. Validate callback structure
3. Check ResponseCode (0000 = success, 2001 = failed)
4. Update payment transaction status
5. Process vote if successful

**Response Code Mapping:**
```php
'0000' => 'success',      // Transaction successful
'0001' => 'pending',      // Awaiting approval
'2001' => 'failed',       // Payment failed
'4000' => 'validation_error',
'4070' => 'insufficient_funds',
'4101' => 'configuration_error',
'4103' => 'permission_denied'
```

#### Status Check Implementation

**When to Use:**
- No callback received after 5 minutes
- User reports payment but status unknown
- Scheduled reconciliation jobs

**Implementation:**
```php
public function checkHubtelTransactionStatus($clientReference)
{
    // Call Hubtel Status Check API
    // Update local transaction status
    // Process vote if paid
    // Return current status
}
```

---

### 5. Database Schema Updates

#### Add OTP Tracking for Payments

**New Table:** `payment_otp_verifications`
```sql
CREATE TABLE payment_otp_verifications (
  id INT PRIMARY KEY AUTO_INCREMENT,
  phone_number VARCHAR(20) NOT NULL,
  otp_request_id INT NOT NULL,
  verified_at DATETIME NOT NULL,
  expires_at DATETIME NOT NULL,
  session_token VARCHAR(64) NOT NULL,
  used_for_payment TINYINT(1) DEFAULT 0,
  payment_transaction_id INT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_phone_session (phone_number, session_token),
  INDEX idx_expires (expires_at),
  FOREIGN KEY (otp_request_id) REFERENCES otp_requests(id)
);
```

#### Extend payment_transactions Table

**Add Columns:**
```sql
ALTER TABLE payment_transactions
ADD COLUMN otp_verified TINYINT(1) DEFAULT 0,
ADD COLUMN otp_verification_id INT NULL,
ADD COLUMN gateway_provider VARCHAR(50) NULL,
ADD COLUMN external_transaction_id VARCHAR(100) NULL,
ADD INDEX idx_external_txn (external_transaction_id);
```

---

### 6. User Experience Flow

#### Complete Voting Journey with OTP

**Scenario: Unregistered User Voting**

```
1. User selects contestant and vote bundle
   ↓
2. User enters phone number (0545644749)
   ↓
3. System checks: User not registered
   ↓
4. "Verify Phone Number" button appears
   ↓
5. User clicks → OTP sent via SMS
   ↓
6. User enters 6-digit OTP (e.g., 123456)
   ↓
7. System verifies OTP → Success
   ↓
8. Phone number field locked (non-editable)
   ↓
9. "Proceed to Payment" button enabled
   ↓
10. User clicks → Hubtel payment initiated
    ↓
11. User receives mobile money prompt on phone
    ↓
12. User enters PIN and approves
    ↓
13. Hubtel sends callback to SmartCast
    ↓
14. System verifies payment → Casts votes
    ↓
15. Success message displayed with receipt
```

**Scenario: Registered User Voting**

```
1. User selects contestant and vote bundle
   ↓
2. User enters registered phone number
   ↓
3. System detects: Registered user
   ↓
4. Skip OTP → Proceed directly to payment
   ↓
5. Payment initiated → Vote processed
```

---

### 7. Frontend Implementation

#### OTP Verification UI Component

**Location:** `views/voting/vote-form.php`

**UI Elements:**
```html
<!-- Phone Number Input -->
<input type="tel" id="phone-number" placeholder="0545644749">
<button id="send-otp-btn">Send OTP</button>

<!-- OTP Input (hidden initially) -->
<div id="otp-section" style="display:none;">
  <input type="text" id="otp-code" maxlength="6" placeholder="Enter 6-digit OTP">
  <button id="verify-otp-btn">Verify OTP</button>
  <button id="resend-otp-btn">Resend OTP</button>
  <span id="otp-timer">5:00</span>
</div>

<!-- Payment Button (disabled until OTP verified) -->
<button id="proceed-payment-btn" disabled>Proceed to Payment</button>
```

**JavaScript Flow:**
```javascript
// 1. Send OTP
sendOtpBtn.onclick = async () => {
  const phone = phoneInput.value;
  const response = await fetch('/api/otp/send-payment-otp', {
    method: 'POST',
    body: JSON.stringify({ phone })
  });
  
  if (response.ok) {
    showOtpSection();
    startOtpTimer(300); // 5 minutes
  }
};

// 2. Verify OTP
verifyOtpBtn.onclick = async () => {
  const otp = otpInput.value;
  const response = await fetch('/api/otp/verify-payment-otp', {
    method: 'POST',
    body: JSON.stringify({ phone, otp })
  });
  
  if (response.ok) {
    lockPhoneNumber();
    enablePaymentButton();
    storeSessionToken(response.session_token);
  }
};

// 3. Proceed to Payment
proceedPaymentBtn.onclick = async () => {
  const paymentData = {
    phone: phoneInput.value,
    amount: voteBundle.amount,
    session_token: getSessionToken(),
    // ... other data
  };
  
  initiateHubtelPayment(paymentData);
};
```

---

### 8. API Endpoints

#### New Endpoints Required

**A. OTP Endpoints**
```
POST /api/otp/send-payment-otp
  Request: { phone: "0545644749" }
  Response: { success: true, expires_at: "2025-10-21 20:05:00" }

POST /api/otp/verify-payment-otp
  Request: { phone: "0545644749", otp: "123456" }
  Response: { 
    success: true, 
    session_token: "abc123...",
    expires_at: "2025-10-21 20:10:00"
  }
```

**B. Payment Endpoints**
```
POST /api/payment/initiate/hubtel
  Request: {
    phone: "233545644749",
    amount: 5.00,
    session_token: "abc123...",
    contestant_id: 123,
    event_id: 45,
    bundle_id: 2
  }
  Response: {
    success: true,
    transaction_id: "xyz789",
    client_reference: "VOTE_xyz789",
    message: "Payment initiated. Approve on your phone."
  }

GET /api/payment/status/{clientReference}
  Response: {
    status: "paid|unpaid|failed",
    transaction_id: "hubtel_txn_id",
    amount: 5.00,
    votes_cast: 10
  }
```

**C. Webhook Endpoint**
```
POST /api/payment/webhook/hubtel
  Headers: { X-Forwarded-For: "HUBTEL_IP" }
  Request: {
    ResponseCode: "0000",
    Message: "success",
    Data: { ... }
  }
  Response: { status: "success" }
```

---

### 9. Security Considerations

#### A. IP Whitelisting
```php
// In HubtelGateway.php
private function validateIpWhitelist()
{
    $clientIp = $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'];
    $whitelist = $this->config['ip_whitelist'] ?? [];
    
    if (!in_array($clientIp, $whitelist)) {
        throw new SecurityException('IP not whitelisted');
    }
}
```

#### B. OTP Security
- Hashed storage (password_hash)
- Single-use enforcement
- Rate limiting per phone number
- Expiry enforcement (5 minutes)
- Session token validation

#### C. Payment Security
- Client reference uniqueness validation
- Amount tampering detection
- Duplicate transaction prevention
- Webhook signature verification (if Hubtel provides)
- HTTPS enforcement for callbacks

#### D. Data Protection
- PCI DSS compliance considerations
- No storage of sensitive payment data
- Encrypted transmission (HTTPS)
- Audit logging for all transactions

---

### 10. Error Handling & User Feedback

#### Error Scenarios & Messages

**OTP Errors:**
```
- Rate limit exceeded: "Too many OTP requests. Please try again in 1 hour."
- Invalid OTP: "Invalid OTP code. Please check and try again."
- Expired OTP: "OTP has expired. Please request a new one."
- SMS delivery failed: "Unable to send OTP. Please check your number."
```

**Payment Errors:**
```
- Insufficient funds: "Insufficient balance. Please top up and try again."
- Wrong PIN: "Incorrect PIN entered. Please try again."
- Network timeout: "Network timeout. Please check your connection."
- Gateway error: "Payment service unavailable. Please try again later."
- Configuration error: "Payment system configuration issue. Contact support."
```

#### Retry Logic
```php
// Automatic retry for transient errors
$maxRetries = 3;
$retryDelay = 2; // seconds

for ($i = 0; $i < $maxRetries; $i++) {
    try {
        $result = $this->callHubtelApi($endpoint, $data);
        return $result;
    } catch (TransientException $e) {
        if ($i === $maxRetries - 1) throw $e;
        sleep($retryDelay * ($i + 1));
    }
}
```

---

### 11. Testing Strategy

#### A. Unit Tests
```
- OTP generation and verification
- Phone number formatting
- Channel detection
- Response code mapping
- Amount calculation
```

#### B. Integration Tests
```
- Complete OTP flow
- Payment initiation
- Callback processing
- Status check
- Vote casting after payment
```

#### C. End-to-End Tests
```
- Unregistered user voting journey
- Registered user voting journey
- Payment failure scenarios
- Network timeout handling
- Concurrent payment attempts
```

#### D. Security Tests
```
- IP whitelisting enforcement
- OTP brute force prevention
- Rate limiting validation
- Session token validation
- Duplicate transaction prevention
```

#### E. Test Environment Setup
```
- Use Hubtel test credentials
- Mock SMS sending in development
- Test with all three networks (MTN, Telecel, AirtelTigo)
- Test with various amount ranges
- Test callback delays and timeouts
```

---

### 12. Deployment Checklist

#### Pre-Deployment
- [ ] Obtain Hubtel production credentials (Client ID, Secret, POS Sales ID)
- [ ] Submit server IP addresses to Hubtel for whitelisting
- [ ] Configure callback URL in Hubtel dashboard
- [ ] Set up SMS gateway for OTP delivery
- [ ] Update database schema (migrations)
- [ ] Configure environment variables
- [ ] Test in staging environment

#### Deployment Steps
1. Deploy database migrations
2. Update payment gateway configuration
3. Deploy HubtelGateway service
4. Deploy OTP verification endpoints
5. Update frontend voting forms
6. Configure webhook endpoints
7. Enable Hubtel gateway in production
8. Monitor initial transactions

#### Post-Deployment
- [ ] Monitor error logs for first 24 hours
- [ ] Verify callback delivery
- [ ] Test with real transactions (small amounts)
- [ ] Validate vote processing
- [ ] Check transaction reconciliation
- [ ] Monitor OTP delivery success rate
- [ ] Set up alerting for payment failures

---

### 13. Monitoring & Analytics

#### Key Metrics to Track
```
- OTP delivery success rate
- OTP verification success rate
- Payment initiation success rate
- Payment completion rate (by gateway)
- Average payment processing time
- Callback delivery latency
- Gateway failover frequency
- Error rate by error code
- Revenue by gateway
```

#### Logging Strategy
```php
// Transaction lifecycle logging
Log::info('Payment initiated', [
    'gateway' => 'hubtel',
    'client_reference' => $reference,
    'amount' => $amount,
    'phone' => $phone
]);

Log::info('Callback received', [
    'gateway' => 'hubtel',
    'response_code' => $responseCode,
    'transaction_id' => $transactionId
]);

Log::info('Vote processed', [
    'transaction_id' => $transactionId,
    'votes_cast' => $votesCast
]);
```

#### Alerting Rules
```
- Payment failure rate > 10% in 1 hour
- No callbacks received in 30 minutes
- OTP delivery failure rate > 20%
- Gateway response time > 30 seconds
- Duplicate transaction attempts detected
```

---

### 14. Cost & Performance Considerations

#### Transaction Costs
```
Hubtel Charges:
- Mobile Money: ~2-3% + GHS 0.01 per transaction
- Minimum charge: GHS 0.02
- Maximum charge: Varies by network

Comparison with Paystack:
- Paystack: 1.95% + GHS 0.00 (local cards)
- Paystack: 3.9% (international cards)
```

#### Performance Optimization
```
- Cache gateway configurations
- Implement connection pooling for API calls
- Use async processing for callbacks
- Implement database indexing for quick lookups
- Use Redis for session token storage
- Implement CDN for static assets
```

#### Scalability Considerations
```
- Horizontal scaling of webhook processors
- Queue-based vote processing
- Database read replicas for reporting
- Rate limiting per tenant
- Load balancing for high traffic events
```

---

### 15. Compliance & Regulatory

#### Data Protection (GDPR/Ghana DPA)
- Obtain consent for phone number usage
- Provide privacy policy for payment data
- Implement data retention policies
- Allow users to request data deletion
- Encrypt sensitive data at rest

#### Financial Regulations
- Maintain transaction audit trail
- Implement fraud detection
- Support transaction disputes
- Provide transaction receipts
- Enable refund processing

#### Telecommunications Regulations
- Comply with Ghana NCA SMS regulations
- Implement opt-out for marketing messages
- Respect DND (Do Not Disturb) lists
- Rate limit SMS to prevent spam

---

### 16. Maintenance & Support

#### Regular Maintenance Tasks
```
Weekly:
- Review error logs
- Check callback delivery rates
- Validate transaction reconciliation

Monthly:
- Update gateway configurations
- Review and optimize database queries
- Analyze payment success rates
- Update IP whitelist if needed

Quarterly:
- Security audit
- Performance optimization
- Cost analysis
- User feedback review
```

#### Support Procedures
```
Payment Issues:
1. Check transaction status in database
2. Verify callback received
3. Check Hubtel status API
4. Contact Hubtel support if needed
5. Manual vote processing if confirmed paid

OTP Issues:
1. Check SMS delivery logs
2. Verify phone number format
3. Check rate limiting status
4. Resend OTP manually if needed
5. Provide alternative verification method
```

---

## Implementation Timeline

### Phase 1: Foundation (Week 1-2)
- [ ] Create HubtelGateway service
- [ ] Implement OTP verification flow
- [ ] Update database schema
- [ ] Configure test environment

### Phase 2: Integration (Week 3-4)
- [ ] Integrate with PaymentService
- [ ] Implement webhook handling
- [ ] Build frontend OTP UI
- [ ] Create API endpoints

### Phase 3: Testing (Week 5-6)
- [ ] Unit testing
- [ ] Integration testing
- [ ] Security testing
- [ ] User acceptance testing

### Phase 4: Deployment (Week 7-8)
- [ ] Staging deployment
- [ ] Production deployment
- [ ] Monitoring setup
- [ ] Documentation finalization

---

## Recommended Next Steps

### Immediate Actions
1. **Obtain Hubtel Credentials**
   - Contact Hubtel sales team
   - Request test and production credentials
   - Get POS Sales ID

2. **IP Whitelisting**
   - Identify server public IP addresses
   - Submit to Hubtel for whitelisting
   - Verify whitelisting is active

3. **SMS Gateway Setup**
   - Choose SMS provider (Hubtel SMS, Twilio, etc.)
   - Configure API credentials
   - Test OTP delivery

4. **Development Environment**
   - Set up test database
   - Configure test credentials
   - Create test phone numbers

### Technical Decisions Required
1. **Gateway Priority**
   - Should Hubtel be primary or secondary?
   - Implement automatic failover?
   - User preference selection?

2. **OTP Delivery**
   - Use Hubtel SMS or third-party?
   - Fallback to voice OTP?
   - Support WhatsApp OTP?

3. **Session Management**
   - Redis vs database for session tokens?
   - Session timeout duration?
   - Multi-device support?

4. **User Registration**
   - Auto-register after successful payment?
   - Require email for registration?
   - Link payments to user accounts?

---

## Conclusion

The integration of Hubtel Direct Receive Money with OTP verification provides:

### Benefits
✅ **Enhanced Security** - OTP verification prevents unauthorized payments  
✅ **Better User Experience** - Familiar mobile money flow for Ghanaian users  
✅ **Gateway Redundancy** - Failover capability with Paystack  
✅ **Compliance** - Meets Hubtel's security requirements  
✅ **Scalability** - Supports high-volume voting events  
✅ **Cost Optimization** - Competitive transaction fees  

### Risks & Mitigations
⚠️ **SMS Delivery Failures** → Implement retry logic and alternative delivery  
⚠️ **IP Whitelisting Issues** → Maintain updated IP list, monitor changes  
⚠️ **Callback Delays** → Implement status check API fallback  
⚠️ **User Confusion** → Clear UI/UX with helpful error messages  
⚠️ **Integration Complexity** → Comprehensive testing and monitoring  

### Success Criteria
- 95%+ OTP delivery success rate
- 90%+ payment completion rate
- <30 seconds average payment processing time
- <1% callback delivery failure rate
- Zero security incidents

---

## Appendix

### A. Hubtel API Response Codes Reference
```
0000 - Transaction successful
0001 - Transaction pending (awaiting approval)
2001 - Transaction failed
4000 - Validation error
4070 - Insufficient funds or fees not configured
4101 - Business not set up or scope issue
4103 - Permission denied or account restrictions
```

### B. Ghana Mobile Network Prefixes
```
MTN:        024, 025, 053, 054, 055, 059
Telecel:    020, 050
AirtelTigo: 026, 027, 056, 057
```

### C. Sample Hubtel Request/Response

**Request:**
```json
{
  "CustomerName": "John Doe",
  "CustomerMsisdn": "233545644749",
  "CustomerEmail": "voter@smartcast.com",
  "Channel": "mtn-gh",
  "Amount": 5.00,
  "PrimaryCallbackUrl": "https://smartcast.com/api/payment/webhook/hubtel",
  "Description": "Vote for Contestant #123",
  "ClientReference": "VOTE_xyz789"
}
```

**Response:**
```json
{
  "Message": "Transaction pending. Expect callback request for final state",
  "ResponseCode": "0001",
  "Data": {
    "TransactionId": "hubtel_txn_12345",
    "Description": "Vote for Contestant #123",
    "ClientReference": "VOTE_xyz789",
    "Amount": 5.00,
    "Charges": 0.15,
    "AmountAfterCharges": 5.00,
    "AmountCharged": 5.15,
    "DeliveryFee": 0.0
  }
}
```

### D. Contact Information
```
Hubtel Support: support@hubtel.com
Hubtel Sales: sales@hubtel.com
Technical Issues: techsupport@hubtel.com
Emergency: +233 XXX XXX XXX
```

---

**Document Version:** 1.0  
**Last Updated:** October 21, 2025  
**Prepared By:** Cascade AI  
**Status:** Recommendation - Awaiting Approval
