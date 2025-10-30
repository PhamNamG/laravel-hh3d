# ✅ Đã fix lỗi 502 Bad Gateway

## 🔍 Nguyên nhân lỗi 502

Lỗi **502 Bad Gateway** xảy ra khi:
- Nginx không thể kết nối với PHP-FPM backend
- PHP-FPM chưa được cấu hình đúng hoặc không chạy
- Thiếu timeout và buffer settings

## 🛠️ Những gì đã fix

### 1. ✅ Thêm PHP-FPM Configuration (`docker/www.conf`)

**Vấn đề**: PHP-FPM không có config pool, không biết listen ở đâu

**Fix**: Tạo file `docker/www.conf` với:
- `listen = 127.0.0.1:9000` - Địa chỉ PHP-FPM listen
- Process manager settings (pm.*)
- Request timeout: 300s
- Catch worker output
- Environment variables

### 2. ✅ Thêm PHP Configuration (`docker/php.ini`)

**Vấn đề**: PHP dùng default settings (quá thấp cho production)

**Fix**: Tạo file `docker/php.ini` với:
```ini
memory_limit = 256M
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
opcache.enable = 1
```

### 3. ✅ Fix Supervisor Command (`docker/supervisord.conf`)

**Vấn đề cũ**:
```conf
command=php-fpm8 -F  ❌ Sai command trên Alpine
```

**Fix mới**:
```conf
command=/usr/local/sbin/php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.d/www.conf
priority=1
```

- Nginx có `priority=2` và `depends_on=php-fpm` → PHP-FPM start trước

### 4. ✅ Cải thiện Nginx Config (`docker/default.conf`)

**Vấn đề**: Thiếu timeout và buffer cho FastCGI

**Fix thêm**:
```nginx
location ~ \.php$ {
    try_files $uri =404;  # Tránh lỗi file not found
    
    # Timeouts
    fastcgi_connect_timeout 60s;
    fastcgi_send_timeout 300s;
    fastcgi_read_timeout 300s;
    
    # Buffers
    fastcgi_buffer_size 32k;
    fastcgi_buffers 8 16k;
    fastcgi_busy_buffers_size 32k;
}
```

### 5. ✅ Update Dockerfile

**Thêm packages**:
```dockerfile
bash              # Để chạy start.sh script
netcat-openbsd    # Để test port connections
```

**Copy PHP configs**:
```dockerfile
COPY docker/www.conf /usr/local/etc/php-fpm.d/www.conf
COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
```

**Tạo directories cần thiết**:
```dockerfile
RUN mkdir -p /var/log/supervisor /run/php
```

**Skip cache nếu không có .env** (tránh lỗi khi build):
```dockerfile
RUN if [ -f .env ]; then \
        php artisan config:cache && \
        php artisan route:cache && \
        php artisan view:cache; \
    fi
```

### 6. ✅ Cải thiện Startup Script (`docker/start.sh`)

**Thêm testing trước khi start**:
```bash
# Test PHP-FPM configuration
php-fpm -t || { echo "PHP-FPM config test failed!"; exit 1; }

# Test Nginx configuration
nginx -t || { echo "Nginx config test failed!"; exit 1; }
```

**Better logging**:
```bash
echo "========================================="
echo "PHP-FPM will listen on 127.0.0.1:9000"
echo "Nginx will listen on 0.0.0.0:10000"
echo "========================================="
```

## 📋 File structure sau khi fix

```
example-app/
├── Dockerfile                    # ✅ Updated
├── .dockerignore
├── docker-compose.yml
├── render.yaml
├── docker/
│   ├── nginx.conf
│   ├── default.conf             # ✅ Updated (thêm timeouts & buffers)
│   ├── www.conf                 # ✅ NEW (PHP-FPM pool config)
│   ├── php.ini                  # ✅ NEW (PHP settings)
│   ├── supervisord.conf         # ✅ Updated (fix command)
│   └── start.sh                 # ✅ Updated (thêm tests)
├── DEPLOYMENT.md
├── DEPLOY-QUICK-START.md
├── TROUBLESHOOTING.md           # ✅ NEW (debug guide)
└── FIXED-502-ERROR.md           # ✅ NEW (file này)
```

