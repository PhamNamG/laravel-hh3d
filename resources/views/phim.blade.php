@extends('layouts.app')

@section('title', ($phim['name'] ?? 'Phim') . ' - HH3D')

@section('content')
<div class="container">
    <div class="phim-wrapper">
        <!-- Main Content -->
        <div class="phim-main">
            <!-- Phim Info Section -->
            <div class="phim-info-container">
                <!-- Poster -->
                <div class="phim-poster-large">
                    <img 
                        src="{{ $phim['linkImg'] ?? 'https://via.placeholder.com/300x400?text=No+Image' }}" 
                        alt="{{ $phim['name'] ?? '' }}"
                    >
                    <a href="#episodes" class="btn-watch-now">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                            <polygon points="5 3 19 12 5 21 5 3"></polygon>
                        </svg>
                        Xem phim
                    </a>
                </div>

                <!-- Info Details -->
                <div class="phim-details">
                    <h1 class="phim-title">{{ $phim['name'] ?? 'Không có tên' }}</h1>
                    
                    @if(isset($phim['anotherName']))
                        <p class="phim-subtitle">{{ $phim['anotherName'] }}</p>
                    @endif

                    <div class="phim-meta-list">
                        @if(isset($phim['type']))
                            <div class="meta-row">
                                <span class="meta-label">Thể Loại:</span>
                                <span class="meta-value">{{ $phim['type'] }}</span>
                            </div>
                        @endif

                        @if(count($episodes) > 0)
                            <div class="meta-row">
                                <span class="meta-label">Tập mới nhất:</span>
                                <span class="meta-value highlight">
                                    Tập {{ $episodes[0]['seri'] ?? 0 }}
                                    @if(isset($phim['sumSeri']))
                                        /{{ $phim['sumSeri'] }}
                                    @endif
                                    [{{ $phim['quality'] ?? 'HD' }}]
                                </span>
                            </div>
                        @endif

                        @if(isset($phim['status']))
                            <div class="meta-row">
                                <span class="meta-label">Tình trạng:</span>
                                <span class="meta-value">{{ $phim['status'] === 'completed' ? 'Hoàn thành' : 'Đang chiếu' }}</span>
                            </div>
                        @endif

                        @if(isset($phim['view']))
                            <div class="meta-row">
                                <span class="meta-label">Lượt xem:</span>
                                <span class="meta-value">{{ number_format($phim['view']) ?? '2207K' }}</span>
                            </div>
                        @endif

                        @if(isset($phim['rating']) && count($phim['rating']) > 0)
                            @php
                                $avgRating = formatRating($phim['rating']);
                                $ratingCount = count($phim['rating']);
                            @endphp
                            <div class="meta-row">
                                <span class="meta-label">Đánh Giá:</span>
                                <span class="meta-value rating-display">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($avgRating))
                                            ⭐
                                        @elseif($i - 0.5 <= $avgRating)
                                            ⭐
                                        @else
                                            ☆
                                        @endif
                                    @endfor
                                    <span class="rating-text">{{ $avgRating }}/5 - ({{ $ratingCount }} bình chọn)</span>
                                </span>
                            </div>
                        @endif

                        @if(isset($phim['year']))
                            <div class="meta-row">
                                <span class="meta-label">Năm:</span>
                                <span class="meta-value">{{ $phim['year'] }}</span>
                            </div>
                        @endif

                        @if(isset($phim['country']))
                            <div class="meta-row">
                                <span class="meta-label">Quốc gia:</span>
                                <span class="meta-value">{{ $phim['country'] }}</span>
                            </div>
                        @endif

                        @if(isset($phim['lang']))
                            <div class="meta-row">
                                <span class="meta-label">Ngôn ngữ:</span>
                                <span class="meta-value">{{ $phim['lang'] }}</span>
                            </div>
                        @endif
                    </div>

                    @if(isset($phim['des']))
                        <div class="phim-description">
                            <p>{{ $phim['des'] }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Episodes Section -->
            <section class="episodes-section" id="episodes">
                <!-- Server Tabs -->
                <div class="server-tabs">
                    <button class="server-tab active" data-server="vietsub">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        VIETSUB
                    </button>
                    
                    @if(isset($phim['thuyetMinh']) && $phim['thuyetMinh'])
                        <button class="server-tab" data-server="thuyet-minh">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                            </svg>
                            THUYẾT MINH
                        </button>
                    @endif
                </div>

                <!-- Episodes Grid -->
                <div class="episodes-grid">
                    @forelse($episodes as $episode)
                        <a 
                            href="{{ url('/xem/' . $phim['slug'] . '/' . $episode['slug']) }}" 
                            class="episode-button"
                            title="{{ $episode['name'] ?? 'Tập ' . $episode['seri'] }}"
                        >
                            Tập {{ $episode['seri'] }}
                        </a>
                    @empty
                        <div class="empty-episodes">
                            <p>Chưa có tập phim nào</p>
                        </div>
                    @endforelse
                </div>
            </section>
        </div>

        <!-- Sidebar -->
        <x-sidebar-popular :categories="$popularCategories" />
    </div>
</div>
@endsection

@push('styles')
<style>
/* Phim Page Specific Styles */
.phim-wrapper {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 30px;
}

.phim-main {
    display: flex;
    flex-direction: column;
    gap: 30px;
}

/* Phim Info Container */
.phim-info-container {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 30px;
    background-color: var(--bg-card);
    border-radius: 10px;
    padding: 30px;
}

.phim-poster-large {
    position: relative;
    width: 300px;
    flex-shrink: 0;
}

.phim-poster-large img {
    width: 100%;
    border-radius: 10px;
    display: block;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.5);
}

