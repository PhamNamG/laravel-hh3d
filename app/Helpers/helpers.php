<?php

if (!function_exists('formatEpisodeCount')) {
    /**
     * Format episode count display
     */
    function formatEpisodeCount($current, $total = null)
    {
        if ($total) {
            return "Tập {$current}/{$total}";
        }
        return "Tập {$current}";
    }
}

if (!function_exists('formatRating')) {
    /**
     * Calculate average rating
     */
    function formatRating($ratings)
    {
        if (empty($ratings)) {
            return null;
        }
        
        $average = array_sum($ratings) / count($ratings);
        return number_format($average, 1);
    }
}

if (!function_exists('getQualityBadgeClass')) {
    /**
     * Get CSS class for quality badge
     */
    function getQualityBadgeClass($quality)
    {
        $classes = [
            '4K' => 'quality-4k',
            'FHD' => 'quality-fhd',
            'HD' => 'quality-hd',
        ];
        
        return $classes[$quality] ?? 'quality-default';
    }
}

if (!function_exists('truncateText')) {
    /**
     * Truncate text with ellipsis
     */
    function truncateText($text, $length = 100)
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        
        return mb_substr($text, 0, $length) . '...';
    }
}

if (!function_exists('isNewRelease')) {
    /**
     * Check if category is new (released within 7 days)
     */
    function isNewRelease($releaseDate)
    {
        if (!$releaseDate) {
            return false;
        }
        
        $release = strtotime($releaseDate);
        $now = time();
        $daysDiff = ($now - $release) / (60 * 60 * 24);
        
        return $daysDiff <= 7;
    }
}

if (!function_exists('formatViews')) {
    /**
     * Format view count (1000 -> 1K, 1000000 -> 1M)
     */
    function formatViews($count)
    {
        if ($count >= 1000000) {
            return number_format($count / 1000000, 1) . 'M';
        }
        
        if ($count >= 1000) {
            return number_format($count / 1000, 1) . 'K';
        }
        
        return $count;
    }
}

