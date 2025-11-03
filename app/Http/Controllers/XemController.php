<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class XemController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function show($episodeSlug)
    {
        try {
            // Cache dữ liệu trong 5 phút (300 giây)
            $cacheKey = "episode_detail_{$episodeSlug}";
            
            $episode = Cache::remember($cacheKey, 300, function () use ($episodeSlug) {
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'no-cache'])
                    ->get("{$this->apiBaseUrl}/product/{$episodeSlug}", [
                        '_t' => time(), // Cache buster
                    ]);
                
                if (!$response->successful()) {
                    return null;
                }

                return $response->json();
            });
            
            if (!$episode) {
                return view('xem', [
                    'notFound' => true,
                    'episode' => [],
                    'category' => [],
                    'allEpisodes' => [],
                    'prevEpisode' => null,
                    'nextEpisode' => null,
                ]);
            }

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
                'notFound' => false,
                'episode' => $episode,
                'category' => $category,
                'allEpisodes' => $allEpisodes,
                'prevEpisode' => $prevEpisode,
                'nextEpisode' => $nextEpisode,
            ]);
        } catch (\Exception $e) {
            return view('xem', [
                'notFound' => true,
                'episode' => [],
                'category' => [],
                'allEpisodes' => [],
                'prevEpisode' => null,
                'nextEpisode' => null,
            ]);
        }
    }
}

