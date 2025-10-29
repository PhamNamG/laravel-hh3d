@extends('layouts.app')

@section('title', ($category['name'] ?? 'Xem phim') . ' - Tập ' . ($episode['seri'] ?? '') . ' - HH3D')

@section('content')
<div class="container">
    <div class="xem-wrapper">
        <!-- Main Content -->
        <div class="xem-main">
            <!-- Video Player Section -->
            <div class="player-container">
                <div class="player-wrapper">
                    @if(isset($episode['dailyMotionServer']) && $episode['dailyMotionServer'])
                        <iframe 
                            src="{{ $episode['dailyMotionServer'] }}" 
                            frameborder="0" 
                            allow="autoplay; fullscreen; picture-in-picture" 
                            allowfullscreen
                            class="video-player"
                        ></iframe>
                    @elseif(isset($episode['link']) && $episode['link'])
                        <video 
                            controls 
                            autoplay 
                            class="video-player"
                            poster="{{ $category['linkImg'] ?? '' }}"
                        >
                            <source src="{{ $episode['link'] }}" type="video/mp4">
                            Trình duyệt của bạn không hỗ trợ video.
                        </video>
                    @else
                        <div class="no-video">
                            <p>Video đang được cập nhật</p>
                        </div>
                    @endif
                </div>

                <!-- Player Controls -->
                <div class="player-controls">
                    <!-- Navigation Buttons -->
                    <div class="episode-navigation">
                        @if($prevEpisode)
                            <a href="{{ url('/xem/' . $category['slug'] . '/' . $prevEpisode) }}" class="nav-btn prev-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6"/>
                                </svg>
                                Tập trước
                            </a>
                        @else
                            <button class="nav-btn prev-btn disabled" disabled>
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m15 18-6-6 6-6"/>
                                </svg>
                                Tập trước
                            </button>
                        @endif

                        @if($nextEpisode)
                            <a href="{{ url('/xem/' . $category['slug'] . '/' . $nextEpisode) }}" class="nav-btn next-btn">
                                Tập tiếp theo
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </a>
                        @else
                            <button class="nav-btn next-btn disabled" disabled>
                                Tập tiếp theo
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="m9 18 6-6-6-6"/>
                                </svg>
                            </button>
                        @endif
                    </div>

                    <!-- Server Buttons -->
                    <div class="server-buttons">
                        <button class="server-btn server-vip1 active" data-server="dailymotion">
                            VIP 1
                        </button>
                        <button class="server-btn server-vip4k" data-server="vip4k">
                            VIP 4K
                        </button>
                        <button class="server-btn server-vip2" data-server="server2">
                            VIP 2
                        </button>
                        <button class="server-btn server-hx" data-server="hx">
                            HX
                        </button>
                    </div>
                </div>
            </div>

            <!-- Episode Info -->
            <div class="episode-info">
                <h1 class="episode-title">
                    <a href="{{ url('/phim/' . $category['slug']) }}">{{ $category['name'] ?? 'Phim' }}</a>
                    - Tập {{ $episode['seri'] ?? '' }}
                </h1>
                @if(isset($category['anotherName']))
                    <p class="episode-subtitle">{{ $category['anotherName'] }}</p>
                @endif
            </div>

            <!-- Episodes Section -->
            <section class="episodes-section">
                <!-- Server Tabs -->
                <div class="server-tabs">
                    <button class="server-tab active" data-server="vietsub">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                        </svg>
                        VIETSUB
                    </button>
                    
                    @if(isset($category['thuyetMinh']) && $category['thuyetMinh'])
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
                    @forelse($allEpisodes as $ep)
                        <a 
                            href="{{ url('/xem/' . $category['slug'] . '/' . $ep['slug']) }}" 
                            class="episode-button {{ $ep['slug'] === $episode['slug'] ? 'active' : '' }}"
                            title="Tập {{ $ep['seri'] }}"
                        >
                            Tập {{ $ep['seri'] }}
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
/* Xem Page Specific Styles */
.xem-wrapper {
    display: grid;
    grid-template-columns: 1fr 320px;
    gap: 30px;
}

.xem-main {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* Video Player */
.player-container {
    background-color: var(--bg-card);
    border-radius: 10px;
    overflow: hidden;
}

.player-wrapper {
    position: relative;
    padding-bottom: 56.25%; /* 16:9 aspect ratio */
    height: 0;
    background-color: #000;
}

.video-player {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: none;
}

.no-video {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
    color: var(--text-muted);
}

/* Player Controls */
.player-controls {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    background-color: var(--bg-card);
    gap: 20px;
}

.episode-navigation {
    display: flex;
    gap: 10px;
}

.nav-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background-color: var(--accent-primary);
    color: white;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
}

.nav-btn:hover:not(.disabled) {
    background-color: var(--accent-secondary);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
}

