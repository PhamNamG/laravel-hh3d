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

</style>