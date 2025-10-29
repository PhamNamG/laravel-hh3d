@extends('layouts.app')

@section('title', 'Trang Chủ - HH3D')

@section('content')
<div class="container">
    <div class="home-wrapper">
        <!-- Main Content -->
        <div class="main-section">
            <!-- New Updates Section -->
            <section class="section">
                <h2 class="section-title">MỚI CẬP NHẬT</h2>
                
                @if(isset($error))
                    <div class="error-message">
                        <p>{{ $error }}</p>
                    </div>
                @endif
                
                <div class="movie-grid">
                    @forelse($categories as $category)
                        <x-movie-card :category="$category" />
                    @empty
                        <div class="empty-state">
                            <p>Chưa có phim nào được cập nhật</p>
                        </div>
                    @endforelse
                </div>
                
                <!-- Load More Button -->
                @if(count($categories) >= 12)
                    <div class="load-more-wrapper">
                        <button class="btn btn-load-more" onclick="loadMoreCategories()">
                            Xem Thêm
                        </button>
                    </div>
                @endif
            </section>
        </div>
        
        <!-- Sidebar -->
        <x-sidebar-popular :categories="$popularCategories" />
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPage = 1;

async function loadMoreCategories() {
    currentPage++;
    const button = event.target;
    button.disabled = true;
    button.textContent = 'Đang tải...';
    
    try {
        // Có thể implement AJAX load more ở đây
        window.location.href = `/?page=${currentPage}`;
    } catch (error) {
        console.error('Error loading more:', error);
        button.disabled = false;
        button.textContent = 'Xem Thêm';
    }
}

// Refresh button functionality
document.querySelector('.btn-refresh')?.addEventListener('click', () => {
    location.reload();
});

// Search form enhancement
const searchInput = document.querySelector('.search-input');
searchInput?.addEventListener('keypress', (e) => {
    if (e.key === 'Enter' && !searchInput.value.trim()) {
        e.preventDefault();
        alert('Vui lòng nhập từ khóa tìm kiếm');
    }
});
</script>
@endpush

