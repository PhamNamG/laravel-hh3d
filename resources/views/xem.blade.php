@extends('layouts.app')

@section('title', ($category['name'] ?? 'Xem phim') . ' - Tập ' . ($episode['seri'] ?? '') . ' Vietsub - ' . ($category['anotherName'] ?? '') . ' - Hhkungffu')

@section('meta_description')
Xem {{ $category['name'] ?? 'phim' }} Tập {{ $episode['seri'] ?? '' }} {{ isset($category['anotherName']) ? '(' . $category['anotherName'] . ')' : '' }} Vietsub {{ isset($category['quality']) ? strtoupper($category['quality']) : 'HD' }} miễn phí. {{ isset($category['des']) ? Str::limit($category['des'], 120) : '' }}
@endsection

@section('meta_keywords')
{{ $category['name'] ?? '' }} tập {{ $episode['seri'] ?? '' }}, {{ $category['anotherName'] ?? '' }}, xem phim online, {{ $category['lang'] ?? 'vietsub' }}, {{ isset($category['tags']) ? implode(', ', array_column($category['tags'], 'name')) : '' }}
@endsection

@section('canonical_url', url('/xem/' . ($episode['slug'] ?? '')))

@section('og_type', 'video.episode')
@section('og_title', ($category['name'] ?? 'Xem phim') . ' - Tập ' . ($episode['seri'] ?? ''))
@section('og_description', 'Xem ' . ($category['name'] ?? 'phim') . ' Tập ' . ($episode['seri'] ?? '') . ' Vietsub miễn phí')
@section('og_image', $category['linkImg'] ?? asset('images/Logo.jpg'))
@section('og_url', url('/xem/' . ($episode['slug'] ?? '')))

@push('structured_data')
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "TVEpisode",
  "episodeNumber": "{{ $episode['seri'] ?? '' }}",
  "name": "{{ $category['name'] ?? '' }} - Tập {{ $episode['seri'] ?? '' }}",
  "description": "{{ isset($category['des']) ? Str::limit($category['des'], 200) : '' }}",
  "image": "{{ $category['linkImg'] ?? '' }}",
  "url": "{{ url('/xem/' . ($episode['slug'] ?? '')) }}",
  "partOfSeries": {
    "@type": "TVSeries",
    "name": "{{ $category['name'] ?? '' }}",
    "url": "{{ url('/phim/' . ($category['slug'] ?? '')) }}"
  },
  @if(isset($category['rating']) && count($category['rating']) > 0)
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "{{ number_format(array_sum($category['rating']) / count($category['rating']), 1) }}",
    "ratingCount": "{{ count($category['rating']) }}",
    "bestRating": "5",
    "worstRating": "1"
  },
  @endif
  "inLanguage": "{{ $category['lang'] ?? 'vi' }}",
  "countryOfOrigin": {
    "@type": "Country",
    "name": "{{ $category['country'] ?? 'Trung Quốc' }}"
  },
  "potentialAction": {
    "@type": "WatchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "{{ url('/xem/' . ($episode['slug'] ?? '')) }}",
      "actionPlatform": [
        "http://schema.org/DesktopWebPlatform",
        "http://schema.org/MobileWebPlatform"
      ]
    },
    "expectsAcceptanceOf": {
      "@type": "Offer",
      "price": "0",
      "priceCurrency": "VND",
      "availability": "https://schema.org/InStock"
    }
  }
}
</script>

<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "BreadcrumbList",
  "itemListElement": [
    {
      "@type": "ListItem",
      "position": 1,
      "name": "Trang chủ",
      "item": "{{ url('/') }}"
    },
    {
      "@type": "ListItem",
      "position": 2,
      "name": "{{ $category['name'] ?? 'Phim' }}",
      "item": "{{ url('/phim/' . ($category['slug'] ?? '')) }}"
    },
    {
      "@type": "ListItem",
      "position": 3,
      "name": "Tập {{ $episode['seri'] ?? '' }}",
      "item": "{{ url('/xem/' . ($episode['slug'] ?? '')) }}"
    }
  ]
}
</script>
@endpush

