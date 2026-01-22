# 🚀 Deployment Guide

Complete guide for deploying the Amusement Park Management System to production.

## Pre-Deployment Checklist

- [ ] All tests passing (`php artisan test`)
- [ ] Code reviewed and merged to main branch
- [ ] Database backups created
- [ ] Environment variables configured
- [ ] SSL certificate installed
- [ ] Email service configured
- [ ] Payment gateway configured (if applicable)
- [ ] File storage configured
- [ ] Cache cleared
- [ ] Assets compiled for production
- [ ] Monitoring and logging configured

## Environment Setup

### 1. Server Requirements

**Minimum Requirements:**
- PHP 8.0+
- MySQL 5.7+
- 2 GB RAM
- 10 GB Storage
- Ubuntu 18.04+ or CentOS 7+

**Recommended:**
- PHP 8.1+
- MySQL 8.0+
- 4 GB RAM
- 50 GB SSD Storage
- Ubuntu 22.04 LTS or CentOS 8

### 2. System Dependencies

```bash
sudo apt update
sudo apt upgrade -y

# PHP and Extensions
sudo apt install -y php8.1 php8.1-cli php8.1-fpm
sudo apt install -y php8.1-mysql php8.1-mbstring php8.1-xml
sudo apt install -y php8.1-bcmath php8.1-curl php8.1-gd
sudo apt install -y php8.1-zip php8.1-intl php8.1-opcache

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Node.js and npm
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# MySQL
sudo apt install -y mysql-server

# Nginx
sudo apt install -y nginx
```

### 3. Database Setup

```bash
# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE amusement_park_production;
CREATE USER 'app_user'@'localhost' IDENTIFIED BY 'strong_password_here';
GRANT ALL PRIVILEGES ON amusement_park_production.* TO 'app_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

## Application Deployment

### 1. Deploy Code

```bash
# Create app directory
sudo mkdir -p /var/www/amusement-park
cd /var/www/amusement-park

# Clone repository
sudo git clone <repository-url> .

# Set permissions
sudo chown -R www-data:www-data /var/www/amusement-park
sudo chmod -R 755 /var/www/amusement-park
sudo chmod -R 775 storage bootstrap/cache
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install frontend dependencies
npm install
npm run build
```

### 3. Configure Environment

```bash
# Copy example env file
cp .env.example .env

# Edit .env file
sudo nano .env
```

**Important .env settings for production:**

```env
APP_NAME="Amusement Park Management System"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=single
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=amusement_park_production
DB_USERNAME=app_user
DB_PASSWORD=strong_password_here

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_FROM_ADDRESS=noreply@amusementpark.com
MAIL_FROM_NAME="Amusement Park"

SANCTUM_STATEFUL_DOMAINS=yourdomain.com
SESSION_DOMAIN=yourdomain.com

# Optional: Image/Video storage
AWS_ACCESS_KEY_ID=your_key
AWS_SECRET_ACCESS_KEY=your_secret
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=amusement-park
AWS_URL=https://your-bucket.s3.amazonaws.com
```

### 4. Generate Key and Setup

```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Database Migration

```bash
# Run migrations
php artisan migrate --force

# Seed data (optional)
php artisan db:seed --class=DatabaseSeeder
```

## Web Server Configuration

### Nginx Configuration

