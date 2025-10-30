# Hướng dẫn Deploy Laravel lên Render

## 📋 Yêu cầu trước khi deploy

1. Tài khoản Render (https://render.com)
2. Repository GitHub/GitLab chứa source code
3. File `.env` với các biến môi trường cần thiết

## 🚀 Các bước Deploy

### 1. Chuẩn bị Source Code

Đảm bảo các file sau đã được commit:
- ✅ `Dockerfile`
- ✅ `.dockerignore`
- ✅ `docker/nginx.conf`
- ✅ `docker/default.conf`
- ✅ `docker/supervisord.conf`
- ✅ `docker/start.sh`
- ✅ `render.yaml` (optional - auto configuration)

### 2. Tạo Database trên Render (nếu cần)

1. Truy cập Render Dashboard
2. Click **"New +"** → **"PostgreSQL"** hoặc **"MySQL"**
3. Đặt tên database: `laravel-db`
4. Chọn plan: **Free**
5. Click **"Create Database"**
6. Lưu lại thông tin kết nối (Internal Database URL)

### 3. Tạo Web Service

#### Option 1: Sử dụng Blueprint (render.yaml)

1. Push `render.yaml` lên repository
2. Render sẽ tự động detect và tạo service theo config

#### Option 2: Manual Setup

1. Truy cập Render Dashboard
2. Click **"New +"** → **"Web Service"**
3. Connect repository GitHub/GitLab
4. Chọn repository chứa Laravel app

**Cấu hình Service:**
- **Name**: `laravel-app`
- **Environment**: `Docker`
- **Region**: `Singapore` (hoặc gần bạn nhất)
- **Branch**: `main` hoặc `master`
- **Plan**: `Free` (hoặc plan khác)

### 4. Cấu hình Environment Variables

Thêm các biến môi trường sau trong Render:

#### Bắt buộc:
```env
APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_URL=https://your-app.onrender.com

# Database (nếu dùng Render Database, lấy từ Internal Database URL)
DB_CONNECTION=mysql
DB_HOST=<your-db-host>
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=<your-db-username>
DB_PASSWORD=<your-db-password>

# Cache & Session
CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync

LOG_CHANNEL=stack
LOG_LEVEL=error
```

#### Generate APP_KEY:

Nếu chưa có `APP_KEY`, chạy lệnh sau trên máy local:
```bash
php artisan key:generate --show
```

Copy giá trị hiển thị (bao gồm cả `base64:`) và paste vào Render.

### 5. Deploy

1. Click **"Create Web Service"**
2. Render sẽ bắt đầu build Docker image
3. Quá trình build mất khoảng 3-5 phút
4. Sau khi build xong, app sẽ tự động deploy

### 6. Chạy Migrations (nếu cần)

Sau khi deploy thành công, vào **Shell** trong Render Dashboard:

```bash
php artisan migrate --force
```

### 7. Kiểm tra Health Check

Truy cập: `https://your-app.onrender.com/health`

Nếu thấy `healthy`, deployment thành công! ✅

## 🧪 Test Local trước khi Deploy

```bash
# Build Docker image
docker build -t laravel-app .

# Run với docker-compose
docker-compose up -d

# Kiểm tra logs
docker-compose logs -f app

# Test app
curl http://localhost:10000/health

# Stop
docker-compose down
```

## 🔧 Troubleshooting

### ❌ Lỗi: "Permission denied" cho storage

**Giải pháp**: File `start.sh` đã tự động fix permissions. Nếu vẫn lỗi, kiểm tra logs:
```bash
docker logs <container-id>
```

### ❌ Lỗi: "Database connection refused"

**Giải pháp**: 
1. Kiểm tra `DB_HOST` phải là **Internal Database URL** từ Render
2. Đảm bảo Database service đang chạy
3. Kiểm tra `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD` đúng

### ❌ Lỗi: "Route not found"

**Giải pháp**:
```bash
php artisan route:clear
php artisan config:clear
php artisan cache:clear
```

### ❌ Lỗi: Build timeout

**Giải pháp**: 
- Sử dụng `.dockerignore` để giảm kích thước context
- Tối ưu `composer install` với `--no-dev --optimize-autoloader`

## 📊 Monitoring

- **Logs**: Render Dashboard → Service → Logs
- **Metrics**: Render Dashboard → Service → Metrics
- **Health Check**: Tự động check endpoint `/health` mỗi 30s

## 🔄 Auto Deploy

Render tự động deploy khi có commit mới push lên branch `main`/`master`.

Để tắt auto-deploy:
- Render Dashboard → Service → Settings → Build & Deploy → Disable

## 💡 Tips

1. **Free Plan Limitations**:
   - Service sẽ sleep sau 15 phút không hoạt động
   - Cold start mất ~30s khi service wake up
   - 750 giờ/tháng miễn phí

2. **Tối ưu Performance**:
   - Sử dụng Redis cho cache (nếu có plan Paid)
   - Enable OPcache trong PHP
   - Sử dụng CDN cho static assets

3. **Security**:
   - Luôn set `APP_DEBUG=false` trong production
   - Sử dụng HTTPS (Render cung cấp SSL miễn phí)
   - Bảo mật `.env` và sensitive data

## 📚 Tài liệu tham khảo

- [Render Docker Deployment](https://render.com/docs/docker)
- [Laravel Deployment](https://laravel.com/docs/10.x/deployment)
- [Render Environment Groups](https://render.com/docs/environment-variables)

---

**Chúc bạn deploy thành công! 🎉**