@section('content')
{{-- Episode Not Found --}}
@if(isset($notFound) && $notFound)
	<x-not-found 
		icon="fa-video-slash"
		title="Không tìm thấy tập phim"
		message="Rất tiếc, tập phim bạn đang tìm kiếm không tồn tại hoặc đã bị xóa."
	/>
@else
<main id="main-contents" class="col-xs-12 col-sm-12 col-md-8 watch-page single-movie" data-id="{{ $category['_id'] ?? '' }}">
    <section id="content">
                {{-- Video Player - Được tạo động bởi JavaScript --}}
                <div id="halim-player-wrapper" class="embed-responsive embed-responsive-16by9">
                    <div style="display: flex; align-items: center; justify-content: center; height: 400px; background: #000; color: #fff;">
                        <div class="flex flex-col items-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-white mb-2"></div>
                            <p>Đang tải video...</p>
                        </div>
                    </div>
                </div>
                
                <div class="clearfix"></div>
                
                {{-- Navigation Buttons --}}
                <div class="button-watch">
                    <div class="col-xs-12 col-md-8">
                        @if($prevEpisode)
                            <div class="luotxem halim-prev-episode">
                                <a href="{{ url('/xem/' . $prevEpisode) }}">
                                    <i class="fa-solid fa-backward"></i> Tập trước
                                </a>
                            </div>
                        @else
                            <div class="luotxem halim-prev-episode" style="opacity: 0.5; cursor: not-allowed;">
                                <i class="fa-solid fa-backward"></i> Tập trước
                            </div>
                        @endif
                        
                        @if($nextEpisode)
                            <div class="luotxem halim-next-episode">
                                <a href="{{ url('/xem/' . $nextEpisode) }}">
                                    Tập tiếp theo <i class="fa-solid fa-forward"></i>
                                </a>
                            </div>
                        @else
                            <div class="luotxem halim-next-episode" style="opacity: 0.5; cursor: not-allowed;">
                                Tập tiếp theo <i class="fa-solid fa-forward"></i>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="clearfix"></div>
                
                {{-- Server Selection Buttons - Will be generated by JavaScript --}}
                <div id="halim-ajax-list-server" class="text-center">
                    <!-- Server buttons will be dynamically generated -->
                </div>
                
                <div class="clearfix"></div>
                
                {{-- Episodes List --}}
                <div id="halim-list-server" class="list-eps-ajax">
                    <div class="halim-server show_all_eps">
                        <span class="halim-server-name">
                            <i class="fa-solid fa-database"></i>#Vietsub
                        </span>
                        <ul id="listsv-1" class="halim-list-eps">
                            @forelse($allEpisodes as $ep)
                                <li class="halim-episode halim-episode-1-tap-{{ $ep['seri'] ?? '' }} col-xs-3 col-sm-2 col-lg-1 {{ $ep['slug'] === $episode['slug'] ? 'active' : '' }}">
                                    <a 
                                        data-post-id="{{ $category['_id'] ?? '' }}" 
                                        data-ep="tap-{{ $ep['seri'] ?? '' }}" 
                                        data-sv="1" 
                                        href="{{ url('/xem/' . $ep['slug']) }}" 
                                        title="Tập {{ $ep['seri'] ?? '' }}"
                                    >
                                        <span class="box-shadow halim-btn {{ $ep['slug'] === $episode['slug'] ? 'active' : '' }}">
                                            {{ is_numeric($ep['seri']) ? 'Tập ' . $ep['seri'] : $ep['seri'] }}
                                        </span>
                                    </a>
                                </li>
                            @empty
                                <li class="col-md-12">
                                    <p style="color: #ccc; padding: 20px; text-align: center;">
                                        Chưa có tập phim nào
                                    </p>
                                </li>
                            @endforelse
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    
                    {{-- Thuyết Minh Server (if available) --}}
                    @if(isset($category['thuyetMinh']) && $category['thuyetMinh'])
                    <div class="halim-server show_all_eps">
                        <span class="halim-server-name">
                            <i class="fa-solid fa-database"></i>#Thuyết Minh:
                        </span>
                        <ul id="listsv-2" class="halim-list-eps">
                            @foreach($allEpisodes as $ep)
                                <li class="halim-episode halim-episode-2-tap-{{ $ep['seri'] ?? '' }} col-xs-3 col-sm-2 col-lg-1">
                                    <a 
                                        data-post-id="{{ $category['_id'] ?? '' }}" 
                                        data-ep="tap-{{ $ep['seri'] ?? '' }}" 
                                        data-sv="2" 
                                        href="{{ url('/xem/' . $ep['slug'] . '?server=2') }}" 
                                        title="Tập {{ $ep['seri'] ?? '' }}"
                                    >
                                        <span class="box-shadow halim-btn">Tập {{ $ep['seri'] ?? '' }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="clearfix"></div>
                    </div>
                    @endif
                </div>
                
                <div class="clearfix"></div>
                
                {{-- Title Block --}}
                <div class="title-block watch-page">
                    <div class="title-wrapper full">
                        <h1 class="entry-title">
                                <a href="{{ url('/xem/' . $episode['slug']) }}" 
                               title="{{ $category['name'] ?? 'Phim' }} Tập {{ $episode['seri'] ?? '' }} Vietsub" 
                               class="tl">
                                <i class="fa fa-info-circle"></i> 
                                {{ $category['name'] ?? 'Phim' }} Tập {{ $episode['seri'] ?? '' }} Vietsub
                            </a>
                        </h1>
                        <h2 class="halim-post-title">
                            <a class="original_title" href="{{ url('/phim/' . $category['slug']) }}">
                                {{ $category['name'] ?? 'Phim' }}
                                @if(isset($category['anotherName']) && $category['anotherName'])
                                    | {{ $category['anotherName'] }}
                                @endif
                            </a>
                        </h2>
                        
                        @if(isset($category['week']) && count($category['week']) > 0)
                        <h3 class="halim_showtime_movies" style="background-color: transparent; margin: 0">
                            Phim chiếu 1 tập vào {{ implode(', ', array_map(fn($w) => $w['name'] ?? '', $category['week'])) }}
                        </h3>
                        @endif
                    </div>
                    
                    {{-- Ratings --}}
                    @if(isset($category['rating']) && count($category['rating']) > 0)
                    @php
                        $avgRating = array_sum($category['rating']) / count($category['rating']);
                        $ratingCount = count($category['rating']);
                        $ratingWidth = ($avgRating / 5) * 100;
                    @endphp
                    <div class="ratings_wrapper">
                        <div class="kk-star-ratings">
                            <div class="kksr-stars">
                                <div class="kksr-stars-inactive">
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="kksr-star" data-star="{{ $i }}" style="padding-right: 5px">
                                            <div class="kksr-icon" style="width: 16px; height: 16px;"></div>
                                        </div>
                                    @endfor
                                </div>
                                <div class="kksr-stars-active" >
                                    @for($i = 1; $i <= 5; $i++)
                                        <div class="kksr-star" style="padding-right: 5px">
                                            <div class="kksr-icon" style="width: 16px; height: 16px;"></div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                            <div class="kksr-legend" style="font-size: 12.8px;">
                                {{ number_format($avgRating, 1) }}/5 - ({{ $ratingCount }} bình chọn)
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                
                {{-- Tags Section --}}
                @if(isset($category['tags']) && count($category['tags']) > 0)
                <div class="tags-list mt-5">
                    @foreach($category['tags'] as $tag)
                        <a href="{{ url('/tag/' . ($tag['slug'] ?? '')) }}" rel="tag">
                            {{ $tag['name'] ?? '' }}
                        </a>
                    @endforeach
                </div>
        @endif
    </section>
</main>
@endif
@endsection

@push('scripts')
<script>
// Pass episode data to JavaScript
window.episodeData = {
    voiceOverLink: @json($episode['voiceOverLink'] ?? ''),
    dailyMotionServer: @json($episode['dailyMotionServer'] ?? ''),
    server2: @json($episode['server2'] ?? ''),
    link: @json($episode['link'] ?? ''),
    voiceOverLink2: @json($episode['voiceOverLink2'] ?? '')
};
</script>
<script src="{{ asset('js/video-player.js') }}"></script>
@endpush
