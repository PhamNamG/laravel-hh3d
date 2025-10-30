<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            // Lấy chi tiết category/phim
            $response = Http::timeout(10)
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get("{$this->apiBaseUrl}/category/{$slug}", [
                    '_t' => time(), // Cache buster
                ]);
            
            
            if (!$response->successful()) {
                // Thay vì redirect, hiển thị thông báo lỗi cụ thể
                return view('phim', [
                    'phim' => [
                        'name' => 'Lỗi',
                        'error' => 'API Error: ' . $response->status() . ' - ' . $response->body()
                    ],
                    'episodes' => [],
                ]);
            }

            $phim = $response->json();

            // Sort episodes theo số tập giảm dần
            $episodes = $phim['products'] ?? [];
            usort($episodes, function($a, $b) {
                return ($b['seri'] ?? 0) - ($a['seri'] ?? 0);
            });

            return view('phim', [
                'phim' => $phim,
                'episodes' => $episodes,
            ]);
        } catch (\Exception $e) {
            return view('phim', [
                'phim' => [
                    'name' => 'Lỗi Exception',
                    'error' => $e->getMessage()
                ],
                'episodes' => [],
            ]);
        }
    }
}

