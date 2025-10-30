# 🚀 Hướng Dẫn Deploy Laravel lên VPS Ubuntu

## 📋 Yêu Cầu

- VPS Ubuntu 20.04/22.04
- Domain đã trỏ về IP VPS
- SSH access với quyền root/sudo

---

## 🛠️ BƯỚC 1: Chuẩn Bị VPS

### 1.1. SSH vào VPS
```bash
ssh root@your-server-ip
```

### 1.2. Cập nhật hệ thống
```bash
sudo apt update && sudo apt upgrade -y
```

### 1.3. Cài đặt các gói cần thiết
```bash
sudo apt install -y software-properties-common curl git unzip
```

---

## 🐘 BƯỚC 2: Cài Đặt PHP 8.2

### 2.1. Thêm repository PHP
```bash
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update
```

### 2.2. Cài đặt PHP và extensions
```bash
sudo apt install -y php8.2 php8.2-fpm php8.2-cli php8.2-common \
    php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring \
    php8.2-curl php8.2-xml php8.2-bcmath php8.2-intl
```

### 2.3. Kiểm tra PHP version
```bash
php -v
```

---

## 🎼 BƯỚC 3: Cài Đặt Composer

```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
sudo chmod +x /usr/local/bin/composer
composer --version
```

---

## 🗄️ BƯỚC 4: Cài Đặt MySQL (Tùy chọn - nếu dùng database)

### 4.1. Cài đặt MySQL
```bash
sudo apt install -y mysql-server
```

### 4.2. Bảo mật MySQL
```bash
sudo mysql_secure_installation
```

### 4.3. Tạo database và user
```bash
sudo mysql -u root -p
```

