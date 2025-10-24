-- Test USSD Update Issue
-- Run these queries to debug the organizer update problem

-- 1. Check if USSD columns exist in tenants table
DESCRIBE tenants;

-- 2. Check current USSD data for a tenant (replace X with your tenant ID)
SELECT id, name, ussd_code, ussd_enabled, ussd_welcome_message 
FROM tenants 
WHERE id = 1;

-- 3. Manually test update (replace X with your tenant ID)
UPDATE tenants 
SET ussd_welcome_message = 'Test Welcome Message' 
WHERE id = 1;

-- 4. Verify the update worked
SELECT id, name, ussd_code, ussd_enabled, ussd_welcome_message 
FROM tenants 
WHERE id = 1;

-- 5. Check all tenants with USSD codes
SELECT id, name, ussd_code, ussd_enabled, 
       SUBSTRING(ussd_welcome_message, 1, 50) as welcome_msg
FROM tenants 
WHERE ussd_code IS NOT NULL;

-- 6. If columns don't exist, add them
-- ALTER TABLE tenants ADD COLUMN ussd_code VARCHAR(10) NULL AFTER plan;
-- ALTER TABLE tenants ADD COLUMN ussd_enabled TINYINT(1) DEFAULT 0 AFTER ussd_code;
-- ALTER TABLE tenants ADD COLUMN ussd_welcome_message TEXT NULL AFTER ussd_enabled;
