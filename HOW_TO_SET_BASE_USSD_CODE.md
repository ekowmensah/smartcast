# How to Set Your Base USSD Code

## 📱 What is a Base USSD Code?

The base USSD code is the short code you register with your telecom provider (Hubtel, MTN, Vodafone, etc.).

**Examples:**
- `*920*` - Default in SmartCast
- `*713*` - Custom code
- `*384*` - Another custom code

**Full Format:** `*[BASE]*[TENANT]#`
- Example: `*920*01#` where `920` is base, `01` is tenant

---

## 🎯 Quick Setup (Recommended)

### **Method 1: Using Environment Variables** ⭐

**Step 1: Edit `.env` file**
```env
# Add this line to your .env file
USSD_BASE_CODE=920

# Optional: Set other USSD settings
USSD_PROVIDER=hubtel
USSD_ENABLED=true
USSD_CALLBACK_URL=https://yourdomain.com/api/ussd/callback
```

**Step 2: Restart your server**
```bash
# Restart Apache/Nginx
sudo service apache2 restart
# or
sudo systemctl restart nginx
```

**Done!** ✅ Your base code is now `920`

---

### **Method 2: Edit Configuration File**

**Step 1: Open config file**
```
File: config/ussd_config.php
```

**Step 2: Change base_code**
```php
return [
    // Change this to your registered USSD code
    'base_code' => '920',  // ← Change this number
    
    // Other settings...
];
```

**Step 3: Save and test**

---

## 🔧 Configuration Options

### **config/ussd_config.php**

```php
return [
    /**
     * Base USSD Code
     * This is what you register with telecom provider
     */
    'base_code' => '920',  // Change to your code
    
    /**
     * Format
     * How the code is displayed
     */
    'format' => '*{base}*{tenant}#',
    
    /**
     * Tenant Code Length
     * 2 = 01-99 (99 tenants max)
     * 3 = 001-999 (999 tenants max)
     */
    'tenant_code_length' => 2,
    
    /**
     * Maximum Tenants
     */
    'max_tenants' => 99,
];
```

---

## 📋 Common Base Codes

### **Ghana (Hubtel)**
```
*920* - Available
*713* - Available
*384* - Available
*714* - Available
```

### **Nigeria**
```
*347* - Available
*894* - Available
*737* - Available (Paystack)
```

### **Kenya**
```
*483* - Available
*384* - Available
```

---

## 🚀 Step-by-Step Setup Guide

### **Step 1: Register with Telecom Provider**

**For Hubtel (Ghana):**
1. Login to Hubtel Dashboard
2. Go to USSD → Create Application
3. Request a short code (e.g., `920`)
4. Wait for approval (1-3 business days)
5. Note your assigned code

**For MTN Direct:**
1. Contact MTN Business
2. Request USSD short code
3. Provide business details
4. Pay registration fee
5. Receive your code

---

### **Step 2: Update SmartCast Configuration**

**Option A: Environment Variable**
```env
USSD_BASE_CODE=920
```

**Option B: Config File**
```php
'base_code' => '920',
```

---

### **Step 3: Update Callback URL**

**In Hubtel Dashboard:**
```
Callback URL: https://yourdomain.com/api/ussd/callback
```

**In SmartCast (.env):**
```env
USSD_CALLBACK_URL=https://yourdomain.com/api/ussd/callback
```

---

### **Step 4: Test Your Setup**

**Test from phone:**
```
Dial: *920*01#
Expected: USSD menu appears
```

**Test from code:**
```php
use SmartCast\Helpers\UssdHelper;

// Get base code
echo UssdHelper::getBaseCode(); // "920"

// Format full code
echo UssdHelper::formatUssdCode('01'); // "*920*01#"

// Get formatted base
echo UssdHelper::getBaseCodeFormatted(); // "*920*"
```

---

## 🎨 Using the Helper in Your Code

### **Example 1: Display USSD Code**

