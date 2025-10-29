# 🚀 Quick Start - HH3D Laravel Frontend

## Chạy Nhanh (5 Phút)

### 1. Chạy Server
```bash
cd example-app
php artisan serve
```

### 2. Truy Cập
Mở browser: **http://127.0.0.1:8000**

### 3. Xong! 🎉

## 📸 Preview

### Trang Chủ
- ✅ Header với logo, search, navigation
- ✅ Section "Mới Cập Nhật" - Grid 12 phim
- ✅ Sidebar "Xem Nhiều" - Top 8 phim ranked
- ✅ Footer với social links

### API Integration
- Backend: `http://hh3d.id.vn/api`
- Endpoint: `/api/categorys?page=1&limit=12`
- Auto fetch khi load trang

## 🎨 Features

### Movie Cards
- **Episode Badge:** Tập X/Y [4K/FHD/HD]
- **Status Badge:** Hoàn Thành (xanh lá) / Đang Phát (cam)
- **New Badge:** Phim mới (gradient đỏ-cam, animation pulse)
- **Play Overlay:** Icon play hiện khi hover
- **Rating:** Hiển thị rating trung bình (⭐ 4.5)

### Responsive
- **Desktop:** Grid 4-6 columns
- **Tablet:** Grid 3-4 columns  
- **Mobile:** Grid 2 columns

### Dark Theme
- Background: #0a0a0a (đen thuần)
- Cards: #1e1e1e (xám đậm)
- Accent: #3b82f6 (xanh dương)
- Smooth transitions & hover effects

## 🔧 Cấu Trúc

```
Views:
├── layouts/app.blade.php       → Layout master
├── components/
│   ├── header.blade.php        → Header + Nav
│   ├── footer.blade.php        → Footer
│   └── movie-card.blade.php    → Card component
└── home.blade.php              → Trang chủ

Controllers:
└── HomeController.php          → Gọi API categories

Assets:
├── css/app.css                 → Dark theme styles
└── js/app.js                   → Interactive features
```

## 🐛 Troubleshooting

### Lỗi "Class not found"
```bash
composer dump-autoload
```

### API không kết nối
Kiểm tra API đang chạy: http://hh3d.id.vn/api/categorys

### CSS không load
```bash
php artisan cache:clear
php artisan config:clear
```

## 📝 Notes

- **API Response:** Backend trả về array `data` với list categories
- **Image Fallback:** Tự động hiển thị placeholder nếu ảnh lỗi
- **Error Handling:** Show error message nếu API failed
- **Lazy Loading:** Ảnh chỉ load khi scroll tới

## 🔗 Links

- Backend API: http://hh3d.id.vn
- Full Documentation: README-FRONTEND.md

---

**Enjoy! 🎬**

