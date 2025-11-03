<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CacheController extends Controller
{
    /**
     * Clear all application cache
     */
    public function clearAll()
    {
        try {
            Cache::flush();
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa toàn bộ cache thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear specific cache keys
     */
    public function clearSpecific(Request $request)
    {
        $type = $request->input('type');
        $clearedKeys = [];

        try {
            switch ($type) {
                case 'home':
                    Cache::forget('home_categories_page_1');
                    $clearedKeys[] = 'home_categories_page_1';
                    break;

                case 'latest':
                    // Clear all latest pages (1-100)
                    for ($i = 1; $i <= 100; $i++) {
                        Cache::forget("latest_page_{$i}");
                    }
                    $clearedKeys[] = 'latest_page_*';
                    break;

                case 'sidebar':
                    Cache::forget('sidebar_popular');
                    $clearedKeys[] = 'sidebar_popular';
                    break;

                case 'search':
                    // Clear all search cache (pattern-based)
                    Cache::forget('search_categories');
                    $clearedKeys[] = 'search_*';
                    break;

                case 'phim':
                    // Clear specific phim by slug
                    $slug = $request->input('slug');
                    if ($slug) {
                        Cache::forget("phim_detail_{$slug}");
                        $clearedKeys[] = "phim_detail_{$slug}";
                    }
                    break;

                case 'episode':
                    // Clear specific episode by slug
                    $slug = $request->input('slug');
                    if ($slug) {
                        Cache::forget("episode_detail_{$slug}");
                        $clearedKeys[] = "episode_detail_{$slug}";
                    }
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Loại cache không hợp lệ'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đã xóa cache thành công',
                'cleared' => $clearedKeys
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Clear cache when new movie is added/updated (webhook from backend)
     */
    public function webhookClearCache(Request $request)
    {
        // Validate secret key
        $secretKey = $request->header('X-Secret-Key');
        if ($secretKey !== config('api.webhook_secret')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $action = $request->input('action'); // 'new_movie', 'update_movie', 'new_episode'
        $data = $request->input('data', []);

        try {
            switch ($action) {
                case 'new_movie':
                case 'update_movie':
                    // Clear home, latest, sidebar
                    Cache::forget('home_categories_page_1');
                    Cache::forget('sidebar_popular');
                    
                    // Clear all latest pages
                    for ($i = 1; $i <= 100; $i++) {
                        Cache::forget("latest_page_{$i}");
                    }

                    // Clear specific phim if slug provided
                    if (isset($data['slug'])) {
                        Cache::forget("phim_detail_{$data['slug']}");
                    }
                    break;

                case 'new_episode':
                    // Clear phim detail và trang latest
                    if (isset($data['category_slug'])) {
                        Cache::forget("phim_detail_{$data['category_slug']}");
                    }
                    
                    Cache::forget('home_categories_page_1');
                    for ($i = 1; $i <= 10; $i++) {
                        Cache::forget("latest_page_{$i}");
                    }
                    break;

                case 'clear_all':
                    Cache::flush();
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => "Cache cleared for action: {$action}"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get cache statistics
     */
    public function stats()
    {
        // Note: This requires specific cache driver support
        return response()->json([
            'success' => true,
            'message' => 'Cache statistics',
            'data' => [
                'driver' => config('cache.default'),
                'note' => 'Để xem thống kê chi tiết, cần cấu hình cache driver hỗ trợ (Redis/Memcached)'
            ]
        ]);
    }
}

