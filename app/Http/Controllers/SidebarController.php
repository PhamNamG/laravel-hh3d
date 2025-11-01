<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SidebarController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    /**
     * Get popular movies for sidebar
     * Data từ: https://hh3d.id.vn/api/category/filters?width=300&height=400
     */
    public function getPopularMovies()
    {
        try {
            // Cache trong 5 phút để tránh call API quá nhiều
            $popularCategories = Cache::remember('sidebar_popular', 300, function () {
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'max-age=2592000'])
                    ->get("{$this->apiBaseUrl}/category/filters", [
                        'width' => 300,
                        'height' => 400,
                        '_t' => time(),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }

                return [];
            });

            return response()->json([
                'success' => true,
                'data' => $popularCategories
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'data' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get sidebar data as array (for internal use in other controllers)
     */
    public static function getSidebarData()
    {
        try {
            $apiBaseUrl = config('api.base_url');

            // Cache trong 5 phút
            return Cache::remember('sidebar_popular', 300, function () use ($apiBaseUrl) {
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'max-age=2592000'])
                    ->get("{$apiBaseUrl}/category/filters", [
                        'width' => 300,
                        'height' => 400,
                        '_t' => time(),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }

                return [];
            });
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Clear sidebar cache
     */
    public function clearCache()
    {
        Cache::forget('sidebar_popular');

        return response()->json([
            'success' => true,
            'message' => 'Sidebar cache cleared successfully'
        ]);
    }
}
