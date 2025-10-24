# Hubtel Card Payment Implementation Guide

## Overview
This guide explains how to add Hubtel card payments alongside the existing mobile money payments in SmartCast.

## What's Been Implemented

### 1. Backend - HubtelGateway.php ✅
Added `initializeCardPayment()` method that:
- Uses Hubtel Checkout API (`https://payproxyapi.hubtel.com/items/initiate`)
- Returns a `checkoutUrl` for card payment
- Supports Visa, Mastercard, and other cards
- Handles callbacks and verification

## What Needs to Be Done

### 2. Update PaymentService.php
Add method to route card payments to Hubtel:

```php
public function initializeCardPayment($paymentData)
{
    // Get Hubtel gateway
    $gateway = $this->getGatewayByName('hubtel');
    $gatewayService = $this->getGatewayService($gateway);
    
    // Initialize card payment
    $result = $gatewayService->initializeCardPayment($paymentData);
    
    if ($result['success']) {
        // Create payment transaction record
        $paymentTransactionId = $this->createPaymentTransaction([
            'gateway_id' => $gateway['id'],
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'] ?? 'GHS',
            'reference' => $paymentData['reference'],
            'gateway_reference' => $result['gateway_reference'],
            'status' => 'pending',
            'payment_method' => 'card',
            'metadata' => $paymentData['metadata'] ?? [],
            'tenant_id' => $paymentData['tenant_id'] ?? null,
            'related_type' => 'vote',
            'related_id' => $paymentData['voting_transaction_id'] ?? null
        ]);
        
        return [
            'success' => true,
            'payment_transaction_id' => $paymentTransactionId,
            'reference' => $paymentData['reference'],
            'gateway_reference' => $result['gateway_reference'],
            'payment_url' => $result['payment_url'],
            'checkout_id' => $result['checkout_id'] ?? null,
            'provider' => 'card',
            'charge_status' => 'pending',
            'requires_approval' => true,
            'message' => $result['message']
        ];
    }
    
    return $result;
}
```

### 3. Update VoteController.php
Add payment method selection in `processVote()`:

```php
// Check payment method (mobile_money or card)
$paymentMethod = $_POST['payment_method'] ?? 'mobile_money';

if ($paymentMethod === 'card') {
    // Initialize card payment
    $paymentResult = $this->paymentService->initializeCardPayment([
        'amount' => $bundle['price'],
        'reference' => $reference,
        'description' => "Vote for {$contestant['name']}",
        'callback_url' => $callbackUrl,
        'return_url' => $returnUrl,
        'voting_transaction_id' => $transactionId,
        'tenant_id' => $event['tenant_id'],
        'metadata' => [
            'transaction_id' => $transactionId,
            'event_id' => $eventId,
            'contestant_id' => $contestantId
        ]
    ]);
} else {
    // Existing mobile money flow
    $paymentResult = $this->paymentService->initializeMobileMoneyPayment([...]);
}
```

### 4. Update Frontend - vote-form.php
Add payment method selection UI:

```html
<!-- Payment Method Selection -->
<div class="form-group">
    <label>Payment Method</label>
    <div class="payment-methods">
        <label class="payment-method-option">
            <input type="radio" name="payment_method" value="mobile_money" checked>
            <div class="method-card">
                <i class="fas fa-mobile-alt"></i>
                <span>Mobile Money</span>
                <small>MTN, Telecel, AirtelTigo</small>
            </div>
        </label>
        
        <label class="payment-method-option">
            <input type="radio" name="payment_method" value="card">
            <div class="method-card">
                <i class="fas fa-credit-card"></i>
                <span>Card Payment</span>
                <small>Visa, Mastercard</small>
            </div>
        </label>
    </div>
</div>

<!-- Phone number field (only for mobile money) -->
<div class="form-group" id="phone-field">
    <label for="msisdn">Mobile Money Number</label>
    <input type="tel" id="msisdn" name="msisdn" required>
</div>
```

Add JavaScript to toggle phone field:

```javascript
document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const phoneField = document.getElementById('phone-field');
        if (this.value === 'mobile_money') {
            phoneField.style.display = 'block';
            document.getElementById('msisdn').required = true;
        } else {
            phoneField.style.display = 'none';
            document.getElementById('msisdn').required = false;
        }
    });
});
```

### 5. Handle Card Payment Response
Update payment initiation handler:

```javascript
if (data.payment_url) {
    // Redirect to Hubtel checkout page for card payment
    window.location.href = data.payment_url;
} else {
    // Mobile money - show status checking
    showPaymentStatus(data);
    checkPaymentStatus(data.transaction_id, data.status_check_url);
}
```

### 6. Card Payment Callback
Card payments will redirect back to your callback URL after completion.
The existing webhook handler in VoteController should work for both:

```php
// In handlePaymentCallback()
if (isset($callbackData['ResponseCode'])) {
    // Hubtel callback (works for both mobile money and card)
    if ($callbackData['ResponseCode'] === '0000') {
        // Payment successful
        $this->processSuccessfulPayment($transaction, $paymentDetails);
    }
}
```

## Payment Flow Comparison

### Mobile Money Flow:
```
1. User selects Mobile Money
2. Enters phone number
3. Payment initiated
4. User approves on phone (*170#)
5. Webhook processes vote
6. Success shown
```

### Card Payment Flow:
```
1. User selects Card Payment
2. Clicks Pay button
3. Redirected to Hubtel checkout page
4. Enters card details on Hubtel's secure page
5. Card processed
6. Redirected back to SmartCast
7. Webhook processes vote
8. Success shown
```

## Configuration Required

1. **Hubtel Checkout API Access**
   - Ensure your Hubtel account has Checkout API enabled
   - Same credentials (client_id, client_secret) work for both APIs

2. **Callback URLs**
   - Register your callback URL in Hubtel dashboard
   - Must be HTTPS in production

3. **Testing**
   - Use Hubtel test cards for testing
   - Test Card: 4111 1111 1111 1111
   - CVV: Any 3 digits
   - Expiry: Any future date

## Database Schema
No changes needed! The existing `payment_transactions` table supports both:
- `payment_method` field stores 'mobile_money' or 'card'
- `gateway_reference` stores the transaction reference
- `metadata` stores additional details

## Security Considerations

1. ✅ Card details never touch your server (handled by Hubtel)
2. ✅ PCI DSS compliance handled by Hubtel
3. ✅ Webhook signature verification (already implemented)
4. ✅ HTTPS required for production

## Next Steps

1. Implement PaymentService card method
2. Update VoteController to handle payment method selection
3. Add frontend payment method selector
4. Test with Hubtel test cards
5. Deploy and test in production

## Estimated Implementation Time
- Backend: 1-2 hours
- Frontend: 1 hour
- Testing: 1 hour
- **Total: 3-4 hours**
