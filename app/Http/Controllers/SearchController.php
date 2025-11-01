<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    /**
     * Display search results
     */
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $categories = $request->input('categories', []);
        $status = $request->input('status', '');
        
        $results = [];
        $error = null;

        if (!empty($query)) {
            try {
                // Build API URL
                $url = "{$this->apiBaseUrl}/categorys/search?value=" . urlencode($query);
                
                if (!empty($categories) && is_array($categories)) {
                    $url .= '&categories=' . implode(',', $categories);
                }
                
                if (!empty($status)) {
                    $url .= '&status=' . $status;
                }

                // Call API
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'max-age=300'])
                    ->get($url);

                if ($response->successful()) {
                    $data = $response->json();
                    $results = $data ?? [];
                } else {
                    $error = 'Không thể tìm kiếm. Vui lòng thử lại sau.';
                }

            } catch (\Exception $e) {
                $error = 'Lỗi kết nối. Vui lòng thử lại sau.';
            }
        }

        return view('search', [
            'query' => $query,
            'results' => $results,
            'error' => $error,
            'totalResults' => count($results),
            'selectedCategories' => $categories,
            'selectedStatus' => $status,
        ]);
    }

    /**
     * Get available categories for filter
     */
    public function getCategories()
    {
        try {
            $categories = Cache::remember('search_categories', 3600, function () {
                $response = Http::timeout(10)
                    ->get("{$this->apiBaseUrl}/tags");

                if ($response->successful()) {
                    $data = $response->json();
                    return $data['data'] ?? [];
                }
                
                return [];
            });

            return response()->json([
                'success' => true,
                'data' => $categories
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}