**Before (Hardcoded):**
```php
<span>*920*<?= $tenant['ussd_code'] ?>#</span>
```

**After (Dynamic):**
```php
<?php use SmartCast\Helpers\UssdHelper; ?>
<span><?= UssdHelper::formatUssdCode($tenant['ussd_code']) ?></span>
```

---

### **Example 2: In Controllers**

```php
use SmartCast\Helpers\UssdHelper;

class UssdController extends BaseController
{
    public function handleRequest()
    {
        $serviceCode = $_POST['serviceCode']; // "*920*01#"
        $tenantCode = UssdHelper::extractTenantCode($serviceCode); // "01"
        
        // Find tenant
        $tenant = $this->tenantModel->findAll(['ussd_code' => $tenantCode]);
    }
}
```

---

### **Example 3: Validation**

```php
use SmartCast\Helpers\UssdHelper;

// Validate tenant code format
if (!UssdHelper::isValidTenantCode('01')) {
    throw new Exception('Invalid tenant code');
}

// Get max tenants
$maxTenants = UssdHelper::getMaxTenants(); // 99

// Pad code
$paddedCode = UssdHelper::padTenantCode(1); // "01"
```

---

## 🔄 Changing Your Base Code

### **Scenario: You want to change from *920* to *713***

**Step 1: Update Configuration**
```php
// config/ussd_config.php
'base_code' => '713',  // Changed from 920
```

**Step 2: Update Hubtel**
- Register new code `*713*` with Hubtel
- Update callback URL if needed

**Step 3: Notify Users**
- Send email to all organizers
- Update marketing materials
- Update website

**Step 4: Test**
```
Old: *920*01# (will stop working)
New: *713*01# (now works)
```

---

## 📊 Multiple Base Codes (Advanced)

If you want different base codes for different tenants:

### **Option 1: Tenant-Specific Codes**
```php
// tenants table
tenant_id | ussd_base_code | ussd_tenant_code
1         | 920            | 01
2         | 713            | 01
3         | 920            | 02
```

### **Option 2: Plan-Based Codes**
```php
// Premium tenants get vanity codes
Premium: *920*EVENT#
Basic:   *920*01#
```

---

## ⚠️ Important Notes

### **1. Code Registration**
- Base codes must be registered with telecom provider
- Registration can take 1-3 business days
- Some codes may already be taken
- Costs vary by provider

### **2. Testing**
- Always test with real phone after changes
- Use test environment first
- Verify callback URL is HTTPS

### **3. Backwards Compatibility**
- Changing base code affects all tenants
- Old codes will stop working
- Plan migration carefully

### **4. Multiple Environments**
```env
# Development
USSD_BASE_CODE=920

# Staging
USSD_BASE_CODE=921

# Production
USSD_BASE_CODE=920
```

---

## 🧪 Testing Checklist

- [ ] Base code updated in config
- [ ] Environment variable set (if using)
- [ ] Hubtel dashboard updated
- [ ] Callback URL configured
- [ ] Test with real phone
- [ ] Verify tenant codes work
- [ ] Check error handling
- [ ] Update documentation

---

## 📞 Support

### **Hubtel Support**
- Email: support@hubtel.com
- Phone: +233 30 281 0800
- Dashboard: https://dashboard.hubtel.com

### **MTN Business**
- Phone: 0244300000
- Email: business@mtn.com.gh

---

## 🎯 Quick Reference

### **Current Setup:**
```
Base Code: 920
Format: *920*XX#
Max Tenants: 99
Provider: Hubtel
```

### **Change Base Code:**
```bash
# Edit .env
USSD_BASE_CODE=713

# Or edit config/ussd_config.php
'base_code' => '713'
```

### **Test:**
```bash
# Dial from phone
*713*01#

# Check in code
php -r "require 'vendor/autoload.php'; echo SmartCast\Helpers\UssdHelper::getBaseCode();"
```

---

**Your base USSD code is now configurable!** 🚀

For questions, contact support or check the documentation.
