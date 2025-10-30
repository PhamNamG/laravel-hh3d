<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class XemController extends Controller
{
    private $apiBaseUrl = 'http://hh3d.id.vn/api';

    public function show($categorySlug, $episodeSlug)
    {
        try {
            // Lấy thông tin episode
            $response = Http::timeout(10)
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get("{$this->apiBaseUrl}/product/{$episodeSlug}", [
                    '_t' => time(), // Cache buster
                ]);
            
            if (!$response->successful()) {
                return redirect('/')->with('error', 'Không tìm thấy tập phim');
            }

            $episode = $response->json();
            $category = $episode['category'] ?? null;

            // Lấy danh sách xem nhiều cho sidebar
            $popularResponse = Http::timeout(10)
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get("{$this->apiBaseUrl}/category/filters", [
                    'width' => 300,
                    'height' => 400,
                    '_t' => time(), // Cache buster
                ]);

            $popularCategories = $popularResponse->successful() ? $popularResponse->json() : [];

            // Sort episodes theo số tập giảm dần
            $allEpisodes = $category['products'] ?? [];
            usort($allEpisodes, function($a, $b) {
                return ($b['seri'] ?? 0) - ($a['seri'] ?? 0);
            });

            // Tìm episode trước và sau
            $prevEpisode = $episode['prevEpisode'] ?? null;
            $nextEpisode = $episode['nextEpisode'] ?? null;

            return view('xem', [
                'episode' => $episode,
                'category' => $category,
                'allEpisodes' => $allEpisodes,
                'prevEpisode' => $prevEpisode,
                'nextEpisode' => $nextEpisode,
                'popularCategories' => $popularCategories['data'] ?? [],
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Không thể tải tập phim');
        }
    }
}

