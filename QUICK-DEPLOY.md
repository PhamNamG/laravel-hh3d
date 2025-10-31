# ⚡ Quick Deploy Guide

## 🎯 Deploy Nhanh với Script Tự Động

### Cách 1: Sử dụng Install Script (Khuyên dùng)

#### 1. Upload file lên VPS
```bash
# Trên máy local (Git Bash hoặc WSL)
scp -r E:/laravel-hh3d/example-app/* root@your-vps-ip:/tmp/hhkungfu/
```

#### 2. SSH vào VPS
```bash
ssh root@your-vps-ip
```

#### 3. Chạy install script
```bash
cd /tmp/hhkungfu
chmod +x install.sh
./install.sh
```

Script sẽ tự động:
- ✅ Cài đặt PHP 8.2
- ✅ Cài đặt Composer
- ✅ Cài đặt MySQL
- ✅ Cài đặt Nginx
- ✅ Setup SSL (Let's Encrypt)
- ✅ Configure Nginx
- ✅ Setup permissions
- ✅ Optimize Laravel

**Thời gian:** ~10 phút

---

### Cách 2: Deploy Thủ Công

Xem hướng dẫn chi tiết: [DEPLOY-VPS-UBUNTU.md](./DEPLOY-VPS-UBUNTU.md)

---

## 📦 Deploy Code Mới (Updates)

### Option A: Git Pull
```bash
ssh root@your-vps-ip
cd /var/www/hhkungfu
git pull origin main
./deploy.sh
```

### Option B: Upload Files
```bash
# Local
scp -r E:/laravel-hh3d/example-app/* root@your-vps-ip:/var/www/hhkungfu/

# VPS
ssh root@your-vps-ip
cd /var/www/hhkungfu
./deploy.sh
```

---

## 🔧 Deploy Script (`deploy.sh`)

```bash
cd /var/www/hhkungfu
chmod +x deploy.sh
./deploy.sh
```

Script tự động:
1. Enable maintenance mode
2. Pull/update code
3. Install dependencies
4. Clear & rebuild cache
5. Fix permissions
6. Disable maintenance mode

---

## ✅ Checklist Trước Khi Deploy

- [ ] Domain đã trỏ về IP VPS
- [ ] Cập nhật `.env` với thông tin production
- [ ] `APP_DEBUG=false`
- [ ] `APP_ENV=production`
- [ ] Database credentials đúng
- [ ] API_URL đúng
- [ ] Backup database (nếu có)
- [ ] Test local trước

---

## 🐛 Troubleshooting

### 1. Lỗi 500
```bash
tail -50 /var/www/hhkungfu/storage/logs/laravel.log
sudo chmod -R 775 /var/www/hhkungfu/storage
sudo chown -R www-data:www-data /var/www/hhkungfu
```

### 2. Lỗi 502
```bash
sudo systemctl restart php8.2-fpm nginx
```

### 3. Cache không clear
```bash
cd /var/www/hhkungfu
php artisan optimize:clear
sudo systemctl restart nginx php8.2-fpm
```

### 4. CSS/JS không load
```bash
# Check .env
APP_URL=https://your-domain.com  # Phải có https://

# Clear cache
php artisan config:clear
php artisan cache:clear
```

---

## 📞 Quick Commands

```bash
# Restart all
sudo systemctl restart nginx php8.2-fpm

# View logs
tail -f /var/www/hhkungfu/storage/logs/laravel.log

# Clear cache
cd /var/www/hhkungfu && php artisan optimize:clear

# Deploy
cd /var/www/hhkungfu && ./deploy.sh
```

---

## 🎉 Done!

Website online tại: **https://your-domain.com** 🚀




