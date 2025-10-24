# USSD Management Troubleshooting Guide

## Issues Fixed

### ✅ Issue 1: Organizer Settings Not Saving

**Problem:** Form submits but data doesn't save to database

**Root Cause:** Session method `getTenantId()` might not exist

**Solution Implemented:**
```php
// Changed from:
$tenantId = $this->session->getTenantId();

// To:
$tenantId = $this->session->get('tenant_id');
```

**Additional Fixes:**
- Added authentication check
- Added tenant existence validation
- Added error logging
- Improved error messages

**Test:**
1. Login as organizer
2. Go to Settings → USSD Settings
3. Update welcome message
4. Click "Save Changes"
5. Check database: `SELECT ussd_welcome_message FROM tenants WHERE id = X`

---

### ✅ Issue 2: Available USSD Codes Location

**Where Available Codes Are Shown:**

**1. Statistics Card (Top of Page):**
```
┌─────────────────────────┐
│ 64                      │
│ Available Codes         │
│ Unassigned (01-99)      │
└─────────────────────────┘
```

**2. Assign Code Modal (Dropdown):**
```
USSD Code: *920* [Select code...] #
                  ├── 01
                  ├── 02
                  ├── 03
                  └── ... (all unassigned codes)
```

**How It Works:**
```php
// Controller generates available codes
$availableCodes = $this->getAvailableUssdCodes();

// Returns array: ['01', '02', '03', ..., '99']
// Excludes already assigned codes
```

**To See Available Codes:**
1. Go to `/superadmin/ussd`
2. Look at "Available Codes" card (shows count)
3. Click "Assign Code" button
4. Open dropdown - shows all available codes

---

### ✅ Issue 3: Buttons Not Working

**Buttons Fixed:**
1. ✅ Assign Code
2. ✅ Edit Code
3. ✅ Enable/Disable
4. ✅ Revoke Code

**Root Cause:** JSON response headers not set properly

**Solution Implemented:**
```php
private function jsonResponse($data, $statusCode = 200)
{
    // Clear any previous output
    if (ob_get_length()) ob_clean();
    
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    header('Cache-Control: no-cache, must-revalidate');
    
    echo json_encode($data);
    exit;
}
```

**Test Each Button:**

**1. Assign Code:**
```
1. Click "Assign Code" for a tenant
2. Select code from dropdown
3. Add welcome message (optional)
4. Click "Assign Code"
5. Should show success message and reload
```

**2. Edit Code:**
```
1. Click "Edit" for a tenant with code
2. Change code or message
3. Click "Assign Code"
4. Should update and reload
```

**3. Enable/Disable:**
```
1. Click "Enable" or "Disable"
2. Confirm action
3. Should toggle status and reload
```

**4. Revoke Code:**
```
1. Click "Revoke"
2. Confirm action
3. Should remove code and reload
```

---

## Debugging Steps

### Check Organizer Settings Save

**1. Check PHP Error Log:**
```bash
tail -f /path/to/php/error.log | grep "USSD Settings Update"
```

**2. Check Database:**
```sql
SELECT id, name, ussd_code, ussd_enabled, ussd_welcome_message 
FROM tenants 
WHERE id = YOUR_TENANT_ID;
```

**3. Check Session:**
```php
// Add to updateOrganizerSettings() temporarily
error_log('Tenant ID: ' . $tenantId);
error_log('Welcome Message: ' . $welcomeMessage);
error_log('Update Result: ' . ($updated ? 'success' : 'failed'));
```

---

### Check Super Admin Buttons

**1. Open Browser Console:**
```
F12 → Console Tab
```

**2. Click a Button and Check:**
- Network tab for API call
- Response should be JSON
- Status should be 200

**3. Check Response Format:**
```json
{
    "success": true,
    "message": "USSD code *920*01# assigned successfully"
}
```

**4. If Error:**
```json
{
    "success": false,
    "message": "Error description here"
}
```

---

## Common Issues

### Issue: "Session error. Please login again"

**Cause:** Session doesn't have tenant_id

**Fix:**
```sql
-- Check session data
SELECT * FROM sessions WHERE user_id = YOUR_USER_ID;
```

**Solution:** Re-login to refresh session

---

### Issue: "Tenant not found"

**Cause:** Tenant ID doesn't exist in database

**Fix:**
```sql
-- Verify tenant exists
SELECT * FROM tenants WHERE id = YOUR_TENANT_ID;
```

---

### Issue: Buttons show "Error: undefined"

**Cause:** Response is not JSON

**Debug:**
1. Open Network tab in browser
2. Click button
3. Check response content-type
4. Should be `application/json`

**Fix:** Clear PHP output buffer before response

---

### Issue: "USSD code must be 2 digits"

**Cause:** Invalid code format

**Valid Codes:**
- ✅ 01, 02, 03, ..., 99
- ❌ 1, 2, 100, ABC

---

### Issue: "Code already assigned"

**Cause:** Another tenant has this code

**Solution:**
1. Check who has the code:
```sql
SELECT id, name, ussd_code FROM tenants WHERE ussd_code = 'XX';
```
2. Revoke from other tenant first
3. Or choose different code

---

## Verification Checklist

### Organizer Settings:
- [ ] Can access `/organizer/settings/ussd`
- [ ] Can see assigned USSD code
- [ ] Can update welcome message
- [ ] Changes save to database
- [ ] Success message appears
- [ ] Statistics show correct data

### Super Admin Dashboard:
- [ ] Can access `/superadmin/ussd`
- [ ] Statistics cards show correct counts
- [ ] Tenants table loads
- [ ] Available codes dropdown populates
- [ ] Assign button works
- [ ] Edit button works
- [ ] Enable/Disable button works
- [ ] Revoke button works
- [ ] Changes reflect in database

---

## Database Queries for Testing

### Check All USSD Codes:
```sql
SELECT 
    id,
    name,
    ussd_code,
    ussd_enabled,
    ussd_welcome_message
FROM tenants
ORDER BY ussd_code;
```

### Check Available Codes:
```sql
SELECT ussd_code 
FROM tenants 
WHERE ussd_code IS NOT NULL
ORDER BY ussd_code;
```

### Manually Assign Code:
```sql
UPDATE tenants 
SET 
    ussd_code = '01',
    ussd_enabled = 1,
    ussd_welcome_message = 'Welcome to Test!'
WHERE id = 1;
```

### Manually Revoke Code:
```sql
UPDATE tenants 
SET 
    ussd_code = NULL,
    ussd_enabled = 0
WHERE id = 1;
```

---

## Success Indicators

### Organizer:
✅ Form submits without errors
✅ Success message: "USSD settings updated successfully"
✅ Database updated
✅ Page reloads with new data

### Super Admin:
✅ Modal opens correctly
✅ Dropdown shows available codes
✅ Button click shows success alert
✅ Page reloads
✅ Table shows updated data
✅ Database reflects changes

---

**All issues should now be resolved!** 🚀
