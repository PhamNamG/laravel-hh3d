{{-- Movie Card Component - Maps API data to hhkungfu.ee theme --}}
@props(['category'])

<article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-{{ $category['_id'] ?? '' }}">
    <div class="halim-item">
        <a class="halim-thumb" 
           href="{{ url('/phim/' . ($category['slug'] ?? '#')) }}" 
           title="{{ $category['name'] ?? 'Phim' }}">
            
            <figure>
                <img 
                    width="300" 
                    height="400" 
                    src="{{ $category['linkImg'] ?? 'https://via.placeholder.com/300x400?text=No+Image' }}" 
                    alt="{{ $category['name'] ?? 'Phim' }}" 
                    class="wp-post-image img-responsive"
                    loading="lazy"
                >
            </figure>
            
            {{-- Quality Badge (FULL HD, 4K, etc) --}}
            @if(isset($category['quality']))
                <span class="status">{{ strtoupper($category['quality']) }}</span>
            @elseif(isset($category['lang']))
                <span class="status">{{ $category['lang'] }}</span>
            @else
                <span class="status">FULL HD</span>
            @endif
            
            {{-- Episode Count Badge --}}
            @php
                $sumSeri = $category['sumSeri'] ?? $category['totalEpisodes'] ?? 0;
                $latestProduct = isset($category['products']) && count($category['products']) > 0 
                    ? $category['products'][0] 
                    : null;
                $currentEpisode = $latestProduct['seri'] ?? $sumSeri;
            @endphp
            
            @if($sumSeri > 0)
                <span class="episode">
                    Tập {{ $currentEpisode }}/{{ $sumSeri }}
                    @if(isset($category['quality']) && str_contains(strtolower($category['quality']), '4k'))
                        [4K]
                    @endif
                </span>
            @endif
            
            {{-- Movie Title Box --}}
            <div class="halim-post-title-box">
                <div class="halim-post-title">
                    <h2 class="entry-title">{{ $category['name'] ?? 'Phim' }}</h2>
                    
                    @if(isset($category['anotherName']) && $category['anotherName'])
                        <p class="original_title">{{ $category['anotherName'] }}</p>
                    @endif
                </div>
            </div>
        </a>
    </div>
</article>
