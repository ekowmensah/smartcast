# 🎯 USSD Management System - Complete Guide

## Overview

The USSD Management System allows Super Admins to assign USSD codes to tenants and enables organizers to manage their USSD voting settings.

---

## 🔐 Access Levels

### **Super Admin**
- Assign/revoke USSD codes
- Enable/disable USSD for tenants
- View all USSD statistics
- Manage USSD code pool (01-99)

### **Organizer/Tenant**
- View assigned USSD code
- Customize welcome message
- View USSD usage statistics
- Cannot change code or status

---

## 📋 Super Admin Features

### **USSD Management Dashboard**

**Access:** `https://yourdomain.com/superadmin/ussd`

**Features:**
- ✅ View all tenants and their USSD codes
- ✅ See available USSD codes (01-99)
- ✅ Platform-wide USSD statistics
- ✅ Assign/edit/revoke codes
- ✅ Enable/disable USSD per tenant

### **Statistics Displayed:**
- **Total Tenants:** All organizations on platform
- **Assigned Codes:** Tenants with USSD codes
- **Active USSD:** Currently enabled tenants
- **Available Codes:** Unassigned codes (01-99)

### **Actions Available:**

#### **1. Assign USSD Code**
```
1. Click "Assign Code" button for tenant
2. Select available 2-digit code (01-99)
3. Optional: Add custom welcome message
4. Click "Assign Code"
5. Code is immediately active
```

**Result:** Tenant can now use *920*XX# for voting

#### **2. Edit USSD Code**
```
1. Click "Edit" button for tenant
2. Change USSD code or welcome message
3. Save changes
```

**Note:** Changing code requires re-registration with Hubtel

#### **3. Enable/Disable USSD**
```
1. Click "Enable" or "Disable" button
2. Confirm action
3. Status updates immediately
```

**Use Case:** Temporarily disable USSD without revoking code

#### **4. Revoke USSD Code**
```
1. Click "Revoke" button
2. Confirm action
3. Code becomes available for other tenants
```

**Warning:** This disables USSD voting for the tenant

---

## 👤 Organizer Features

### **USSD Settings Page**

**Access:** `https://yourdomain.com/organizer/settings/ussd`

**Features:**
- ✅ View assigned USSD code
- ✅ See USSD status (Active/Disabled)
- ✅ Customize welcome message
- ✅ View usage statistics
- ✅ How-to guide for voters

### **Statistics Displayed:**
- **Total Sessions:** All USSD sessions
- **Unique Users:** Different phone numbers
- **Successful Votes:** Completed votes
- **Last Session:** Most recent activity

### **Customizable Settings:**

#### **Welcome Message**
```
Default: "Welcome to [Tenant Name]!"
Custom: "Welcome to Ghana Music Awards 2025!"

Rules:
- Keep short (max 30 chars recommended)
- Will be truncated if too long
- Shown when users dial USSD code
```

**Example:**
```
Input: "Welcome to Sample Event of the Year of 2025"
Display: "Welcome to Sample Event o"
```

---

## 🔧 Technical Implementation

### **Database Schema**

```sql
-- Tenants table columns
ussd_code VARCHAR(10)              -- 2-digit code (01-99)
ussd_enabled TINYINT(1)            -- 1 = enabled, 0 = disabled
ussd_welcome_message TEXT          -- Custom welcome message

-- USSD Sessions table columns
tenant_id INT(11)                  -- Links session to tenant
service_code VARCHAR(20)           -- Full code (*920*01#)
```

### **Files Created**

**Controllers:**
- `src/Controllers/UssdManagementController.php` - Main controller

**Views:**
- `views/superadmin/ussd/dashboard.php` - Super admin dashboard
- `views/organizer/settings/ussd.php` - Organizer settings page

**Routes:**
```php
// Super Admin
GET  /superadmin/ussd              → Dashboard
POST /superadmin/ussd/assign       → Assign code
POST /superadmin/ussd/revoke       → Revoke code
POST /superadmin/ussd/toggle       → Enable/disable

// Organizer
GET  /organizer/settings/ussd      → Settings page
POST /organizer/settings/ussd/update → Update settings
```

---

## 📱 USSD Code Format

### **Structure:**
```
*920*XX#

Where:
- 920 = Base code (fixed)
- XX  = Tenant code (01-99)
- #   = USSD terminator
```

### **Examples:**
```
*920*01# → Tenant 1
*920*02# → Tenant 2
*920*15# → Tenant 15
*920*99# → Tenant 99
```

### **Total Capacity:**
- **99 unique codes** (01-99)
- Each tenant gets one code
- Codes can be reassigned

---

## 🎯 Workflow Examples

### **Example 1: New Tenant Setup**

**Super Admin:**
1. Tenant registers on platform
2. Super admin logs in
3. Goes to `/superadmin/ussd`
4. Clicks "Assign Code" for new tenant
5. Selects code "05"
6. Adds welcome message: "Welcome to EventCo!"
7. Saves

