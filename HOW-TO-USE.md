# 🎬 HH3D - Hướng Dẫn Sử Dụng

## 🚀 Khởi Động Nhanh

### Bước 1: Chạy Server
```bash
cd E:\laravel-hh3d\example-app
php artisan serve
```

### Bước 2: Mở Trình Duyệt
Truy cập: **http://127.0.0.1:8000**

### Bước 3: Thưởng Thức! 🎉
Website sẽ tự động gọi API và hiển thị danh sách phim.

---

## 📱 Giao Diện

### Trang Chủ Bao Gồm:

#### 1. **Header** (Sticky)
- Logo HH3D (góc trái)
- Thanh tìm kiếm (giữa)
- 3 nút: Refresh, User, Login (góc phải)
- Menu điều hướng: Trang Chủ | Thể Loại | Lịch Chiếu | Mới Cập Nhật | Xem Nhiều | Hoàn Thành

#### 2. **Main Content** (Bên Trái)
- Tiêu đề: **MỚI CẬP NHẬT**
- Grid layout: 12 phim (4 columns trên desktop)
- Mỗi card hiển thị:
  - Poster phim
  - Tập hiện tại/tổng số tập + chất lượng (4K/FHD/HD)
  - Status: Hoàn Thành (xanh) / Đang Phát (cam)
  - Badge "NEW" nếu là phim mới
  - Tên phim + tên gốc
  - Năm, ngôn ngữ, rating
  - Icon play khi hover

#### 3. **Sidebar** (Bên Phải)
- Tiêu đề: **XEM NHIỀU**
- Danh sách 8 phim ranked:
  - Top 1: Badge vàng
  - Top 2: Badge bạc
  - Top 3: Badge đồng
  - Top 4-8: Badge đen
- Hiển thị: Poster nhỏ, tên, tập, chất lượng

#### 4. **Footer**
- 4 cột thông tin
- Social media links
- Copyright & disclaimer

---

## 🎨 Tính Năng Giao Diện

### Dark Theme
- Background đen thuần (#0a0a0a)
- Card xám đậm (#1e1e1e)
- Accent xanh dương (#3b82f6)

### Hover Effects
- Card nhấc lên + shadow
- Poster zoom nhẹ
- Icon play hiện ra
- Buttons đổi màu

### Responsive
- **Desktop (>= 1024px):** Grid 4-6 cột + sidebar
- **Tablet (768-1023px):** Grid 3-4 cột, sidebar xuống dưới
- **Mobile (<= 767px):** Grid 2 cột, menu scroll ngang

### Animation
- Badge "NEW" pulse liên tục
- Smooth transitions trên mọi elements
- Lazy loading images

---

## 🔧 Cấu Trúc Code

### Blade Components

#### 1. Sử Dụng Layout
```blade
@extends('layouts.app')

@section('title', 'Trang Chủ')

@section('content')
    <!-- Your content here -->
@endsection
```

#### 2. Include Header/Footer
```blade
@include('components.header')
@include('components.footer')
```

#### 3. Sử Dụng Movie Card
```blade
<x-movie-card :category="$category" />
```

### Helper Functions

```php
// Format episode count
{{ formatEpisodeCount(50, 100) }}  
// Output: Tập 50/100

// Format rating
{{ formatRating([5, 4, 5, 4]) }}   
// Output: 4.5

// Format views
{{ formatViews(1500) }}            
// Output: 1.5K

// Truncate text
{{ truncateText($text, 100) }}     
// Output: Text... (nếu > 100 chars)
```

---

## 🔌 API Integration

### Endpoint
```
http://hh3d.id.vn/api/categorys?page=1&limit=12
```

### HomeController Flow
```php
1. Gọi API /categorys (limit 12)
2. Parse response → $categories['data']
3. Gọi API /categorys với sort=view → $popularCategories
4. Return view với data
5. Blade render components
```

### Error Handling
Nếu API failed:
- Show error message
- Display empty state
- Không crash page

---

## 🎯 Customization

### 1. Thay Đổi Màu Sắc
Edit `public/css/app.css`:
```css
:root {
    --accent-primary: #your-color;
    --bg-primary: #your-bg;
}
```

### 2. Thay Đổi Grid Columns
Edit `public/css/app.css`:
```css
.movie-grid {
    grid-template-columns: repeat(auto-fill, minmax(YOUR_SIZE, 1fr));
}
```

### 3. Thay Đổi Số Lượng Phim
Edit `HomeController.php`:
```php
'limit' => 20  // Thay vì 12
```

### 4. Thêm Section Mới
Tạo file `resources/views/components/your-section.blade.php`:
```blade
<section class="section">
    <h2 class="section-title">YOUR TITLE</h2>
    <!-- Your content -->
</section>
```

Include vào `home.blade.php`:
```blade
@include('components.your-section')
```

---

## 🐛 Debug

### API Không Trả Về Dữ Liệu
1. Test API trực tiếp: 
   ```bash
   curl http://hh3d.id.vn/api/categorys?page=1
   ```
2. Kiểm tra log Laravel:
   ```bash
   tail -f storage/logs/laravel.log
   ```
3. Enable debug mode trong `.env`:
   ```
   APP_DEBUG=true
   ```

### Ảnh Không Hiển Thị
- Kiểm tra `linkImg` trong API response
- Fallback tự động: `https://via.placeholder.com/300x400?text=No+Image`

### CSS Không Apply
```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Routes Không Hoạt Động
```bash
php artisan route:clear
php artisan route:cache
```

---

## 📚 File Reference

### Controller
- `app/Http/Controllers/HomeController.php` - Gọi API

### Views
- `resources/views/layouts/app.blade.php` - Layout master
- `resources/views/components/header.blade.php` - Header
- `resources/views/components/footer.blade.php` - Footer
- `resources/views/components/movie-card.blade.php` - Movie card
- `resources/views/home.blade.php` - Trang chủ

### Assets
- `public/css/app.css` - Styles
- `public/js/app.js` - JavaScript

### Config
- `routes/web.php` - Routes
- `app/Helpers/helpers.php` - Helper functions

---

## 🎓 Tips & Tricks

### 1. Sticky Sidebar
Sidebar sẽ tự động sticky khi scroll (CSS: `position: sticky; top: 100px`)

### 2. Lazy Loading
Thêm `loading="lazy"` vào images để tối ưu performance

### 3. Active Nav Item
JavaScript tự động detect current URL và add class `active`

### 4. Smooth Scroll
Links với `href="#..."` sẽ scroll smooth

### 5. Image Error Fallback
JavaScript tự động replace lỗi images với placeholder

---

## 📞 Support

- **API Documentation:** http://hh3d.id.vn/api
- **Frontend Docs:** README-FRONTEND.md
- **Quick Start:** QUICK-START.md
- **Summary:** SUMMARY.md

---

**Happy Coding! 🚀**

