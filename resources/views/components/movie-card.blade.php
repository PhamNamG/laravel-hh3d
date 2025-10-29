@props(['category'])

<div class="movie-card">
    <a href="{{ url('/phim/' . $category['slug']) }}" class="movie-link">
        <!-- Poster Image -->
        <div class="movie-poster">
            <img 
                src="{{ $category['linkImg'] ?? 'https://via.placeholder.com/300x400?text=No+Image' }}" 
                alt="{{ $category['name'] }}"
                loading="lazy"
            >
            
            <!-- Episode Badge -->
            @if(isset($category['products']) && count($category['products']) > 0)
                <div class="episode-badge">
                    {{ formatEpisodeCount(count($category['products']), $category['sumSeri'] ?? null) }}
                    [{{ $category['quality'] ?? 'HD' }}]
                </div>
            @endif
            
            <!-- Status Badge -->
            @if(isset($category['status']))
                <div class="status-badge status-{{ strtolower($category['status']) }}">
                    {{ $category['status'] === 'completed' ? 'Hoàn Thành' : 'Đang Phát' }}
                </div>
            @endif
            
            <!-- New Badge -->
            @if(isset($category['newMovie']) && $category['newMovie'])
                <div class="new-badge">NEW</div>
            @endif
            
            <!-- Play Icon Overlay -->
            <div class="play-overlay">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="white">
                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                </svg>
            </div>
        </div>
        
        <!-- Movie Info -->
        <div class="movie-info">
            <h3 class="movie-title">{{ $category['name'] }}</h3>
            
            @if(isset($category['anotherName']))
                <p class="movie-subtitle">{{ $category['anotherName'] }}</p>
            @endif
            
            <div class="movie-meta">
                @if(isset($category['year']))
                    <span class="meta-item">{{ $category['year'] }}</span>
                @endif
                
                @if(isset($category['lang']))
                    <span class="meta-item">{{ $category['lang'] }}</span>
                @endif
                
                @if(isset($category['rating']) && count($category['rating']) > 0)
                    @php
                        $avgRating = formatRating($category['rating']);
                    @endphp
                    @if($avgRating)
                        <span class="meta-item rating">
                            ⭐ {{ $avgRating }}
                        </span>
                    @endif
                @endif
            </div>
        </div>
    </a>
</div>