**Result:**
- Tenant gets *920*05#
- USSD enabled automatically
- Tenant can customize message

**Organizer:**
1. Logs into organizer dashboard
2. Goes to Settings → USSD
3. Sees assigned code: *920*05#
4. Customizes welcome message
5. Views usage statistics

### **Example 2: Temporary Disable**

**Scenario:** Tenant requests to pause USSD during event setup

**Super Admin:**
1. Goes to `/superadmin/ussd`
2. Finds tenant
3. Clicks "Disable"
4. Confirms

**Result:**
- USSD code still assigned
- Users get "disabled" message when dialing
- Can re-enable anytime

### **Example 3: Code Reassignment**

**Scenario:** Tenant closes account, need to reuse code

**Super Admin:**
1. Revokes code from old tenant
2. Code becomes available
3. Assigns to new tenant
4. New tenant uses same code

---

## 📊 Statistics & Monitoring

### **Super Admin Dashboard:**
```
Total Tenants:      50
Assigned Codes:     35
Active USSD:        30
Available Codes:    64
```

### **Organizer Dashboard:**
```
Total Sessions:     1,250
Unique Users:       850
Successful Votes:   1,100
Last Session:       2 hours ago
```

---

## 🔒 Security & Permissions

### **Super Admin Only:**
- ✅ Assign USSD codes
- ✅ Revoke USSD codes
- ✅ Enable/disable USSD
- ✅ View all tenant codes

### **Organizer Only:**
- ✅ View own USSD code
- ✅ Update welcome message
- ✅ View own statistics
- ❌ Cannot change code
- ❌ Cannot enable/disable

### **Audit Trail:**
All USSD management actions are logged for security.

---

## 🚀 Deployment Steps

### **1. Run Database Migration**
```bash
mysql -u username -p database < migrations/add_multi_tenant_ussd.sql
```

### **2. Verify Routes**
Check that routes are registered in `src/Core/Application.php`

### **3. Test Super Admin Access**
```
1. Login as super admin
2. Navigate to /superadmin/ussd
3. Verify dashboard loads
4. Test assigning a code
```

### **4. Test Organizer Access**
```
1. Login as organizer
2. Navigate to /organizer/settings/ussd
3. Verify settings page loads
4. Test updating welcome message
```

### **5. Register with Hubtel**
```
1. Login to Hubtel Dashboard
2. Register USSD codes
3. Set callback URL
4. Test with real phone
```

---

## 📝 Best Practices

### **For Super Admins:**

1. **Code Assignment:**
   - Assign codes sequentially (01, 02, 03...)
   - Keep track of which codes are premium
   - Document code assignments

2. **Welcome Messages:**
   - Review tenant messages for appropriateness
   - Ensure messages are clear and professional
   - Keep them short

3. **Monitoring:**
   - Check USSD statistics regularly
   - Identify inactive codes
   - Reassign unused codes

### **For Organizers:**

1. **Welcome Message:**
   - Keep it short and clear
   - Include brand name
   - Test how it displays

2. **Testing:**
   - Test USSD flow regularly
   - Verify payment integration
   - Check all menu options

3. **User Communication:**
   - Promote USSD code to voters
   - Include in marketing materials
   - Provide clear instructions

---

## 🆘 Troubleshooting

### **Issue: "USSD Code Not Assigned"**
**Solution:** Contact super admin to assign a code

### **Issue: "USSD Disabled"**
**Solution:** Contact super admin to enable USSD

### **Issue: "Code Already Taken"**
**Solution:** Super admin needs to select different code

### **Issue: "Welcome Message Too Long"**
**Solution:** System auto-truncates to 15 chars (excluding "Welcome to")

### **Issue: "USSD Not Working"**
**Checklist:**
- [ ] Database migration run
- [ ] USSD code assigned
- [ ] USSD enabled for tenant
- [ ] Hubtel USSD registered
- [ ] Callback URL configured
- [ ] HTTPS active

---

## 📞 Support

### **For Tenants:**
- Contact platform support
- Email: support@smartcast.com
- Request USSD code assignment

### **For Super Admins:**
- Check logs: `tail -f error.log | grep USSD`
- Verify database: `SELECT * FROM tenants WHERE ussd_code IS NOT NULL`
- Test endpoint: `/superadmin/ussd`

---

## 🎓 Training Materials

### **Super Admin Training:**
1. Understanding USSD codes
2. Assigning codes to tenants
3. Managing USSD status
4. Monitoring usage

### **Organizer Training:**
1. Viewing your USSD code
2. Customizing welcome message
3. Understanding statistics
4. Promoting USSD to voters

---

## ✅ Summary

**Super Admin Can:**
- Assign USSD codes (01-99)
- Enable/disable USSD per tenant
- Revoke codes
- View all statistics

**Organizer Can:**
- View assigned code
- Customize welcome message
- View usage statistics
- Access how-to guides

**System Supports:**
- 99 unique USSD codes
- Multi-tenant isolation
- Real-time statistics
- Secure code management

---

**USSD Management System is ready for production!** 🚀