## 🚀 Cách test fix

### Test 1: Local với Docker Compose

```bash
# Rebuild từ đầu
docker-compose down -v
docker-compose build --no-cache
docker-compose up -d

# Xem logs
docker-compose logs -f app

# Kiểm tra output phải có:
# ✅ "Starting Laravel application..."
# ✅ "Testing PHP-FPM configuration..."
# ✅ "Testing Nginx configuration..."
# ✅ "All tests passed! Starting services..."
# ✅ "PHP-FPM will listen on 127.0.0.1:9000"
```

### Test 2: Health Check

```bash
# Test endpoint
curl http://localhost:10000/health
# Kết quả: healthy

# Test status code
curl -I http://localhost:10000/health
# Kết quả: HTTP/1.1 200 OK
```

### Test 3: Kiểm tra PHP-FPM

```bash
# Vào container
docker exec -it <container-name> bash

# Check PHP-FPM process
ps aux | grep php-fpm
# Phải thấy nhiều php-fpm processes

# Check listening port
netstat -tlnp | grep 9000
# Kết quả: tcp  0  0  127.0.0.1:9000  0.0.0.0:*  LISTEN

# Check supervisor status
supervisorctl status
# php-fpm    RUNNING   pid 123, uptime 0:01:00
# nginx      RUNNING   pid 124, uptime 0:01:00
```

## 🎯 Deploy lên Render

```bash
# 1. Commit changes
git add .
git commit -m "Fix 502 Bad Gateway - Add PHP-FPM configuration"
git push origin main

# 2. Render sẽ auto-deploy
# 3. Xem logs trong Render Dashboard
# 4. Test: https://your-app.onrender.com/health
```

## 📊 Kết quả mong đợi

### ✅ Logs thành công:

```
=========================================
Starting Laravel application...
=========================================
Creating directories...
Setting permissions...
Clearing Laravel caches...
Caching Laravel configurations...
Testing PHP-FPM configuration...
[30-Oct-2025 12:00:00] NOTICE: configuration file test is successful
Testing Nginx configuration...
nginx: configuration file /etc/nginx/nginx.conf test is successful
=========================================
All tests passed! Starting services...
PHP-FPM will listen on 127.0.0.1:9000
Nginx will listen on 0.0.0.0:10000
=========================================
```

### ✅ Health check:

```bash
$ curl https://your-app.onrender.com/health
healthy
```

### ✅ Homepage:

```bash
$ curl -I https://your-app.onrender.com
HTTP/2 200
content-type: text/html; charset=UTF-8
```

## 🎉 Tóm tắt

| Trước | Sau |
|-------|-----|
| ❌ 502 Bad Gateway | ✅ 200 OK |
| ❌ PHP-FPM không có config | ✅ Có `www.conf` đầy đủ |
| ❌ Nginx không kết nối được PHP | ✅ FastCGI hoạt động |
| ❌ Không có timeout settings | ✅ Timeout 300s |
| ❌ Không có buffer settings | ✅ Buffer 32k |
| ❌ Supervisor command sai | ✅ Dùng đúng `/usr/local/sbin/php-fpm` |
| ❌ Không test config trước start | ✅ Test `php-fpm -t` và `nginx -t` |

---

## 📚 Tài liệu thêm

- **DEPLOYMENT.md**: Hướng dẫn deploy đầy đủ
- **DEPLOY-QUICK-START.md**: Hướng dẫn nhanh
- **TROUBLESHOOTING.md**: Debug chi tiết nếu vẫn gặp lỗi

**Nếu vẫn gặp lỗi 502, xem file `TROUBLESHOOTING.md` để debug! 🔧**

