<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class LatestController extends Controller
{
  private $apiBaseUrl;

  public function __construct()
  {
    $this->apiBaseUrl = config('api.base_url');
  }

  public function getLatest(Request $request)
  {
    $page = $request->input('page', 1);
    
    try {
      // Cache cả response (bao gồm data + pagination)
      $cacheKey = 'latest_page_' . $page;

      $result = Cache::remember($cacheKey, 300, function () use ($page) {
        $response = Http::timeout(10)
          ->withHeaders(['Cache-Control' => 'max-age=300'])
          ->get("{$this->apiBaseUrl}/category/latest/next", [
            'page' => $page,
            '_t' => time(),
          ]);
          
          if ($response->successful()) {
            return $response->json();
          }
          return ['data' => [], 'pagination' => null];
      });
      
      $pagination = $result['pagination'] ?? null;
      
      return view('lastest', [
        'categories' => $result['data'] ?? [],
        'currentPage' => $pagination['currentPage'] ?? $page,
        'pageSize' => $pagination['pageSize'] ?? 16,
        'totalPages' => $pagination['totalPages'] ?? 1,
        'totalCount' => $pagination['totalCount'] ?? 0,
        'hasNextPage' => $pagination['hasNextPage'] ?? false,
        'hasPrevPage' => $pagination['hasPrevPage'] ?? false,
      ]);
    } catch (\Exception $e) {
      Log::error("Latest Error: " . $e->getMessage());
      
      return view('lastest', [
        'categories' => [],
        'currentPage' => $page,
        'totalPages' => 1,
        'hasNextPage' => false,
        'hasPrevPage' => false,
      ]);
    }
  }
}
