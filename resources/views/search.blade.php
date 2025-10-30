@extends('layouts.app')

@section('title', 'Tìm kiếm: ' . ($query ?: 'Tất cả phim'))

@section('meta_description', 'Kết quả tìm kiếm cho "' . $query . '" - ' . $totalResults . ' phim tìm thấy')

@section('canonical_url', url('/search?q=' . urlencode($query)))

@section('content')
<div id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
    <div id="wrapper">
        <section class="hot-movies">
            {{-- Search Header --}}
            <div class="section-bar clearfix">
                <h3 class="section-title">
                    <span>
                        @if($query)
                            Kết quả tìm kiếm: "{{ $query }}"
                        @else
                            Tất cả phim
                        @endif
                    </span>
                </h3>
            </div>

            {{-- Search Stats --}}
            @if($query)
                <div style="padding: 15px 0; color: #999; font-size: 14px;">
                    <i class="fa-solid fa-magnifying-glass"></i> 
                    Tìm thấy <strong style="color: #fff;">{{ $totalResults }}</strong> kết quả
                    @if($totalResults === 0)
                        cho "<strong style="color: #fff;">{{ $query }}</strong>"
                    @endif
                </div>
            @endif


            {{-- Error Message --}}
            @if($error)
                <div class="alert alert-danger" style="background: rgba(220, 53, 69, 0.1); border: 1px solid rgba(220, 53, 69, 0.3); color: #dc3545;">
                    <i class="fa-solid fa-circle-exclamation"></i> {{ $error }}
                </div>
            @endif

            {{-- Results Grid --}}
            @if($totalResults > 0)
                <div class="halim_box">
                    @foreach($results as $category)
                        <x-movie-card :category="$category" />
                    @endforeach
                </div>

                <div class="clearfix"></div>

            @elseif($query && $totalResults === 0 && !$error)
                {{-- No Results --}}
                <div style="text-align: center; padding: 80px 20px; color: #999;">
                    <i class="fa-solid fa-film" style="font-size: 64px; opacity: 0.3; margin-bottom: 20px;"></i>
                    <h3 style="color: #ccc; margin-bottom: 10px;">Không tìm thấy kết quả</h3>
                    <p style="font-size: 14px;">
                        Không có phim nào phù hợp với từ khóa "<strong style="color: #fff;">{{ $query }}</strong>"
                    </p>
                    <p style="font-size: 13px; margin-top: 20px;">
                        Gợi ý:
                    </p>
                    <ul style="list-style: none; padding: 0; font-size: 13px; line-height: 1.8;">
                        <li>✓ Kiểm tra chính tả từ khóa</li>
                        <li>✓ Thử tìm kiếm với từ khóa khác</li>
                        <li>✓ Sử dụng từ khóa ngắn gọn hơn</li>
                    </ul>
                    <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 30px;">
                        <i class="fa-solid fa-house"></i> Về trang chủ
                    </a>
                </div>
            @endif

            @if(!$query)
                {{-- Show instruction when no search query --}}
                <div style="text-align: center; padding: 80px 20px; color: #999;">
                    <i class="fa-solid fa-magnifying-glass" style="font-size: 64px; opacity: 0.3; margin-bottom: 20px;"></i>
                    <h3 style="color: #ccc; margin-bottom: 10px;">Tìm kiếm phim</h3>
                    <p style="font-size: 14px;">
                        Nhập tên phim bạn muốn tìm vào ô tìm kiếm phía trên
                    </p>
                </div>
            @endif

        </section>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto focus on search input
    const searchInput = document.querySelector('input[name="q"]');
    if (searchInput && !searchInput.value) {
        searchInput.focus();
    }

    // Handle form submit
    const searchForm = document.querySelector('.search-form');
    searchForm?.addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (!query) {
            e.preventDefault();
            alert('Vui lòng nhập từ khóa tìm kiếm');
            searchInput.focus();
        }
    });

    // Clear button (if exists)
    const clearBtn = document.querySelector('.clear-search');
    clearBtn?.addEventListener('click', function() {
        searchInput.value = '';
        searchInput.focus();
    });
});
</script>

<style>
.search-filter-box label {
    display: block;
}

.search-filter-box .form-control:focus {
    border-color: rgba(255,107,129,0.5);
    box-shadow: 0 0 0 0.2rem rgba(255,107,129,0.25);
    outline: none;
}

.search-filter-box .btn-primary {
    background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
    border: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.search-filter-box .btn-primary:hover {
    background: linear-gradient(135deg, #ff6b81 0%, #ff4757 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255,71,87,0.4);
}

.search-filter-box .btn-primary:active {
    transform: translateY(0);
}

@media (max-width: 768px) {
    .search-filter-box .form-group {
        margin-bottom: 15px;
    }
}
</style>
@endpush

