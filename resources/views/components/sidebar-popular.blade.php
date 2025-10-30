@props(['categories'])

<aside class="sidebar">
    <section class="sidebar-section">
        <h2 class="sidebar-title">XEM NHIỀU</h2>
        
        <div class="sidebar-list">
            @forelse($categories as $index => $category)
                <div class="sidebar-item">
                    <a href="{{ url('/phim/' . $category['slug']) }}" class="sidebar-link">
                        <div class="sidebar-poster">
                            <img 
                                src="{{ $category['linkImg'] ?? 'https://via.placeholder.com/80x120?text=No+Image' }}" 
                                alt="{{ $category['name'] }}"
                                loading="lazy"
                            >
                            
                            <!-- Rank Badge -->
                            <div class="rank-badge rank-{{ $index + 1 }}">
                                {{ $index + 1 }}
                            </div>
                        </div>
                        
                        <div class="sidebar-info">
                            <h4 class="sidebar-movie-title">{{ $category['name'] }}</h4>
                            
                            @if(isset($category['anotherName']))
                                <p class="sidebar-movie-subtitle">{{ $category['anotherName'] }}</p>
                            @endif
                            
                            <div class="sidebar-meta">
                                @if(isset($category['products']) && count($category['products']) > 0)
                                    <span class="meta-tag">Tập {{ count($category['products']) }}</span>
                                @endif
                                
                                @if(isset($category['quality']))
                                    <span class="meta-tag">{{ $category['quality'] }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="empty-state-small">
                    <p>Chưa có dữ liệu</p>
                </div>
            @endforelse
        </div>
    </section>
</aside>


