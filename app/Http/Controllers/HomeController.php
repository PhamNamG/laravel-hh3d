<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
            // Lấy danh sách categories mới cập nhật
            // Thêm timestamp để bypass cache
            $response = Http::timeout(10)
                ->withHeaders(['Cache-Control' => 'no-cache'])
                ->get("{$this->apiBaseUrl}/category/latest/next", [
                    'page' => 1,
                    '_t' => time(), // Cache buster
                ]);

            $categories = $response->successful() ? $response->json() : [];

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

