<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WeekController extends Controller
{
    private $apiBaseUrl;

    public function __construct()
    {
        $this->apiBaseUrl = config('api.base_url');
    }

    /**
     * Get schedule by week day
     */
    public function getByWeek(Request $request)
    {
        $week = $request->input('w', 'Thứ 2'); // Default: Monday
        
        try {
            // Cache schedule data for 5 minutes
            $cacheKey = 'week_schedule_' . md5($week);
            
            $schedule = Cache::remember($cacheKey, 300, function () use ($week) {
                $response = Http::timeout(10)
                    ->withHeaders(['Cache-Control' => 'no-cache'])
                    ->get("{$this->apiBaseUrl}/week", [
                        'w' => $week,
                        '_t' => time(),
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    // Backend returns {name, content} structure
                    return $data['content'] ?? [];
                }
                
                return [];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'week' => $week,
                    'content' => $schedule
                ]
            ]);

        } catch (\Exception $e) {
            Log::error("Week Schedule Error: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to load schedule',
                'data' => [
                    'week' => $week,
                    'content' => []
                ]
            ], 500);
        }
    }

    /**
     * Get current day in Vietnamese format
     */
    public static function getCurrentDay()
    {
        $dayMap = [
            'Monday' => 'Thứ 2',
            'Tuesday' => 'Thứ 3',
            'Wednesday' => 'Thứ 4',
            'Thursday' => 'Thứ 5',
            'Friday' => 'Thứ 6',
            'Saturday' => 'Thứ 7',
            'Sunday' => 'Chủ nhật',
        ];

        $currentDay = date('l'); // Get English day name
        return $dayMap[$currentDay] ?? 'Thứ 2';
    }

    /**
     * Clear schedule cache
     */
    public function clearCache()
    {
        $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];
        
        foreach ($days as $day) {
            Cache::forget('week_schedule_' . md5($day));
        }

        return response()->json([
            'success' => true,
            'message' => 'Schedule cache cleared successfully'
        ]);
    }
}