.nav-btn.disabled {
    background-color: var(--bg-secondary);
    color: var(--text-muted);
    cursor: not-allowed;
    opacity: 0.5;
}

/* Server Buttons */
.server-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.server-btn {
    padding: 10px 20px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    cursor: pointer;
}

.server-vip1 {
    background-color: #22c55e;
    color: white;
}

.server-vip1:hover {
    background-color: #16a34a;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(34, 197, 94, 0.4);
}

.server-vip4k {
    background-color: #3b82f6;
    color: white;
}

.server-vip4k:hover {
    background-color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
}

.server-vip2 {
    background-color: #0891b2;
    color: white;
}

.server-vip2:hover {
    background-color: #0e7490;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(8, 145, 178, 0.4);
}

.server-hx {
    background-color: #0284c7;
    color: white;
}

.server-hx:hover {
    background-color: #0369a1;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(2, 132, 199, 0.4);
}

.server-btn.active {
    border-color: white;
    box-shadow: 0 0 0 2px currentColor;
}

/* Episode Info */
.episode-info {
    background-color: var(--bg-card);
    padding: 20px;
    border-radius: 10px;
}

.episode-title {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 8px 0;
}

.episode-title a {
    color: var(--accent-primary);
    transition: color 0.3s ease;
}

.episode-title a:hover {
    color: var(--accent-secondary);
}

.episode-subtitle {
    font-size: 14px;
    color: var(--text-secondary);
    margin: 0;
}

/* Episodes Section */
.episodes-section {
    background-color: var(--bg-card);
    border-radius: 10px;
    padding: 25px;
}

.server-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid var(--border-color);
}

.server-tab {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 20px;
    background-color: var(--bg-secondary);
    border: 1px solid var(--border-color);
    border-radius: 6px;
    color: var(--text-secondary);
    font-size: 13px;
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

.episode-button.active {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border-color: #ef4444;
    color: white;
    font-weight: 700;
    box-shadow: 0 5px 15px rgba(239, 68, 68, 0.4);
}

.empty-episodes {
    grid-column: 1 / -1;
    text-align: center;
    padding: 40px;
    color: var(--text-muted);
}

/* Responsive */
@media (max-width: 1024px) {
    .xem-wrapper {
        grid-template-columns: 1fr;
    }
    
    .player-controls {
        flex-direction: column;
        align-items: stretch;
    }
    
    .episode-navigation {
        width: 100%;
    }
    
    .nav-btn {
        flex: 1;
        justify-content: center;
    }
    
    .server-buttons {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 768px) {
    .episode-title {
        font-size: 18px;
    }
    
    .episodes-grid {
        grid-template-columns: repeat(auto-fill, minmax(80px, 1fr));
    }
    
    .server-buttons {
        gap: 5px;
    }
    
    .server-btn {
        padding: 8px 15px;
        font-size: 12px;
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
    
    .nav-btn {
        padding: 8px 15px;
        font-size: 13px;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Server button functionality
const serverButtons = document.querySelectorAll('.server-btn');
const videoPlayer = document.querySelector('.video-player');

serverButtons.forEach(btn => {
    btn.addEventListener('click', function() {
        // Remove active from all
        serverButtons.forEach(b => b.classList.remove('active'));
        
        // Add active to clicked
        this.classList.add('active');
        
        const server = this.dataset.server;
        
        // Switch video source based on server
        switch(server) {
            case 'dailymotion':
                if (videoPlayer && videoPlayer.tagName === 'IFRAME') {
                    videoPlayer.src = '{{ $episode["dailyMotionServer"] ?? "" }}';
                }
                break;
            case 'server2':
                // Load server 2 if available
                console.log('Loading VIP 2 server...');
                break;
            case 'vip4k':
                // Load VIP 4K server if available
                console.log('Loading VIP 4K server...');
                break;
            case 'hx':
                // Load HX server if available
                console.log('Loading HX server...');
                break;
        }
    });
});

// Server tabs functionality
document.querySelectorAll('.server-tab').forEach(tab => {
    tab.addEventListener('click', function() {
        document.querySelectorAll('.server-tab').forEach(t => t.classList.remove('active'));
        this.classList.add('active');
        
        const server = this.dataset.server;
        console.log('Selected server:', server);
    });
});

// Auto scroll to active episode
const activeEpisode = document.querySelector('.episode-button.active');
if (activeEpisode) {
    activeEpisode.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Arrow left - previous episode
    if (e.key === 'ArrowLeft') {
        const prevBtn = document.querySelector('.prev-btn:not(.disabled)');
        if (prevBtn) prevBtn.click();
    }
    
    // Arrow right - next episode
    if (e.key === 'ArrowRight') {
        const nextBtn = document.querySelector('.next-btn:not(.disabled)');
        if (nextBtn) nextBtn.click();
    }
});
</script>
@endpush

