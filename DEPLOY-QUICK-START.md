# 🚀 Quick Start - Deploy lên Render

## Bước 1: Test Local
```bash
# Build và chạy
docker-compose up -d

# Kiểm tra
curl http://localhost:10000/health
# Kết quả: healthy ✅
```

## Bước 2: Push lên Git
```bash
git add .
git commit -m "Add Docker configuration for Render"
git push origin main
```

## Bước 3: Deploy trên Render

### A. Tạo Database (nếu cần)
1. Render Dashboard → New + → PostgreSQL/MySQL
2. Name: `laravel-db`
3. Plan: Free
4. Create Database
5. Copy **Internal Database URL**

### B. Tạo Web Service
1. Render Dashboard → New + → Web Service
2. Connect repository
3. Cấu hình:
   - **Name**: `laravel-app`
   - **Environment**: Docker ⚠️ **QUAN TRỌNG**
   - **Region**: Singapore
   - **Plan**: Free

### C. Environment Variables

Thêm vào Render (Environment tab):

```env
APP_NAME=Laravel
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:GENERATE_THIS_WITH_artisan_key_generate
APP_URL=https://your-app.onrender.com

DB_CONNECTION=mysql
DB_HOST=<from-render-db>
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=<from-render-db>
DB_PASSWORD=<from-render-db>

CACHE_DRIVER=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stack
```

**Generate APP_KEY**:
```bash
php artisan key:generate --show
```

### D. Deploy
1. Click **Create Web Service**
2. Đợi 3-5 phút build
3. Truy cập: `https://your-app.onrender.com/health`

## Bước 4: Migration (nếu có)
```bash
# Vào Shell trong Render Dashboard
php artisan migrate --force
```

---

## ⚠️ Lưu ý quan trọng

1. **Environment phải chọn Docker**, không phải Native
2. **Port 10000** đã được cấu hình sẵn
3. **Health check** tại `/health`
4. **Free plan** sẽ sleep sau 15 phút không dùng

## 🐛 Lỗi thường gặp

| Lỗi | Giải pháp |
|------|-----------|
| Permission denied | Đã tự động fix trong `start.sh` |
| Database refused | Dùng Internal URL, không dùng External |
| 404 Not Found | Clear cache: `php artisan config:clear` |
| Build timeout | Đã optimize trong Dockerfile |

---

📖 **Chi tiết đầy đủ**: Xem file `DEPLOYMENT.md`

