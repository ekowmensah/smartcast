-- Check USSD code for tenant
SELECT id, name, ussd_code, ussd_enabled 
FROM tenants 
WHERE ussd_code = '734';

-- If not found, check what codes exist
SELECT id, name, ussd_code, ussd_enabled 
FROM tenants 
WHERE ussd_code IS NOT NULL;

-- Update tenant code to 734 if needed (replace 1 with your tenant ID)
-- UPDATE tenants SET ussd_code = '734', ussd_enabled = 1 WHERE id = 1;
