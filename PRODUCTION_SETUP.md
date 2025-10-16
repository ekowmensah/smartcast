# 🚀 SmartCast Production Setup Guide

## ❌ Current Issue
```
Fatal error: Class "PHPMailer\PHPMailer\PHPMailer" not found
```

## ✅ Solution: Install PHPMailer on Production

### Option 1: Automatic Deployment (Recommended)

1. **Upload deployment script to production server:**
   ```bash
   scp deploy_to_production.sh user@your-server:/home/smartcast/
   ```

2. **Run deployment script:**
   ```bash
   ssh user@your-server
   cd /home/smartcast
   chmod +x deploy_to_production.sh
   ./deploy_to_production.sh
   ```

### Option 2: Manual Installation

1. **SSH into production server:**
   ```bash
   ssh user@your-server
   cd /home/smartcast/public_html
   ```

2. **Install Composer (if not installed):**
   ```bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

3. **Install PHPMailer:**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```

4. **Set permissions:**
   ```bash
   chmod -R 755 vendor/
   chmod -R 644 vendor/*/
   ```

### Option 3: Upload Vendor Folder

If you can't run Composer on production:

1. **Create archive locally:**
   ```cmd
   cd C:\xampp\htdocs\smartcast
   tar -czf vendor.tar.gz vendor/ composer.lock
   ```

2. **Upload and extract:**
   ```bash
   # Upload vendor.tar.gz to /home/smartcast/public_html/
   cd /home/smartcast/public_html
   tar -xzf vendor.tar.gz
   chmod -R 755 vendor/
   ```

## 🔧 Verification

After installation, test with:

```bash
cd /home/smartcast/public_html
php -r "
require_once 'vendor/autoload.php';
require_once 'src/Core/Application.php';
use SmartCast\Services\EmailServiceFactory;
echo 'Status: ' . print_r(EmailServiceFactory::getStatus(), true);
"
```

## 📧 Email Configuration

Create `.env` file with:

```env
# Email Configuration
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your-email@gmail.com
SMTP_PASSWORD=your-app-password
SMTP_ENCRYPTION=tls
FROM_EMAIL=noreply@smartcast.com.gh
FROM_NAME=SmartCast
SUPER_ADMIN_EMAIL=admin@smartcast.com.gh
SUPER_ADMIN_NAME=Super Admin
```

## 🎯 Expected Results

- ✅ No more PHPMailer errors
- ✅ Registration emails work
- ✅ Automatic fallback to simple email if needed
- ✅ Better error handling and logging

## 🆘 Troubleshooting

### If PHPMailer still not found:
1. Check if `vendor/autoload.php` exists
2. Verify `includes/autoloader.php` includes composer autoloader
3. Check file permissions

### If emails still fail:
1. Configure SMTP settings in `.env`
2. Check server firewall (port 587/465)
3. Use app passwords for Gmail
4. System will fallback to PHP mail() function

## 📁 Files Modified

- ✅ `composer.json` - Added PHPMailer dependency
- ✅ `includes/autoloader.php` - Added composer autoloader
- ✅ `src/Services/EmailServiceFactory.php` - Smart email service selection
- ✅ `src/Controllers/AuthController.php` - Uses factory for email service
- ✅ `src/Services/EmailService.php` - Fixed APP_URL constant issue

## 🎉 Production Ready!

After following these steps, your SmartCast application will:
- Handle email services gracefully
- Work with or without PHPMailer
- Provide better error messages
- Continue functioning even if email fails
