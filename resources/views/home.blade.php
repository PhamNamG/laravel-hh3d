@extends('layouts.app')

@section('title', 'Hhkungfu - Xem Phim Hoạt Hình 3D Trung Quốc Vietsub Miễn Phí')

@section('meta_description', 'Xem phim hoạt hình 3D Trung Quốc mới nhất, chất lượng cao Full HD. Cập nhật liên tục phim hoạt hình Trung Quốc hay nhất, xem phim online miễn phí tại Hhkungfu.')

@section('meta_keywords', 'phim hoạt hình 3D, phim hoạt hình Trung Quốc, xem phim online, phim 3D vietsub, phim hoạt hình miễn phí, phim Trung Quốc mới nhất')

@section('canonical_url', url('/'))

@section('og_type', 'website')
@section('og_title', 'Hhkungfu - Xem Phim Hoạt Hình 3D Trung Quốc')
@section('og_description', 'Kho phim hoạt hình 3D Trung Quốc phong phú, cập nhật liên tục. Xem miễn phí, chất lượng cao.')
@section('og_url', url('/'))

@push('structured_data')
<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "WebSite",
		"name": "Hhkungfu",
		"url": "{{ url('/') }}",
		"description": "Xem phim hoạt hình 3D Trung Quốc miễn phí",
		"potentialAction": {
			"@type": "SearchAction",
			"target": "{{ url('/search') }}?q={search_term_string}",
			"query-input": "required name=search_term_string"
		}
	}
</script>
@endpush

@section('content')
<div id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
	<div id="wrapper">
		<section class="hot-movies">
			<div class="section-bar clearfix">
				<h3 class="section-title"><span>Mới cập nhật</span></h3>
			</div>

			@if(isset($error))
			<div class="alert alert-danger">
				{{ $error }}
			</div>
			@endif

			<div class="halim_box">
				@forelse($categories as $category)
				<x-movie-card :category="$category" />
				@empty
				<div class="col-md-12">
					<p class="text-center" style="color: #ccc; padding: 40px;">
						Chưa có phim nào được cập nhật
					</p>
				</div>
				@endforelse
			</div>

			<div class="clearfix"></div>

			<div class="text-center">
			<a href="{{ url('/moi-cap-nhat?page=2') }}" class="">Xem thêm</a>
			</div>
		</section>
		<x-week />
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