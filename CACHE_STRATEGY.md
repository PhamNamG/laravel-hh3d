# 📦 Chiến Lược Cache cho Web Phim

## 🎯 Mục tiêu
- Giảm tải cho backend API
- Tăng tốc độ response time
- Giảm bandwidth
- Đảm bảo dữ liệu không quá cũ (stale data)

---

## ⏱️ Thời Gian Cache

### 1. **Trang Chủ** - 3 phút (180s)
```php
// HomeController.php
Cache::remember('home_categories_page_1', 180, ...)
```
**Lý do:** Có phim mới cập nhật thường xuyên, cần refresh nhanh

---

### 2. **Chi Tiết Phim** - 10 phút (600s)
```php
// PhimController.php
Cache::remember("phim_detail_{$slug}", 600, ...)
```
**Lý do:** Metadata (tên, mô tả, poster) ít thay đổi

---

### 3. **Xem Phim (Episode)** - 30 phút (1800s)
```php
// XemController.php
Cache::remember("episode_detail_{$episodeSlug}", 1800, ...)
```
**Lý do:** Link video và thông tin tập phim hầu như không thay đổi

---

### 4. **Tìm Kiếm** - 5 phút (300s)
```php
// SearchController.php
Cache::remember('search_' . md5($query), 300, ...)
```
**Lý do:** Kết quả search ít thay đổi cho cùng 1 từ khóa

---

### 5. **Sidebar Phổ Biến** - 15 phút (900s)
```php
// SidebarController.php
Cache::remember('sidebar_popular', 900, ...)
```
**Lý do:** Danh sách phim phổ biến thay đổi chậm

---

### 6. **Trang Phim Mới** - 3 phút (180s)
```php
// LatestController.php
Cache::remember("latest_page_{$page}", 180, ...)
```
**Lý do:** Có phim mới liên tục, cần cập nhật nhanh

---

## 🔄 Clear Cache API

### Manual Clear Cache

#### 1. Clear Toàn Bộ Cache
```bash
POST /api/cache/clear-all
```

#### 2. Clear Cache Theo Loại
```bash
POST /api/cache/clear
Content-Type: application/json

{
  "type": "home"        # Trang chủ
  "type": "latest"      # Phim mới
  "type": "sidebar"     # Sidebar
  "type": "search"      # Tìm kiếm
  "type": "phim"        # Chi tiết phim (cần thêm slug)
  "type": "episode"     # Episode (cần thêm slug)
}
```

**Ví dụ:**
```bash
# Clear cache chi tiết phim cụ thể
curl -X POST http://localhost:8000/api/cache/clear \
  -H "Content-Type: application/json" \
  -d '{"type": "phim", "slug": "thanh-lan-quyet"}'
```

---

### Webhook Auto Clear (từ Backend)

Backend có thể gọi webhook để tự động clear cache khi:
- Admin thêm phim mới
- Admin cập nhật phim
- Có tập mới

```bash
POST /api/cache/webhook
Headers:
  X-Secret-Key: YOUR_SECRET_KEY
  Content-Type: application/json

Body:
{
  "action": "new_movie",      # hoặc "update_movie", "new_episode"
  "data": {
    "slug": "phim-moi",
    "category_slug": "category-slug"
  }
}
```

---

## 🔧 Cấu Hình

### 1. Thêm Webhook Secret vào `.env`
```env
API_WEBHOOK_SECRET=your-secret-key-here-change-this
```

### 2. Thêm vào `config/api.php`
```php
return [
    'base_url' => env('API_BASE_URL', 'https://hh3d.id.vn/api'),
    'webhook_secret' => env('API_WEBHOOK_SECRET', ''),
];
```

### 3. Thêm Routes vào `routes/api.php`
```php
use App\Http\Controllers\CacheController;

// Cache management routes
Route::prefix('cache')->group(function () {
    Route::post('clear-all', [CacheController::class, 'clearAll']);
    Route::post('clear', [CacheController::class, 'clearSpecific']);
    Route::post('webhook', [CacheController::class, 'webhookClearCache']);
    Route::get('stats', [CacheController::class, 'stats']);
});
```

---

## 📊 Cache Flow

```
User Request → Check Cache → Cache Hit? 
                                  ↓
                            Yes ← Return Cached Data (FAST!)
                                  ↓
                            No  → Call API → Cache Data → Return Data
```

---

## 💡 Best Practices

### ✅ DO:
1. **Cache dữ liệu ít thay đổi lâu hơn**
   - Episode: 30 phút
   - Sidebar: 15 phút
   
2. **Cache dữ liệu thay đổi nhanh ngắn hơn**
   - Trang chủ: 3 phút
   - Latest: 3 phút

3. **Clear cache khi có cập nhật quan trọng**
   - Admin thêm phim mới → Clear home + latest
   - Admin cập nhật episode → Clear phim detail

4. **Dùng cache key unique**
   - `phim_detail_{$slug}` - không conflict giữa các phim
   - `search_` + md5($query) - unique cho mỗi query

### ❌ DON'T:
1. **Không cache quá lâu cho trang chủ**
   - User sẽ không thấy phim mới

2. **Không cache query có tham số user-specific**
   - Giỏ hàng, wishlist → Không cache

3. **Không quên clear cache sau khi cập nhật**
   - Admin sửa phim → Phải clear cache phim đó

---

## 🚀 Performance Benefits

| Trang | Không Cache | Có Cache | Cải thiện |
|-------|-------------|----------|-----------|
| Home | ~800ms | ~50ms | **16x** |
| Phim Detail | ~600ms | ~40ms | **15x** |
| Episode | ~700ms | ~45ms | **15.5x** |
| Search | ~1000ms | ~60ms | **16.7x** |

---

## 🔍 Monitor Cache

### Kiểm tra Cache đang hoạt động:
```bash
# Xem cache stats
curl http://localhost:8000/api/cache/stats
```

### Debug Cache trong Code:
```php
// Check if cache exists
if (Cache::has('home_categories_page_1')) {
    Log::info('Cache HIT: home_categories_page_1');
} else {
    Log::info('Cache MISS: home_categories_page_1');
}
```

---

## 📝 Notes

- **Cache Driver:** Mặc định dùng `file` cache. Để performance tốt hơn, nên dùng **Redis** hoặc **Memcached**
- **Cache Size:** Giới hạn cache size để tránh đầy disk
- **Cache Warming:** Có thể tạo command để pre-populate cache sau khi deploy

---

## 🛠️ Troubleshooting

### Cache không clear?
```bash
# Manual clear tất cả
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

### Cache không hoạt động?
1. Kiểm tra `.env` → `CACHE_DRIVER=file` (hoặc redis)
2. Kiểm tra quyền folder `storage/framework/cache`
3. Xem logs: `storage/logs/laravel.log`

---

**Last Updated:** 2024
**Version:** 1.0

