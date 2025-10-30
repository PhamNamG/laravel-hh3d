{{-- Sidebar Popular - Xem Nhiều --}}
@props(['categories'])

<div id="enjoytube-recent-2" class="widget widget-enjoytube-recent widget-posts-thumbnail">
    <h2 class="widget-title mt-2"><span>Xem Nhiều</span></h2>
    <div class="popular-post">
        @forelse($categories as $category)
            <div class="item post-{{ $category['_id'] ?? '' }}">
                <a class="thumbnail-link" 
                   title="{{ $category['name'] ?? 'Phim' }}" 
                   href="{{ url('/phim/' . ($category['slug'] ?? '#')) }}" 
                   rel="bookmark">
                    
                    <div class="item-link">
                        <img 
                            width="60" 
                            height="80" 
                            src="{{ $category['linkImg'] ?? 'https://via.placeholder.com/60x80?text=No+Image' }}" 
                            alt="{{ $category['name'] ?? 'Phim' }}" 
                            class="wp-post-image img-responsive"
                            loading="lazy"
                        >
                    </div>
                    
                    <h3 class="title">{{ $category['name'] ?? 'Phim' }}</h3>
                    
                    @if(isset($category['anotherName']) && $category['anotherName'])
                        <p class="original_title">{{ $category['anotherName'] }}</p>
                    @endif
                </a>
            </div>
        @empty
            <div class="item">
                <p style="color: #ccc; text-align: center; padding: 20px;">
                    Chưa có dữ liệu
                </p>
            </div>
        @endforelse
    </div>
</div>
