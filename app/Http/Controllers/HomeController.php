<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }
 
    public function index()
    {
        try {
            // Cache trang chủ 3 phút (có phim mới liên tục)
            $cacheKey = 'home_categories_page_1';
            
            $categories = Cache::remember($cacheKey, 180, function () {
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'no-cache'])
                    ->get("{$this->apiBaseUrl}/category/latest/next", [
                        'page' => 1,
                        '_t' => time(), // Cache buster
                    ]);

                return $response->successful() ? $response->json() : [];
            });

            return view('home', [
                'categories' => $categories['data'] ?? [],
                'pagination' => $categories['pagination'] ?? null
            ]);
        } catch (\Exception $e) {
            return view('home', [
                'categories' => [],
                'error' => 'Không thể kết nối đến server'
            ]);
        }
    }
}

