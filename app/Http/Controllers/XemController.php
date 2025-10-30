<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class XemController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

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
            ]);
        } catch (\Exception $e) {
            return redirect('/')->with('error', 'Không thể tải tập phim');
        }
    }
}