Trong MySQL console:
```sql
CREATE DATABASE laravel_hh3d CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'laravel_user'@'localhost' IDENTIFIED BY 'your-strong-password';
GRANT ALL PRIVILEGES ON laravel_hh3d.* TO 'laravel_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

---

## 🌐 BƯỚC 5: Cài Đặt Nginx

### 5.1. Cài đặt Nginx
```bash
sudo apt install -y nginx
```

### 5.2. Kiểm tra status
```bash
sudo systemctl status nginx
```

### 5.3. Cấu hình firewall
```bash
sudo ufw allow 'Nginx Full'
sudo ufw allow OpenSSH
sudo ufw enable
```

---

## 📦 BƯỚC 6: Clone & Setup Project

### 6.1. Tạo thư mục cho project
```bash
sudo mkdir -p /var/www/hhkungfu
cd /var/www/hhkungfu
```

### 6.2. Clone project từ GitHub (hoặc upload)

**Option A: Clone từ Git**
```bash
sudo git clone https://github.com/your-username/laravel-hh3d.git .
```

**Option B: Upload bằng SCP từ máy local**
```bash
# Trên máy local (Windows)
scp -r E:\laravel-hh3d\example-app/* root@your-server-ip:/var/www/hhkungfu/
```

### 6.3. Set permissions
```bash
sudo chown -R www-data:www-data /var/www/hhkungfu
sudo chmod -R 755 /var/www/hhkungfu
sudo chmod -R 775 /var/www/hhkungfu/storage
sudo chmod -R 775 /var/www/hhkungfu/bootstrap/cache
```

### 6.4. Cài đặt dependencies
```bash
cd /var/www/hhkungfu
composer install --optimize-autoloader --no-dev
```

### 6.5. Tạo file .env
```bash
cp .env.example .env
nano .env
```

Cấu hình `.env`:
```env
APP_NAME=Hhkungfu
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://your-domain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel_hh3d
DB_USERNAME=laravel_user
DB_PASSWORD=your-strong-password

# API Configuration
API_URL=https://hh3d.id.vn/api
API_TIMEOUT=10
API_CACHE_TTL=300

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_DRIVER=file
```

### 6.6. Generate APP_KEY
```bash
php artisan key:generate
```

### 6.7. Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

## 🔧 BƯỚC 7: Cấu Hình Nginx

### 7.1. Tạo file config cho site
```bash
sudo nano /etc/nginx/sites-available/hhkungfu
```

Nội dung:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name your-domain.com www.your-domain.com;
    
    root /var/www/hhkungfu/public;
    index index.php index.html;

    # Charset
    charset utf-8;

    # Logs
    access_log /var/log/nginx/hhkungfu-access.log;
    error_log /var/log/nginx/hhkungfu-error.log;

    # Max upload size
    client_max_body_size 20M;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    # PHP-FPM
    location ~ \.php$ {
        include snippets/fastcgi-php.conf;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Timeout settings
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        access_log off;
    }

    # Deny access to hidden files
    location ~ /\. {
        deny all;
        access_log off;
        log_not_found off;
    }
}
```

### 7.2. Enable site
```bash
sudo ln -s /etc/nginx/sites-available/hhkungfu /etc/nginx/sites-enabled/
sudo rm /etc/nginx/sites-enabled/default
```

### 7.3. Test cấu hình Nginx
```bash
sudo nginx -t
```

### 7.4. Restart Nginx
```bash
sudo systemctl restart nginx
```

---

## 🔒 BƯỚC 8: Cài Đặt SSL với Let's Encrypt

### 8.1. Cài đặt Certbot
```bash
sudo apt install -y certbot python3-certbot-nginx
```

### 8.2. Lấy SSL certificate
```bash
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

### 8.3. Test auto-renewal
```bash
sudo certbot renew --dry-run
```

---

## 🎯 BƯỚC 9: Cấu Hình PHP-FPM

### 9.1. Edit PHP-FPM config
```bash
sudo nano /etc/php/8.2/fpm/pool.d/www.conf
```

Tìm và sửa:
```ini
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500
```

### 9.2. Edit php.ini
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```

Tìm và sửa:
```ini
memory_limit = 256M
upload_max_filesize = 20M
post_max_size = 20M
max_execution_time = 300
max_input_time = 300

; OPcache settings
opcache.enable=1
opcache.memory_consumption=128
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=10000
opcache.revalidate_freq=60
opcache.fast_shutdown=1
```

### 9.3. Restart PHP-FPM
```bash
sudo systemctl restart php8.2-fpm
```

---

## 🔄 BƯỚC 10: Setup Cron Jobs (Nếu cần)

```bash
sudo crontab -e -u www-data
```

Thêm:
```
* * * * * cd /var/www/hhkungfu && php artisan schedule:run >> /dev/null 2>&1
```

---

## 🚦 BƯỚC 11: Setup Process Manager (Supervisor - Nếu dùng Queue)

### 11.1. Cài đặt Supervisor
```bash
sudo apt install -y supervisor
```

### 11.2. Tạo config
```bash
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

Nội dung:
```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/hhkungfu/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/hhkungfu/storage/logs/worker.log
stopwaitsecs=3600
```

### 11.3. Start Supervisor
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

---

## ✅ BƯỚC 12: Kiểm Tra & Test

### 12.1. Kiểm tra services
```bash
sudo systemctl status nginx
sudo systemctl status php8.2-fpm
sudo systemctl status mysql
```

### 12.2. Kiểm tra logs
```bash
# Nginx logs
tail -f /var/log/nginx/hhkungfu-error.log

# Laravel logs
tail -f /var/www/hhkungfu/storage/logs/laravel.log

# PHP-FPM logs
tail -f /var/log/php8.2-fpm.log
```

### 12.3. Test website
```bash
curl -I https://your-domain.com
```

---

## 🔧 BƯỚC 13: Deploy Updates (Cập nhật code mới)

Tạo script deploy:

```bash
sudo nano /var/www/hhkungfu/deploy.sh
```

Nội dung:
```bash
#!/bin/bash

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /var/www/hhkungfu

# Enable maintenance mode
php artisan down

# Pull latest code
git pull origin main

# Install/update dependencies
composer install --optimize-autoloader --no-dev

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Optimize caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set correct permissions
sudo chown -R www-data:www-data /var/www/hhkungfu
sudo chmod -R 755 /var/www/hhkungfu
sudo chmod -R 775 /var/www/hhkungfu/storage
sudo chmod -R 775 /var/www/hhkungfu/bootstrap/cache

# Disable maintenance mode
php artisan up

echo "✅ Deployment completed!"
```

Chmod script:
```bash
sudo chmod +x /var/www/hhkungfu/deploy.sh
```

Sử dụng:
```bash
sudo /var/www/hhkungfu/deploy.sh
```

---

## 🔐 BƯỚC 14: Bảo Mật

### 14.1. Tắt directory listing
Đã có trong Nginx config

### 14.2. Ẩn phiên bản PHP
```bash
sudo nano /etc/php/8.2/fpm/php.ini
```
```ini
expose_php = Off
```

### 14.3. Ẩn phiên bản Nginx
```bash
sudo nano /etc/nginx/nginx.conf
```
```nginx
http {
    server_tokens off;
    ...
}
```

### 14.4. Fail2ban (chặn brute force)
```bash
sudo apt install -y fail2ban
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

### 14.5. Tắt các services không cần thiết
```bash
sudo systemctl disable apache2
```

---

## 📊 BƯỚC 15: Monitoring (Tùy chọn)

### 15.1. Cài đặt htop
```bash
sudo apt install -y htop
```

### 15.2. Giám sát logs real-time
```bash
# All nginx logs
sudo tail -f /var/log/nginx/*.log

# Laravel logs
sudo tail -f /var/www/hhkungfu/storage/logs/laravel.log
```

---

## 🎯 Quick Commands Cheat Sheet

```bash
# Restart all services
sudo systemctl restart nginx php8.2-fpm mysql

# Clear all Laravel caches
php artisan optimize:clear

# Rebuild all Laravel caches
php artisan optimize

# Check disk space
df -h

# Check memory usage
free -h

# Check running processes
ps aux | grep php

# View real-time logs
tail -f /var/www/hhkungfu/storage/logs/laravel.log
```

---

## 🐛 Troubleshooting

### Lỗi 500 Internal Server Error
```bash
# Check Laravel logs
tail -50 /var/www/hhkungfu/storage/logs/laravel.log

# Check Nginx error log
tail -50 /var/log/nginx/hhkungfu-error.log

# Check PHP-FPM error log
tail -50 /var/log/php8.2-fpm.log

# Fix permissions
sudo chown -R www-data:www-data /var/www/hhkungfu
sudo chmod -R 775 /var/www/hhkungfu/storage
sudo chmod -R 775 /var/www/hhkungfu/bootstrap/cache
```

### Lỗi 502 Bad Gateway
```bash
# Restart PHP-FPM
sudo systemctl restart php8.2-fpm

# Check PHP-FPM status
sudo systemctl status php8.2-fpm
```

### Lỗi permission denied
```bash
sudo chmod -R 775 /var/www/hhkungfu/storage
sudo chmod -R 775 /var/www/hhkungfu/bootstrap/cache
sudo chown -R www-data:www-data /var/www/hhkungfu
```

### Cache không clear
```bash
cd /var/www/hhkungfu
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
sudo systemctl restart php8.2-fpm nginx
```

---

## 🎉 Done!

Website của bạn giờ đã online tại: `https://your-domain.com`

### Next Steps:
1. ✅ Setup monitoring (Uptime Robot, New Relic)
2. ✅ Setup backups (database & files)
3. ✅ Configure CDN (Cloudflare)
4. ✅ Setup analytics (Google Analytics)
5. ✅ Submit sitemap to Google Search Console

---

## 📞 Support

Nếu gặp vấn đề:
1. Check logs: `/var/www/hhkungfu/storage/logs/laravel.log`
2. Check Nginx logs: `/var/log/nginx/hhkungfu-error.log`
3. Check PHP-FPM: `sudo systemctl status php8.2-fpm`

**Good luck! 🚀**

