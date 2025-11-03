<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class PhimController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function show($slug)
    {
        try {
            // Cache dữ liệu trong 5 phút (300 giây)
            $cacheKey = "phim_detail_{$slug}";
            
            $phim = Cache::remember($cacheKey, 300, function () use ($slug) {
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'no-cache'])
                    ->get("{$this->apiBaseUrl}/category/{$slug}", [
                        '_t' => time(), // Cache buster
                    ]);
                
                if (!$response->successful()) {
                    return null;
                }

                return $response->json();
            });
            
            if (!$phim) {
                // Hiển thị trang 404 thân thiện
                return view('phim', [
                    'notFound' => true,
                    'phim' => [],
                    'episodes' => [],
                ]);
            }

            // Sort episodes theo số tập giảm dần
            $episodes = $phim['products'] ?? [];
            usort($episodes, function($a, $b) {
                return ($b['seri'] ?? 0) - ($a['seri'] ?? 0);
            });

            return view('phim', [
                'phim' => $phim,
                'episodes' => $episodes,
                'notFound' => false,
            ]);
        } catch (\Exception $e) {
            // Hiển thị trang 404 thân thiện
            return view('phim', [
                'notFound' => true,
                'phim' => [],
                'episodes' => [],
            ]);
        }
    }
}

