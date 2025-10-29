<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    private $apiBaseUrl = 'http://hh3d.id.vn/api';

    public function index()
    {
        try {
            // Lấy danh sách categories mới cập nhật
            $response = Http::get("{$this->apiBaseUrl}/categorys", [
                'page' => 1,
            ]);

            $categories = $response->successful() ? $response->json() : [];

            // Lấy danh sách xem nhiều (có thể từ endpoint khác)
            $popularResponse = Http::get("{$this->apiBaseUrl}/category/filters", [
                'width' => 300,
                'height' => 400,
            ]);

            $popularCategories = $popularResponse->successful() ? $popularResponse->json() : [];

            return view('home', [
                'categories' => $categories['data'] ?? [],
                'popularCategories' => $popularCategories['data'] ?? [],
                'pagination' => $categories['pagination'] ?? null
            ]);
        } catch (\Exception $e) {
            return view('home', [
                'categories' => [],
                'popularCategories' => [],
                'error' => 'Không thể kết nối đến server'
            ]);
        }
    }
}

