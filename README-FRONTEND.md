# HH3D - Laravel Frontend

Giao diện web xem phim hoạt hình 3D Trung Quốc được xây dựng bằng Laravel Blade Templates.

## 🚀 Cài Đặt

### Yêu Cầu
- PHP >= 8.1
- Composer
- Laravel 10

### Các Bước

1. **Cài đặt dependencies:**
```bash
composer install
```

2. **Cấu hình môi trường:**
```bash
cp .env.example .env
php artisan key:generate
```

3. **Chạy server:**
```bash
php artisan serve
```

Truy cập: http://127.0.0.1:8000

## 📁 Cấu Trúc Thư Mục

```
example-app/
├── app/
│   └── Http/
│       └── Controllers/
│           └── HomeController.php          # Controller gọi API categories
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php              # Layout master
│       ├── components/
│       │   ├── header.blade.php           # Header component
│       │   ├── footer.blade.php           # Footer component
│       │   └── movie-card.blade.php       # Movie card component
│       └── home.blade.php                 # Trang chủ
├── public/
│   ├── css/
│   │   └── app.css                        # Dark theme CSS
│   └── js/
│       └── app.js                         # Main JavaScript
└── routes/
    └── web.php                            # Web routes
```

## 🎨 Components

### 1. Layout Master (`layouts/app.blade.php`)
Layout chính cho toàn bộ website với:
- Meta tags
- CSS & JS imports
- Header & Footer includes
- Content yield section

### 2. Header Component (`components/header.blade.php`)
- Logo
- Search bar
- Navigation menu (Trang Chủ, Thể Loại, Lịch Chiếu, etc.)
- Auth buttons

### 3. Footer Component (`components/footer.blade.php`)
- Thông tin website
- Links
- Social media icons

### 4. Movie Card Component (`components/movie-card.blade.php`)
Props: `$category`
- Poster image
- Episode badge
- Status badge (Hoàn Thành/Đang Phát)
- New badge
- Movie info (title, subtitle, meta)
- Rating display

## 🔌 API Integration

### Backend API
Base URL: `http://hh3d.id.vn/api`

### HomeController
```php
// Lấy danh sách categories mới cập nhật
GET /api/categorys?page=1&limit=12

// Response structure
{
  "data": [
    {
      "name": "Thần Ấn Vương Tọa",
      "slug": "than-an-vuong-toa",
      "linkImg": "https://...",
      "products": [...],
      "sumSeri": 228,
      "quality": "4K",
      "status": "pending",
      "year": 2024,
      "lang": "Vietsub",
      "rating": [5, 4, 5],
      ...
    }
  ],
  "pagination": {
    "page": 1,
    "limit": 12,
    "total": 100
  }
}
```

## 🎨 Styling

### Dark Theme Color Palette
```css
--bg-primary: #0a0a0a        /* Background chính */
--bg-secondary: #1a1a1a      /* Background phụ */
--bg-card: #1e1e1e           /* Card background */
--text-primary: #ffffff      /* Text chính */
--text-secondary: #b3b3b3    /* Text phụ */
--accent-primary: #3b82f6    /* Màu accent (xanh) */
```

### Features
- ✨ Smooth hover effects
- 🎯 Sticky header với backdrop blur
- 📱 Responsive design (Desktop, Tablet, Mobile)
- 🖼️ Lazy loading images
- 🎬 Play overlay on hover
- 🏆 Rank badges cho top 3
- 🔢 Episode counters
- ⭐ Rating display

## 📱 Responsive Breakpoints

- **Desktop:** >= 1024px
- **Tablet:** 768px - 1023px
- **Mobile:** <= 767px

## 🛠️ Customization

### Thêm Section Mới
1. Tạo component trong `resources/views/components/`
2. Include vào `home.blade.php`:
```blade
@include('components.your-component')
```

### Sửa Đổi Colors
Edit `public/css/app.css`:
```css
:root {
  --accent-primary: #your-color;
}
```

### Thêm Routes
Edit `routes/web.php`:
```php
Route::get('/your-route', [YourController::class, 'index']);
```

## 🔥 Features Chính

### Trang Chủ
- ✅ Section "Mới Cập Nhật" với grid layout
- ✅ Sidebar "Xem Nhiều" với ranked list
- ✅ Load more functionality
- ✅ Empty state handling
- ✅ Error handling

### Movie Cards
- ✅ Episode badge (Tập X/Y [4K])
- ✅ Status badge (Hoàn Thành/Đang Phát)
- ✅ New badge animation
- ✅ Play overlay on hover
- ✅ Rating stars display
- ✅ Language & quality tags

### Header
- ✅ Sticky navigation
- ✅ Search functionality
- ✅ Responsive menu
- ✅ Active state indication

### Footer
- ✅ Multi-column layout
- ✅ Social media links
- ✅ Disclaimer text

## 🚧 TODO - Features Tương Lai

- [ ] Trang chi tiết phim/series
- [ ] Video player integration
- [ ] User authentication
- [ ] Comment system
- [ ] Watchlist/Favorites
- [ ] Advanced search filters
- [ ] Pagination
- [ ] Loading skeletons
- [ ] Toast notifications
- [ ] Dark/Light theme toggle

## 🐛 Debug

### API Không Kết Nối
1. Kiểm tra API URL trong `HomeController.php`
2. Test API trực tiếp: `curl http://hh3d.id.vn/api/categorys?page=1`
3. Kiểm tra CORS settings trên backend

### Ảnh Không Hiển Thị
1. Kiểm tra URL của `linkImg` trong response
2. Fallback placeholder sẽ tự động hiển thị

### CSS Không Load
1. Clear cache: `php artisan cache:clear`
2. Kiểm tra đường dẫn trong `app.blade.php`

## 📞 Support

API Backend: http://hh3d.id.vn/api

---

**Built with ❤️ using Laravel Blade Templates**

