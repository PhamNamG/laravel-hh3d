<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SitemapController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    /**
     * Generate sitemap XML
     */
    public function index()
    {
        // Cache sitemap for 1 day
        $sitemap = Cache::remember('sitemap_xml', 86400, function () {
            return $this->generateSitemap();
        });

        return response($sitemap)
            ->header('Content-Type', 'application/xml')
            ->header('Cache-Control', 'public, max-age=86400');
    }

    /**
     * Generate sitemap content
     */
    private function generateSitemap()
    {
        $urls = [];

        // Homepage
        $urls[] = [
            'loc' => url('/'),
            'lastmod' => now()->toAtomString(),
            'changefreq' => 'daily',
            'priority' => '1.0'
        ];

        // Static pages
        $staticPages = [
            [
                'loc' => url('/the-loai'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => url('/lich-chieu'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => url('/moi-cap-nhat'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'daily',
                'priority' => '0.9'
            ],
            [
                'loc' => url('/xem-nhieu'),
                'lastmod' => now()->toAtomString(),
                'changefreq' => 'weekly',
                'priority' => '0.8'
            ],
            [
              'loc' => url('/hoan-thanh'),
              'lastmod' => now()->toAtomString(),
              'changefreq' => 'weekly',
              'priority' => '0.8'
          ],
        ];

        $urls = array_merge($urls, $staticPages);

        // Fetch categories from API - Endpoint: /categorys/sitemap
        try {
            $apiUrl = "{$this->apiBaseUrl}/categorys/sitemap";
            
            $response = Http::timeout(10)
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get($apiUrl);


            if ($response->successful()) {
                $data = $response->json();
                $categories = $data['data'] ?? [];
                

                // Dynamic category pages
                foreach ($categories as $category) {
                    // Category detail page
                    $urls[] = [
                        'loc' => url('/phim/' . ($category['slug'] ?? '')),
                        'lastmod' => isset($category['latestProductUploadDate']) 
                            ? date('c', strtotime($category['latestProductUploadDate']))
                            : now()->toAtomString(),
                        'changefreq' => 'daily',
                        'priority' => '0.8'
                    ];

                    // Episodes/Products - Nếu muốn thêm từng product thì bỏ comment đoạn này
                    // if (isset($category['products']) && is_array($category['products'])) {
                    //     foreach ($category['products'] as $product) {
                    //         if (isset($product['slug'])) {
                    //             $urls[] = [
                    //                 'loc' => url('/xem/' . ($category['slug'] ?? '') . '/' . $product['slug']),
                    //                 'lastmod' => isset($product['uploadDate'])
                    //                     ? date('c', strtotime($product['uploadDate']))
                    //                     : now()->toAtomString(),
                    //                 'changefreq' => 'daily',
                    //                 'priority' => '0.8'
                    //             ];
                    //         }
                    //     }
                    // }
                }
                
            }
        } catch (\Exception $e) {
        } 

        return view('sitemap.index', ['urls' => $urls])->render();
    }

    /**
     * Clear sitemap cache
     */
    public function clear()
    {
        Cache::forget('sitemap_xml');
        return response()->json([
            'success' => true,
            'message' => 'Sitemap cache cleared successfully'
        ]);
    }
}

