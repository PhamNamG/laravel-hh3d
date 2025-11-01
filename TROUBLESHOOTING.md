# 🔧 Troubleshooting Guide - 502 Bad Gateway

## ✅ Đã fix gì?

1. **PHP-FPM Configuration**: Thêm `www.conf` với đầy đủ config
2. **PHP Settings**: Thêm `php.ini` với timeout và memory limits
3. **Nginx Config**: Thêm fastcgi timeouts và buffers
4. **Supervisor**: Fix command và thêm priority, dependencies
5. **Startup Script**: Thêm config tests trước khi start
6. **Dockerfile**: Thêm bash, netcat, tạo thư mục cần thiết

## 🔍 Kiểm tra lỗi

### 1. Test Local trước

```bash
# Rebuild Docker image
docker-compose down
docker-compose build --no-cache
docker-compose up -d

# Xem logs
docker-compose logs -f app
```

### 2. Kiểm tra PHP-FPM đang chạy

```bash
# Vào container
docker exec -it <container-name> sh

# Kiểm tra PHP-FPM process
ps aux | grep php-fpm

# Kiểm tra PHP-FPM đang listen
netstat -tlnp | grep 9000
# Hoặc
ss -tlnp | grep 9000

# Test PHP-FPM config
php-fpm -t

# Kết quả mong đợi:
# [30-Oct-2025 12:00:00] NOTICE: configuration file /usr/local/etc/php-fpm.conf test is successful
```

### 3. Kiểm tra Nginx có kết nối được PHP-FPM không

```bash
# Trong container
curl -I http://127.0.0.1:9000
# Hoặc test với nc
nc -zv 127.0.0.1 9000

# Test Nginx config
nginx -t
```

### 4. Xem logs chi tiết

```bash
# PHP-FPM logs
tail -f /var/log/php-fpm-error.log

# PHP errors
tail -f /var/log/php-errors.log

# Nginx error log
tail -f /var/log/nginx/error.log

# Laravel logs
tail -f /var/www/html/storage/logs/laravel.log

# Supervisor logs
tail -f /var/log/supervisor/supervisord.log
```

## 🐛 Lỗi thường gặp

### ❌ Lỗi: "upstream sent too big header"

**Nguyên nhân**: Response headers quá lớn

**Giải pháp**: Đã fix trong `default.conf`:
```nginx
fastcgi_buffer_size 32k;
fastcgi_buffers 8 16k;
```

### ❌ Lỗi: "upstream timed out"

**Nguyên nhân**: Script PHP chạy quá lâu

**Giải pháp**: Đã fix trong `default.conf` và `php.ini`:
```nginx
fastcgi_read_timeout 300s;
```
```ini
max_execution_time = 300
```

### ❌ Lỗi: "Connection refused"

**Nguyên nhân**: PHP-FPM chưa start hoặc listen sai port

**Kiểm tra**:
```bash
# Xem PHP-FPM đang chạy không
supervisorctl status php-fpm

# Restart PHP-FPM
supervisorctl restart php-fpm

# Xem listen address
grep listen /usr/local/etc/php-fpm.d/www.conf
# Phải là: listen = 127.0.0.1:9000
```

### ❌ Lỗi: "File not found"

**Nguyên nhân**: SCRIPT_FILENAME không đúng hoặc file không tồn tại

**Kiểm tra**:
```bash
# Vào container
ls -la /var/www/html/public/index.php

# Check permissions
ls -la /var/www/html/public

# Phải có: -rw-r--r-- www-data www-data index.php
```

### ❌ Lỗi: PHP Fatal Error

**Xem log**:
```bash
tail -100 /var/www/html/storage/logs/laravel.log
```

**Lỗi thường gặp**:
1. **Missing APP_KEY**: Set trong Environment Variables
2. **Database connection**: Check DB_HOST, DB_PASSWORD
3. **Permission denied**: Check storage và cache permissions

## 🧪 Test Commands

### Test từ bên trong container

```bash
# Vào container
docker exec -it <container-name> bash

# Test PHP
php -v

# Test Laravel
php artisan --version

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Test PHP-FPM
curl -s http://127.0.0.1:9000 || echo "PHP-FPM không phản hồi"

# Test Nginx
curl -I http://127.0.0.1:10000/health
# Kết quả mong đợi: HTTP/1.1 200 OK
```

### Test PHP script trực tiếp

Tạo file test: `/var/www/html/public/info.php`
```php
<?php
phpinfo();
```

Truy cập: `http://localhost:10000/info.php`

**⚠️ XÓA file này sau khi test xong!**

## 📊 Debug trên Render

### Xem logs trên Render Dashboard

1. Vào Service → Logs
2. Tìm dòng:
   - ✅ "Starting services..."
   - ✅ "PHP-FPM will listen on 127.0.0.1:9000"
   - ❌ Bất kỳ ERROR nào

### Vào Shell trên Render

1. Dashboard → Service → Shell
2. Chạy commands:

```bash
# Check PHP-FPM
supervisorctl status

# Restart PHP-FPM nếu cần
supervisorctl restart php-fpm

# Xem logs
tail -50 /var/log/nginx/error.log

# Test Laravel
php artisan route:list
php artisan config:show app

# Test database
php artisan migrate:status
```

## 🔄 Rebuild nếu cần

### Local

```bash
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d
docker-compose logs -f
```

### Render

1. Dashboard → Service → Manual Deploy
2. Click "Clear build cache & deploy"
3. Đợi 3-5 phút
4. Xem logs real-time

## 📞 Nếu vẫn lỗi

Gửi cho tôi:

1. **Docker logs**:
```bash
docker-compose logs app > logs.txt
```

2. **Nginx error log**:
```bash
docker exec <container> cat /var/log/nginx/error.log
```

3. **PHP-FPM log**:
```bash
docker exec <container> cat /var/log/php-fpm-error.log
```

4. **Laravel log**:
```bash
docker exec <container> tail -100 /var/www/html/storage/logs/laravel.log
```

5. **Environment variables** (ẩn sensitive data):
```bash
docker exec <container> env | grep -E 'APP|DB'
```

---

## ✅ Checklist

- [ ] Rebuilt Docker image với `--no-cache`
- [ ] Kiểm tra PHP-FPM đang chạy (`ps aux | grep php-fpm`)
- [ ] Kiểm tra PHP-FPM listen port 9000 (`netstat -tlnp | grep 9000`)
- [ ] Test Nginx config (`nginx -t`)
- [ ] Test PHP-FPM config (`php-fpm -t`)
- [ ] Kiểm tra logs (`/var/log/nginx/error.log`)
- [ ] Kiểm tra permissions (`ls -la /var/www/html/public`)
- [ ] Test endpoint `/health` trả về 200 OK
- [ ] Kiểm tra environment variables đã set đúng

**Nếu tất cả đều ✅, site sẽ hoạt động! 🎉**





