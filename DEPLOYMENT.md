# Production Deployment & DevOps Guide
## Quotation and Proposal Builder: Send, Sign, Convert to Invoice

This guide details recommended deployment architectures, server provisioning, web server configurations, process supervision, and automation workflows.

---

## 1. Production Architecture Overview

The platform supports multiple hosting models:
- **Dedicated Cloud / VPS:** Ubuntu 24.04 LTS / Debian 12 with Nginx, PHP 8.3/8.5-FPM, MySQL 8.0/PostgreSQL 16, and Redis.
- **Containerized:** Docker & Docker Compose / Kubernetes.
- **Shared Hosting / cPanel:** Compatible with standard LAMP/LEMP stacks supporting Composer and terminal access.

---

## 2. Server Provisioning & PHP Configuration

Recommended PHP INI settings for production workloads:

```ini
memory_limit = 512M
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 120
opcache.enable = 1
opcache.memory_consumption = 256
opcache.interned_strings_buffer = 16
opcache.max_accelerated_files = 20000
opcache.validate_timestamps = 0
```

---

## 3. Nginx Web Server Configuration

Example production Nginx virtual host (`/etc/nginx/sites-available/quotation-saas.conf`):

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name quotation.your-domain.com *.quotation.your-domain.com;
    return 301 https://$host$request_uri;
}

server {
    listen 443 ssl http2;
    listen [::]:443 ssl http2;
    server_name quotation.your-domain.com *.quotation.your-domain.com;

    root /var/www/quotation-saas/public;
    index index.php index.html;

    # SSL Certificates (Let's Encrypt / Certbot)
    ssl_certificate /etc/letsencrypt/live/quotation.your-domain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/quotation.your-domain.com/privkey.pem;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;
    add_header Content-Security-Policy "default-src 'self' 'unsafe-inline' 'unsafe-eval' https: data:;" always;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # FastCGI PHP Handler
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    # Deny access to hidden files (.env, .git)
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## 4. Background Queue Worker with Supervisor

Configure Supervisor (`/etc/supervisor/conf.d/quotation-worker.conf`) to guarantee continuous queue processing for emails, PDFs, and webhooks:

```ini
[program:quotation-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/quotation-saas/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/quotation-saas/storage/logs/worker.log
stopwaitsecs=3600
```

Apply and start the worker:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start quotation-worker:*
```

---

## 5. Cron Job Configuration (Scheduler)

Add the Laravel scheduler to the web user's crontab:

```bash
sudo crontab -u www-data -e
```

Add the single line:
```cron
* * * * * cd /var/www/quotation-saas && php artisan schedule:run >> /dev/null 2>&1
```

This handles:
- Daily overdue invoice checks.
- Quotation expiry notifications.
- Dunning reminders.
- Biometric attendance synchronization cycles.

---

## 6. Zero-Downtime Deployment Script

Example shell deployment script (`deploy.sh`):

```bash
#!/usr/bin/env bash
set -e

echo "Deploying Quotation & Proposal Builder..."

# 1. Maintenance Mode
php artisan down --render="errors::503" --secret="deploy-bypass-token"

# 2. Pull Latest Changes
git pull origin main

# 3. Install Composer Dependencies (No Dev)
composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

# 4. Compile Frontend Assets
npm ci
npm run build

# 5. Database Migrations
php artisan migrate --force

# 6. Performance Caching (with drutogoti engine)
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# 7. Restart Queue Workers
php artisan queue:restart

# 8. Restore Service
php artisan up

echo "Deployment finished successfully!"
```

---

## 7. Automated Backups & Disaster Recovery

Ensure scheduled daily database and storage backups using automated tools or cron:

```bash
# Automated database dump
mysqldump -u your_user -p your_database | gzip > /var/backups/db_$(date +\%Y\%m\%d).sql.gz

# Sync to S3 or remote storage
aws s3 sync /var/backups s3://your-company-backup-bucket/databases/
```

---

*Architected & Maintained by **Imran Ahmed** (`me@imrandev.bd`) • [https://imrandev.bd](https://imrandev.bd)*
