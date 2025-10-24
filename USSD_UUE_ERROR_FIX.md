# USSD UUE Error Fix Guide

## ❌ Error Message
```
The Response from the provider of this service is invalid. Error: UUE
```

---

## 🔍 What is UUE Error?

**UUE = Unregistered USSD Error**

This means:
- The USSD code you're using is NOT registered with the telecom provider
- The provider (Hubtel/MTN) doesn't recognize your code
- You're trying to use a code that doesn't belong to you

---

## 🎯 Common Causes

### **1. Wrong Base Code in Config**
```php
// Your config says:
'base_code' => '711',

// But Hubtel has registered:
'920'

// Result: UUE Error ❌
```

### **2. Code Not Registered Yet**
- You requested `*711*` from Hubtel
- Still waiting for approval
- Code not active yet

### **3. Code Expired/Suspended**
- Your USSD subscription expired
- Account suspended
- Code revoked

### **4. Wrong Environment**
- Using production code in test environment
- Using test code in production

---

## ✅ Solution Steps

### **Step 1: Check Your Registered Code**

**For Hubtel:**
1. Login to https://dashboard.hubtel.com
2. Navigate to: **USSD → Applications**
3. Find your application
4. Note the **Service Code** (e.g., `*920*01#`)
5. Extract base code: `920`

**For MTN Direct:**
1. Check your MTN Business portal
2. Or contact MTN support: 0244300000
3. Ask: "What USSD code is registered for my account?"

---

### **Step 2: Update SmartCast Config**

**Option A: Edit config file**
```php
// File: config/ussd_config.php

'base_code' => '920',  // ← Change to YOUR registered code
```

**Option B: Set environment variable**
```env
# File: .env

USSD_BASE_CODE=920  # ← Change to YOUR registered code
```

---

### **Step 3: Verify Callback URL**

**Check Hubtel Dashboard:**
```
Callback URL: https://yourdomain.com/api/ussd/callback
```

**Must be:**
- ✅ HTTPS (not HTTP)
- ✅ Publicly accessible
- ✅ Returns 200 OK
- ✅ Matches your production domain

---

### **Step 4: Test Configuration**

**Test 1: Check Config**
```php
<?php
require 'vendor/autoload.php';
use SmartCast\Helpers\UssdHelper;

echo "Base Code: " . UssdHelper::getBaseCode() . "\n";
echo "Full Code: " . UssdHelper::formatUssdCode('01') . "\n";
```

**Expected Output:**
```
Base Code: 920
Full Code: *920*01#
```

**Test 2: Dial from Phone**
```
Dial: *920*01#
Expected: USSD menu appears
```

---

## 🔧 Quick Fixes

### **Fix 1: Reset to Default (920)**

```php
// config/ussd_config.php
'base_code' => '920',
```

### **Fix 2: Clear Cache**

```bash
# Clear PHP cache
php artisan cache:clear

# Or restart Apache
sudo service apache2 restart
```

### **Fix 3: Check .env Override**

```env
# Check if .env has different value
USSD_BASE_CODE=711  # ← Remove or change this
```

---

## 📋 Verification Checklist

- [ ] Logged into Hubtel dashboard
- [ ] Confirmed registered USSD code
- [ ] Updated `config/ussd_config.php`
- [ ] Checked `.env` file (if exists)
- [ ] Restarted web server
- [ ] Tested with real phone
- [ ] Verified callback URL is HTTPS
- [ ] Checked Hubtel application is active

---

## 🆘 Still Getting UUE Error?

### **Check 1: Is Code Active?**

**In Hubtel Dashboard:**
- Status should be: **Active** ✅
- Not: Pending, Suspended, Expired ❌

### **Check 2: Is Application Enabled?**

**In Hubtel Dashboard:**
- Application toggle: **ON** ✅
- Not: OFF ❌

### **Check 3: Test with Hubtel's Test Code**

```
Dial: *713*01#  (Hubtel test code)
```

If this works, your config is wrong.
If this fails, Hubtel account has issues.

---

## 📞 Contact Support

### **Hubtel Support**
```
Email: support@hubtel.com
Phone: +233 30 281 0800
WhatsApp: +233 59 828 0800
Dashboard: https://dashboard.hubtel.com
```

**What to tell them:**
```
"I'm getting UUE error when dialing my USSD code.
My account: [your email]
Code trying to use: *711*01#
Error: UUE - Unregistered USSD Error
Please confirm my registered USSD code."
```

---

## 🎓 Understanding USSD Registration

### **How USSD Codes Work:**

```
1. You request code from Hubtel
   ↓
2. Hubtel submits to Telecom (MTN/Vodafone)
   ↓
3. Telecom approves (1-3 days)
   ↓
4. Code becomes active
   ↓
5. You can use it
```

### **Available vs Registered:**

```
Available: Code exists but not yours
Registered: Code is assigned to YOUR account
Active: Code is working and live
```

---

## 🔄 Changing Your USSD Code

### **If you want to use *711* instead of *920*:**

**Step 1: Request from Hubtel**
1. Login to dashboard
2. Create new USSD application
3. Request code: `*711*`
4. Wait for approval (1-3 days)

**Step 2: Once Approved**
```php
// Update config
'base_code' => '711',
```

**Step 3: Update Callback**
- Set callback URL in Hubtel
- Test with phone

---

## 📊 Error Code Reference

| Error | Meaning | Solution |
|-------|---------|----------|
| **UUE** | Unregistered USSD Error | Register code with provider |
| **IVR** | Invalid Response | Check callback URL |
| **TMO** | Timeout | Optimize response time |
| **ERR** | General Error | Check logs |
| **NTF** | Not Found | Check routing |

---

## ✅ Success Indicators

**When it's working:**
```
✅ Dial *920*01# → Menu appears
✅ No UUE error
✅ Fast response (< 2 seconds)
✅ Hubtel dashboard shows requests
✅ Your logs show incoming requests
```

**When it's broken:**
```
❌ Dial *920*01# → UUE error
❌ Or: "Service not available"
❌ Or: No response
❌ Hubtel dashboard: No requests
❌ Your logs: No incoming requests
```

---

## 🎯 Quick Diagnosis

**Run this test:**

```bash
# 1. Check config
cat config/ussd_config.php | grep base_code

# 2. Check .env
cat .env | grep USSD_BASE_CODE

# 3. Test helper
php -r "require 'vendor/autoload.php'; echo SmartCast\Helpers\UssdHelper::getBaseCode();"
```

**Expected:**
```
'base_code' => '920',
USSD_BASE_CODE=920
920
```

**If different values:**
- Config and .env don't match
- Fix: Make them match your registered code

---

## 📝 Summary

**The UUE error means:**
1. Your code (`711`) is NOT registered
2. Hubtel doesn't recognize it
3. You need to use your actual registered code

**To fix:**
1. Check Hubtel dashboard for YOUR code
2. Update config to match
3. Restart server
4. Test again

---

**Most likely your registered code is `920`, not `711`!** 

Change config back to `920` and test again. ✅
