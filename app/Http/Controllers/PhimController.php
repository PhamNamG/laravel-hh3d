<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class PhimController extends Controller
{
    private $apiBaseUrl = 'http://hh3d.id.vn/api';

    public function show($slug)
    {
        try {
            // Lấy chi tiết category/phim
            $response = Http::get("{$this->apiBaseUrl}/category/{$slug}");
            
            if (!$response->successful()) {
                return redirect('/')->with('error', 'Không tìm thấy phim');
            }

            $phim = $response->json();

            // Lấy danh sách xem nhiều cho sidebar
            $popularResponse = Http::get("{$this->apiBaseUrl}/category/filters", [
                'width' => 300,
                'height' => 400,
            ]);

            $popularCategories = $popularResponse->successful() ? $popularResponse->json() : [];

            // Sort episodes theo số tập giảm dần
            $episodes = $phim['products'] ?? [];
            usort($episodes, function($a, $b) {
                return ($b['seri'] ?? 0) - ($a['seri'] ?? 0);
            });

            return view('phim', [
                'phim' => $phim,
                'episodes' => $episodes,
                'popularCategories' => $popularCategories['data'] ?? [],
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Không thể tải thông tin phim');
        }
    }
}