.btn-watch-now {
    position: absolute;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    background: linear-gradient(135deg, #ff8c00, #ff4500);
    color: white;
    padding: 12px 30px;
    border-radius: 25px;
    font-size: 16px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    box-shadow: 0 5px 20px rgba(255, 69, 0, 0.4);
    transition: all 0.3s ease;
}

.btn-watch-now:hover {
    transform: translateX(-50%) translateY(-3px);
    box-shadow: 0 8px 30px rgba(255, 69, 0, 0.6);
}

.phim-details {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.phim-title {
    font-size: 32px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    line-height: 1.3;
}

.phim-subtitle {
    font-size: 18px;
    color: var(--text-secondary);
    margin: 0;
}

.phim-meta-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.meta-row {
    display: flex;
    gap: 15px;
    font-size: 14px;
}

.meta-label {
    color: var(--text-secondary);
    min-width: 120px;
    font-weight: 500;
}

.meta-value {
    color: var(--text-primary);
    flex: 1;
}

.meta-value.highlight {
    color: var(--accent-primary);
    font-weight: 600;
}

.rating-display {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 18px;
}

.rating-text {
    font-size: 14px;
    color: var(--text-secondary);
}

.phim-description {
    margin-top: 10px;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
}

.phim-description p {
    color: var(--text-secondary);
    line-height: 1.8;
    font-size: 14px;
}

/* Episodes Section */
.episodes-section {
    background-color: var(--bg-card);
    border-radius: 10px;
    padding: 30px;
}

.server-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 25px;
    padding-bottom: 20px;
    border-bottom: 2px solid var(--border-color);
}

.server-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 8px;
    color: var(--text-secondary);
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
}

.server-tab:hover {
    background-color: var(--bg-card-hover);
    border-color: var(--accent-primary);
    color: var(--text-primary);
}

.server-tab.active {
    background-color: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
}

.episodes-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(90px, 1fr));
    gap: 10px;
}

.episode-button {
    padding: 12px 16px;
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-primary);
    font-size: 13px;
    font-weight: 500;
    text-align: center;
    transition: all 0.3s ease;
    white-space: nowrap;
}

.episode-button:hover {
    background-color: var(--accent-primary);
    border-color: var(--accent-primary);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
}

.empty-episodes {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 1024px) {
    .phim-wrapper {
        grid-template-columns: 1fr;
    }
    
    .phim-info-container {
        grid-template-columns: 250px 1fr;
        padding: 20px;
    }
    
    .phim-poster-large {
        width: 250px;
    }
}

@media (max-width: 768px) {
    .phim-info-container {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .phim-poster-large {
        width: 100%;
        max-width: 300px;
        margin: 0 auto;
    }
    
    .phim-title {
        font-size: 24px;
    }
    
    .meta-row {
        flex-direction: column;
        gap: 5px;
    }
    
    .meta-label {
        min-width: auto;
    }
    
    .episodes-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    }
    
    .server-tabs {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .episodes-grid {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .episode-button {
        padding: 10px;
        font-size: 12px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Server tabs functionality
document.querySelectorAll('.server-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        // Remove active from all tabs
        document.querySelectorAll('.server-tab').forEach(t => t.classList.remove('active'));
        
        // Add active to clicked tab
        this.classList.add('active');
        
        // Here you can implement different server logic
        const server = this.dataset.server;
        console.log('Selected server:', server);
    });
});

// Smooth scroll to episodes
document.querySelector('.btn-watch-now')?.addEventListener('click', function(e) {
    e.preventDefault();
    const episodesSection = document.querySelector('#episodes');
    if (episodesSection) {
        episodesSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Auto click first episode after scroll
        setTimeout(() => {
            const firstEpisode = document.querySelector('.episode-button');
            if (firstEpisode) {
                firstEpisode.style.animation = 'pulse 0.5s ease';
            }
        }, 500);
    }
});
</script>
@endpush
