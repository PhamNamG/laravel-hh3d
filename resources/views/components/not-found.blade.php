@props([
    'icon' => 'fa-film',
    'title' => 'Không tìm thấy',
    'message' => 'Rất tiếc, nội dung bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.',
])

<main id="main-contents" class="col-xs-12 col-sm-12 col-md-8 single-movie">
    <section id="content">
        <div class="empty-state">
            <div class="empty-state__icon">
                <i class="fa-solid {{ $icon }}"></i>
            </div>
            <h1 class="empty-state__title">{{ $title }}</h1>
            <p class="empty-state__message">{{ $message }}</p>
            <a href="{{ url('/') }}" class="empty-state__btn">
                <i class="fa-solid fa-home"></i>
                <span>Về Trang Chủ</span>
            </a>
        </div>
    </section>
</main>

<style>
.empty-state {
    text-align: center;
    padding: 80px 20px;
    max-width: 600px;
    margin: 0 auto;
}

.empty-state__icon {
    margin-bottom: 24px;
}

.empty-state__icon i {
    font-size: 80px;
    color: #e0e0e0;
    transition: color 0.3s ease;
}

.empty-state__title {
    color: #333;
    font-size: 28px;
    font-weight: 600;
    margin: 0 0 16px;
    line-height: 1.3;
}

.empty-state__message {
    color: #666;
    font-size: 16px;
    line-height: 1.6;
    margin: 0 0 32px;
}

.empty-state__btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 28px;
    background: #dc3545;
    color: #fff;
    text-decoration: none;
    border-radius: 6px;
    font-size: 15px;
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(220, 53, 69, 0.2);
}

.empty-state__btn:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
    color: #fff;
    text-decoration: none;
}

.empty-state__btn:active {
    transform: translateY(0);
}

/* Responsive */
@media (max-width: 768px) {
    .empty-state {
        padding: 60px 16px;
    }
    
    .empty-state__icon i {
        font-size: 64px;
    }
    
    .empty-state__title {
        font-size: 24px;
    }
    
    .empty-state__message {
        font-size: 15px;
    }
}
</style>