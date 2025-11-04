<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NavbarComposer
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    public function compose(View $view): void
    {
        $tags = Cache::remember('tags', 300, function () { // cache 5 phút
            try {
                $response = Http::timeout(10)->get("{$this->apiBaseUrl}/tags/laravel");

                if ($response->successful()) {
                    $json = $response->json();
                    if (isset($json['data']) && is_array($json['data'])) {
                        return $json['data']; // chỉ cache phần data
                    }
                }

                Log::warning('NavbarComposer: API /tags/laravel trả về lỗi hoặc không có data', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            } catch (\Exception $e) {
                Log::error('NavbarComposer: Lỗi gọi API tags', ['message' => $e->getMessage()]);
            }

            return []; // fallback
        });

        $menuItems = [
            [
                'name' => 'Trang Chủ',
                'url' => '/',
                'pattern' => '/',
                'slug' => 'trang-chu',  
            ],
            [
                'name' => 'Thể Loại',
                'url' => '#',
                'pattern' => 'the-loai*',
                'dropdown' => true,
                'children' => $tags ? $tags : [],
                'slug' => 'the-loai',
            ],
            [
                'name' => 'Lịch Chiếu',
                'url' => '/lich-chieu',
                'pattern' => 'lich-chieu*',
                'slug' => 'lich-chieu',
            ],
            [
                'name' => 'Mới Cập Nhật',
                'url' => '/moi-cap-nhat',
                'pattern' => 'moi-cap-nhat*',
                'slug' => 'moi-cap-nhat',
            ],
            [
                'name' => 'Xem Nhiều',
                'url' => '/xem-nhieu',
                'pattern' => 'xem-nhieu*',
                'slug' => 'xem-nhieu',
            ],
            [
                'name' => 'Hoàn Thành',
                'url' => '/hoan-thanh',
                'pattern' => 'hoan-thanh*',
                'slug' => 'hoan-thanh',
            ],
        ];

        $view->with('menuItems', $menuItems);
    }
}
