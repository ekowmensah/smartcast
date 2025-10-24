# USSD URLs Verification Report

## ✅ URL Configuration Status

### **Routes Configured:**
- ✅ POST `/api/ussd/callback` → `UssdController@handleRequest`
- ✅ GET `/api/ussd/callback` → `UssdController@handleRequest`

**Location:** `src/Core/Application.php` (Lines 82-83)

### **Controller Status:**
- ✅ `UssdController.php` exists at `src/Controllers/UssdController.php`
- ✅ Properly namespaced: `SmartCast\Controllers\UssdController`
- ✅ Extends `BaseController`
- ✅ Has `handleRequest()` method

---

## 🔗 Your USSD URLs

### **For Hubtel Dashboard:**

**Service Interaction URL:**
```
https://yourdomain.com/api/ussd/callback
```

**Fulfilment URL:**
```
https://yourdomain.com/api/ussd/callback
```

**Replace `yourdomain.com` with your actual domain.**

---

## 🧪 Testing the URLs

### **Method 1: Use the Test Page**

1. Open in browser:
   ```
   http://localhost/smartcast/test_ussd_endpoint.php
   ```
   Or on your server:
   ```
   https://yourdomain.com/test_ussd_endpoint.php
   ```

2. The page will:
   - Show your USSD callback URLs
   - Let you test USSD requests
   - Verify the endpoint is working
   - Provide a deployment checklist

### **Method 2: Test with cURL**

```bash
# Test POST request
curl -X POST http://localhost/smartcast/api/ussd/callback \
  -d "sessionId=test123" \
  -d "serviceCode=*920*01#" \
  -d "phoneNumber=233545644749" \
  -d "text="

# Expected response:
# CON Welcome to [Your Brand]!
# 
# Select an event:
# 1. Event Name
# 0. Exit
```

### **Method 3: Test with Postman**

**URL:** `http://localhost/smartcast/api/ussd/callback`  
**Method:** POST  
**Body (form-data):**
- `sessionId`: test123
- `serviceCode`: *920*01#
- `phoneNumber`: 233545644749
- `text`: (empty)

---

## ✅ Pre-Deployment Checklist

Before registering with Hubtel:

- [ ] **Database Migration Run**
  ```sql
  source migrations/add_multi_tenant_ussd.sql
  ```

- [ ] **Tenant Configured**
  ```sql
  UPDATE tenants SET 
      ussd_code = '01',
      ussd_enabled = 1,
      ussd_welcome_message = 'Welcome!'
  WHERE id = 1;
  ```

- [ ] **Test Endpoint Locally**
  - Visit: `http://localhost/smartcast/test_ussd_endpoint.php`
  - Send test request
  - Verify response starts with "CON" or "END"

- [ ] **SSL Certificate Active**
  - URL must be HTTPS in production
  - Test: `https://yourdomain.com/api/ussd/callback`

- [ ] **Publicly Accessible**
  - Not localhost
  - Hubtel servers can reach it
  - No VPN required

---

## 🔍 Troubleshooting

### **Issue: 404 Not Found**

**Cause:** Route not registered or .htaccess issue

**Solution:**
1. Check `.htaccess` exists in root:
   ```apache
   RewriteEngine On
   RewriteCond %{REQUEST_FILENAME} !-f
   RewriteCond %{REQUEST_FILENAME} !-d
   RewriteRule ^(.*)$ index.php [L,QSA]
   ```

2. Verify routes in `src/Core/Application.php`

3. Clear any cache

### **Issue: 500 Internal Server Error**

**Cause:** PHP error in controller

**Solution:**
1. Check PHP error log
2. Verify all models exist:
   - `SmartCast\Models\Tenant`
   - `SmartCast\Models\UssdSession`
   - `SmartCast\Models\Event`

3. Check database connection

### **Issue: Empty Response**

**Cause:** No tenant configured or database not migrated

**Solution:**
1. Run database migration
2. Configure at least one tenant with USSD code
3. Verify tenant has active events

---

## 📊 Expected Responses

### **First Request (New Session):**
```
CON Welcome to [Tenant Name]!

Select an event:
1. Event Name 1
2. Event Name 2
0. Exit
```

### **Invalid Tenant Code:**
```
END Service not available. Please contact support.
```

### **No Active Events:**
```
END No active voting events available at this time.
```

### **USSD Disabled:**
```
END USSD voting is currently disabled for this service.
```

---

## 🚀 Deployment Steps

### **1. Local Testing (Now)**
```bash
# Open test page
http://localhost/smartcast/test_ussd_endpoint.php

# Send test request
# Verify response
```

### **2. Staging Testing (Before Production)**
```bash
# Deploy to staging server
# Test with staging URL
https://staging.yourdomain.com/api/ussd/callback

# Verify HTTPS works
# Test with real phone (if possible)
```

### **3. Production Deployment**
```bash
# Deploy to production
# Verify URL is accessible
https://yourdomain.com/api/ussd/callback

# Register with Hubtel
# Test with real USSD code
```

---

## 📝 Hubtel Registration Details

When registering in Hubtel Dashboard:

| Field | Value |
|-------|-------|
| **Application Name** | SmartCast Multi-Tenant Voting |
| **Service Interaction URL** | `https://yourdomain.com/api/ussd/callback` |
| **Fulfilment URL** | `https://yourdomain.com/api/ussd/callback` |
| **Request Method** | POST |
| **Response Format** | Plain Text |
| **USSD Codes Requested** | *920*01#, *920*02#, *920*03# |

---

## ✅ Summary

**Status:** ✅ URLs are configured and ready

**What's Working:**
- Routes registered in Application.php
- UssdController exists and is properly set up
- handleRequest() method implemented
- Both POST and GET supported

**Next Steps:**
1. Run database migration
2. Configure tenant(s) with USSD codes
3. Test locally using test page
4. Deploy to production (HTTPS)
5. Register with Hubtel

**Test Page:** `test_ussd_endpoint.php`

---

**Your USSD endpoints are ready to go!** 🚀