Create `/etc/nginx/sites-available/amusement-park`:

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/amusement-park/public;
    index index.php;

    # Redirect HTTP to HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/amusement-park/public;
    index index.php;

    # SSL certificates
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    # Security headers
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_types text/plain text/css text/xml text/javascript 
               application/x-javascript application/xml+rss;

    # Log files
    access_log /var/log/nginx/amusement-park-access.log;
    error_log /var/log/nginx/amusement-park-error.log;

    # Deny access to dotfiles
    location ~ /\. {
        deny all;
    }

    # Deny access to sensitive files
    location ~ /\.(env|htaccess)$ {
        deny all;
    }

    # Laravel front controller
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Static assets caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 365d;
        add_header Cache-Control "public, immutable";
    }
}
```

Enable site:
```bash
sudo ln -s /etc/nginx/sites-available/amusement-park /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### SSL Certificate (Let's Encrypt)

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot certonly --nginx -d yourdomain.com -d www.yourdomain.com
```

## Process Management

### Using Supervisor

Install:
```bash
sudo apt install -y supervisor
```

Create `/etc/supervisor/conf.d/amusement-park.conf`:

```ini
[program:amusement-park-laravel-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/amusement-park/artisan queue:work --queue=default --tries=3 --timeout=90
autostart=true
autorestart=true
numprocs=2
redirect_stderr=true
stdout_logfile=/var/log/supervisor/amusement-park-queue.log
user=www-data
```

Reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start amusement-park-laravel-queue:*
```

### PHP-FPM

Create `/etc/php/8.1/fpm/pool.d/amusement-park.conf`:

```ini
[amusement-park]
user = www-data
group = www-data
listen = /run/php/php8.1-fpm-amusement-park.sock
listen.owner = www-data
listen.group = www-data
pm = dynamic
pm.max_children = 20
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 15
pm.max_requests = 500
```

Restart:
```bash
sudo systemctl restart php8.1-fpm
```

## Database Optimization

### Backup Script

Create `/var/www/scripts/backup-db.sh`:

```bash
#!/bin/bash

BACKUP_DIR="/backups/mysql"
DATE=$(date +"%Y%m%d_%H%M%S")
DB_NAME="amusement_park_production"

mkdir -p $BACKUP_DIR

mysqldump -u app_user -p$DB_PASSWORD $DB_NAME | gzip > $BACKUP_DIR/$DB_NAME\_$DATE.sql.gz

# Keep only last 7 days
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

echo "Backup completed: $BACKUP_DIR/$DB_NAME\_$DATE.sql.gz"
```

Schedule with cron:
```bash
# Run daily at 2 AM
0 2 * * * /var/www/scripts/backup-db.sh
```

### Database Indexes

```sql
-- Optimize rides table
ALTER TABLE rides ADD INDEX idx_status (status);
ALTER TABLE rides ADD INDEX idx_created_at (created_at);

-- Optimize bookings table
ALTER TABLE bookings ADD INDEX idx_user_id (user_id);
ALTER TABLE bookings ADD INDEX idx_status (status);
ALTER TABLE bookings ADD INDEX idx_booking_date (booking_date);

-- Optimize rooms table
ALTER TABLE rooms ADD INDEX idx_status (status);
ALTER TABLE rooms ADD INDEX idx_price (price);
```

## Monitoring & Logging

### Application Logging

Configure in `.env`:
```env
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

View logs:
```bash
tail -f storage/logs/laravel-*.log
```

### Monitoring Tools

**Install Monitoring Stack:**

```bash
# Prometheus
sudo apt install -y prometheus

# Grafana
sudo apt install -y grafana-server

# Node Exporter
wget https://github.com/prometheus/node_exporter/releases/download/v1.5.0/node_exporter-1.5.0.linux-amd64.tar.gz
```

### Error Tracking

Configure Sentry for error tracking:

```bash
composer require sentry/sentry-laravel
```

Update `.env`:
```env
SENTRY_LARAVEL_DSN=your_sentry_dsn
```

## Performance Optimization

### Caching

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### Database Query Optimization

Use eager loading:
```php
$bookings = Booking::with('user', 'ride')->get();
```

Use database indexes for frequently queried columns.

### Redis Caching

```bash
sudo apt install -y redis-server

# Configure in .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### CDN Configuration

For static assets, use CloudFlare or AWS CloudFront:

```env
AWS_CDN_URL=https://cdn.amusementpark.com
ASSET_URL=https://cdn.amusementpark.com
```

## Security Hardening

### 1. Firewall Configuration

```bash
# Enable UFW
sudo ufw enable

# Allow SSH
sudo ufw allow 22/tcp

# Allow HTTP/HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Deny all other inbound traffic
sudo ufw default deny incoming
sudo ufw default allow outgoing
```

### 2. SSH Hardening

Edit `/etc/ssh/sshd_config`:

```bash
Port 22
PermitRootLogin no
PasswordAuthentication no
PubkeyAuthentication yes
X11Forwarding no
MaxAuthTries 3
ClientAliveInterval 300
ClientAliveCountMax 2
```

Restart:
```bash
sudo systemctl restart sshd
```

### 3. File Permissions

```bash
# Correct permissions
sudo chown -R www-data:www-data /var/www/amusement-park
sudo find /var/www/amusement-park -type f -exec chmod 644 {} \;
sudo find /var/www/amusement-park -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/amusement-park/storage
sudo chmod -R 775 /var/www/amusement-park/bootstrap/cache
```

### 4. Environment Variable Security

Ensure `.env` is not in git:
```bash
# Check .gitignore
grep "^\.env$" .gitignore
```

## Deployment Automation

### GitHub Actions

Create `.github/workflows/deploy.yml`:

```yaml
name: Deploy to Production

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Install dependencies
      run: composer install --no-dev --optimize-autoloader
    
    - name: Run tests
      run: php artisan test
    
    - name: Deploy
      run: |
        mkdir -p ~/.ssh
        echo "${{ secrets.DEPLOY_KEY }}" > ~/.ssh/deploy_key
        chmod 600 ~/.ssh/deploy_key
        ssh-keyscan -H ${{ secrets.DEPLOY_HOST }} >> ~/.ssh/known_hosts
        ssh -i ~/.ssh/deploy_key deploy@${{ secrets.DEPLOY_HOST }} 'cd /var/www/amusement-park && ./deploy.sh'
```

### Deployment Script

Create `deploy.sh`:

```bash
#!/bin/bash
set -e

echo "🚀 Starting deployment..."

# Pull latest code
git pull origin main

# Install dependencies
composer install --no-dev --optimize-autoloader

# Install frontend dependencies
npm ci
npm run build

# Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run migrations
php artisan migrate --force

# Restart services
sudo systemctl restart php8.1-fpm
sudo systemctl restart nginx
sudo supervisorctl restart all

echo "✅ Deployment completed successfully!"
```

Make executable:
```bash
chmod +x deploy.sh
```

## Post-Deployment

### 1. Verify Application

```bash
# Check PHP version
php -v

# Check Laravel version
php artisan --version

# Check services
sudo systemctl status nginx
sudo systemctl status php8.1-fpm
sudo systemctl status mysql
```

### 2. Test Application

```bash
# Test health endpoint
curl https://yourdomain.com/health

# Test API
curl -X GET https://yourdomain.com/api/rides \
  -H "Accept: application/json"
```

### 3. Monitor Logs

```bash
# Monitor Nginx
tail -f /var/log/nginx/amusement-park-error.log

# Monitor PHP
tail -f /var/log/php-fpm.log

# Monitor Laravel
tail -f storage/logs/laravel-*.log
```

## Rollback Procedure

If deployment fails:

```bash
# Revert to previous commit
git revert HEAD

# Run migrations down
php artisan migrate:rollback

# Clear caches
php artisan cache:clear

# Restart services
sudo systemctl restart nginx php8.1-fpm
```

## Maintenance Mode

```bash
# Enable maintenance mode
php artisan down --secret=secrettoken

# Access during maintenance
https://yourdomain.com/?secret=secrettoken

# Disable maintenance mode
php artisan up
```

## Support & Resources

- [Laravel Deployment Guide](https://laravel.com/docs/deployment)
- [Nginx Configuration](https://nginx.org/en/docs/)
- [MySQL Optimization](https://dev.mysql.com/doc/refman/8.0/en/)
- [Let's Encrypt](https://letsencrypt.org/)

---

**Last Updated**: January 2024  
**Status**: Production Ready
