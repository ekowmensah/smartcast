# Hubtel Integration - Quick Start Guide
## SmartCast Voting Platform

---

## 🚀 5-Minute Setup

### 1. Run Database Migration
```bash
mysql -u root -p smartcast < migrations/hubtel_integration.sql
```

### 2. Configure Credentials
```sql
UPDATE payment_gateways 
SET config = JSON_SET(
    config,
    '$.client_id', 'YOUR_CLIENT_ID',
    '$.client_secret', 'YOUR_CLIENT_SECRET',
    '$.merchant_account', 'YOUR_POS_SALES_ID',
    '$.ip_whitelist', JSON_ARRAY('YOUR_SERVER_IP')
)
WHERE provider = 'hubtel';
```

### 3. Add OTP UI to Voting Form
```php
<!-- In your vote-form.php -->
<?php include __DIR__ . '/partials/otp-verification.php'; ?>

<button type="button" id="send-otp-btn" class="btn btn-primary">
    <i class="fas fa-shield-alt"></i> Verify Phone Number
</button>

<script src="<?= APP_URL ?>/public/assets/js/otp-payment-handler.js"></script>
<script>
const otpHandler = new OtpPaymentHandler({
    phoneInput: document.querySelector('input[name="msisdn"]'),
    sendOtpBtn: document.getElementById('send-otp-btn'),
    paymentBtn: document.querySelector('.vote-button')
});
</script>
```

### 4. Enable Gateway
```sql
UPDATE payment_gateways SET is_active = 1 WHERE provider = 'hubtel';
```

---

## 📋 Files Created

### Backend
- ✅ `src/Services/Gateways/HubtelGateway.php` - Gateway service
- ✅ `src/Controllers/OtpController.php` - OTP handling
- ✅ `migrations/hubtel_integration.sql` - Database schema

### Frontend
- ✅ `views/voting/partials/otp-verification.php` - OTP UI
- ✅ `public/assets/js/otp-payment-handler.js` - OTP logic

### Documentation
- ✅ `HUBTEL_INTEGRATION_RECOMMENDATION.md` - Full analysis
- ✅ `HUBTEL_IMPLEMENTATION_GUIDE.md` - Deployment guide
- ✅ `HUBTEL_QUICK_START.md` - This file

---

## 🔑 API Endpoints

```
POST /api/otp/send-payment-otp       - Send OTP
POST /api/otp/verify-payment-otp     - Verify OTP
POST /api/payment/webhook/hubtel     - Webhook
```

---

## 🧪 Quick Test

### Test OTP Flow
```javascript
// Send OTP
fetch('/api/otp/send-payment-otp', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({phone: '0245123456'})
});

// Verify OTP
fetch('/api/otp/verify-payment-otp', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify({phone: '0245123456', otp: '123456'})
});
```

### Test Webhook
```bash
curl -X POST http://localhost/smartcast/api/payment/webhook.php?provider=hubtel \
  -H "Content-Type: application/json" \
  -d '{"ResponseCode":"0000","Data":{"ClientReference":"TEST123"}}'
```

---

## 📊 Check Status

### View Recent OTPs
```sql
SELECT * FROM otp_requests ORDER BY created_at DESC LIMIT 10;
```

### View Hubtel Transactions
```sql
SELECT * FROM payment_transactions 
WHERE gateway_provider = 'hubtel' 
ORDER BY created_at DESC LIMIT 10;
```

### Check Logs
```bash
tail -f /var/log/apache2/error.log | grep -i hubtel
```

---

## 🔧 Configuration Checklist

- [ ] Database migrated
- [ ] Hubtel credentials set
- [ ] IP whitelisted with Hubtel
- [ ] Webhook URL registered
- [ ] SMS gateway configured
- [ ] OTP UI added to forms
- [ ] Gateway enabled
- [ ] Tested OTP flow
- [ ] Tested payment flow

---

## 🆘 Common Issues

**OTP not received?**
- Check SMS gateway config in `OtpController.php`
- Enable debug mode to see OTP in logs

**Payment fails?**
- Verify credentials are correct
- Check IP is whitelisted
- Ensure webhook URL is accessible

**Webhook not working?**
- Test with curl command above
- Check server logs for errors
- Verify Hubtel can reach your server

---

## 📞 Need Help?

1. Check `HUBTEL_IMPLEMENTATION_GUIDE.md` for detailed instructions
2. Review error logs: `tail -f error.log`
3. Test with Hubtel sandbox first
4. Contact Hubtel support: support@hubtel.com

---

## 🎯 Next Steps

1. Configure SMS gateway for production
2. Test with real phone numbers
3. Monitor first transactions
4. Set up alerts for failures
5. Train team on new flow

---

**Status:** ✅ Ready for Configuration  
**Time to Deploy:** ~2 hours  
**Difficulty:** Medium

*Last Updated: October 21, 2025*
