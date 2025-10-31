@php
$menuItems = [
[
'title' => 'Trang Chủ',
'url' => '/',
'pattern' => '/',
'id' => 48,
],
[
'title' => 'Thể Loại',
'url' => '#',
'pattern' => 'the-loai*',
'id' => 18,
'dropdown' => true,
'children' => [
['title' => 'Tu Tiên', 'url' => '/category/tu-tien', 'id' => 25],
['title' => 'Luyện Cấp', 'url' => '/category/luyen-cap', 'id' => 22],
['title' => 'Trùng Sinh', 'url' => '/category/trung-sinh', 'id' => 24],
['title' => 'Kiếm Hiệp', 'url' => '/category/kiem-hiep', 'id' => 21],
['title' => 'Xuyên Không', 'url' => '/category/xuyen-khong', 'id' => 26],
['title' => 'Hài Hước', 'url' => '/category/hai-huoc', 'id' => 19],
['title' => 'Hiện Đại', 'url' => '/category/hien-dai', 'id' => 20],
['title' => 'OVA', 'url' => '/category/ova', 'id' => 23],
]
],
[
'title' => 'Lịch Chiếu',
'url' => '/lich-chieu',
'pattern' => 'lich-chieu*',
'id' => 44,
],
[
'title' => 'Mới Cập Nhật',
'url' => '/moi-cap-nhat',
'pattern' => 'moi-cap-nhat*',
'id' => 45,
],
[
'title' => 'Xem Nhiều',
'url' => '/xem-nhieu',
'pattern' => 'xem-nhieu*',
'id' => 46,
],
[
'title' => 'Hoàn Thành',
'url' => '/hoan-thanh',
'pattern' => 'hoan-thanh*',
'id' => 47,
],
];
@endphp

{{-- Navigation Menu --}}
<nav id="main-menu" class="main-menu">
	<div class="container md:p-0">
		<div class="navbar-header">
			<button aria-label="menu" type="button" class="navbar-toggle pull-left expand-menu" data-toggle="collapse" data-target="#halim" aria-expanded="false">
				<i class="fa-solid fa-bars-staggered"></i>
			</button>
			<button aria-label="search" type="button" class="navbar-toggle pull-right expand-search-form" data-toggle="collapse" data-target="#search-form" aria-expanded="false">
				<i class="fa-solid fa-magnifying-glass"></i>
			</button>
		</div>

		{{-- Desktop Menu --}}
		<ul class="nav-menu-list">
			@foreach($menuItems as $item)
			<li class="nav-item {{ request()->is($item['pattern']) ? 'active' : '' }}">
				<a href="{{ url($item['url']) }}">
					<span>{{ $item['title'] }}</span>
				</a>
			</li>
			@endforeach
		</ul>

		{{-- Mobile Menu --}}
		<div id="halim" class="collapse navbar-collapse d-md-none">
			<ul id="menu-menu" class="nav navbar-nav navbar-left">
				@foreach($menuItems as $item)
				<li itemscope="itemscope"
					itemtype="https://www.schema.org/SiteNavigationElement"
					id="menu-item-{{ $item['id'] }}"
					class="menu-item menu-item-type-custom menu-item-object-custom {{ request()->is($item['pattern']) ? 'active' : '' }} {{ isset($item['dropdown']) ? 'menu-item-has-children dropdown' : '' }} menu-item-{{ $item['id'] }} nav-item">

					@if(isset($item['dropdown']))
					<a title="{{ $item['title'] }}"
						href="{{ $item['url'] }}"
						data-toggle="dropdown"
						aria-haspopup="true"
						aria-expanded="false"
						class="dropdown-toggle nav-link"
						id="menu-item-dropdown-{{ $item['id'] }}">
						{{ $item['title'] }}
					</a>
					<ul class="dropdown-menu" aria-labelledby="menu-item-dropdown-{{ $item['id'] }}" role="menu">
						@foreach($item['children'] as $child)
						<li itemscope="itemscope"
							itemtype="https://www.schema.org/SiteNavigationElement"
							id="menu-item-{{ $child['id'] }}"
							class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-{{ $child['id'] }} nav-item">
							<a title="{{ $child['title'] }}"
								href="{{ url($child['url']) }}"
								class="dropdown-item">
								{{ $child['title'] }}
							</a>
						</li>
						@endforeach
					</ul>
					@else
					<a title="{{ $item['title'] }}"
						href="{{ url($item['url']) }}"
						class="nav-link">
						{{ $item['title'] }}
					</a>
					@endif
				</li>
				@endforeach
			</ul>
		</div>

		{{-- Search Form --}}

		<div class="navbar-collapse collapse" id="search-form" aria-expanded="true" >
			<div id="mobile-search-form" class="halim-search-form">
				<form id="search-form-pc" name="halimForm" role="search" action="/search" method="GET">
					<div class="form-group">
						<div class="input-group col-xs-12">
						<input id="search" type="text" name="q" value="{{ request('q') }}" class="form-control" data-toggle="tooltip" data-placement="bottom" data-original-title="Nhấn Enter để tìm kiếm" placeholder="Tìm kiếm phim..." autocomplete="off" required=""> 
							<i class="animate-spin hl-spin4 hidden"></i>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</nav>

<style>
	#main-menu {
		background: #12171b;
		border-bottom: 1px solid rgba(255, 255, 255, 0.1);
		padding: 0;
	}

	.nav-menu-list {
		display: flex;
		list-style: none;
		margin: 0;
		padding: 0;
		justify-content: start;
		flex-wrap: wrap;
	}

	.nav-item {
		position: relative;
	}

	.nav-item a {
		display: flex;
		align-items: center;
		gap: 7px;
		padding: 15px 13px;
		color: #ccc;
		text-decoration: none;
		transition: all 0.3s ease;
		font-size: 14px;
		font-weight: 500;
	}

	.nav-item a i {
		font-size: 16px;
	}

	.nav-item a:hover {
		color: #0af;
		background: rgba(0, 170, 255, 0.1);
	}

	.nav-item.active a {
		color: #fff;
	}

	@media (max-width: 767px) {
		.nav-menu-list {
			display: none;
			justify-content: flex-start;
			overflow-x: auto;
			-webkit-overflow-scrolling: touch;
		}

		.nav-item a {
			padding: 12px 15px;
			font-size: 13px;
			white-space: nowrap;
		}

		.nav-item a span {
			display: none;
		}

		.nav-item a i {
			font-size: 18px;
		}
	}
</style>

<script>
	document.addEventListener('DOMContentLoaded', function() {
		const menuToggle = document.querySelector('.navbar-toggle.expand-menu');
		const mobileMenu = document.getElementById('halim');
		const searchForm = document.getElementById('search-form');
		const searchFormToggle = document.querySelector('.navbar-toggle.expand-search-form');
		if (menuToggle && mobileMenu && searchForm && searchFormToggle) {
			searchFormToggle.addEventListener('click', function(e) {
				e.preventDefault();
				searchForm.classList.toggle('in');
			});
		}
		if (menuToggle && mobileMenu) {
			menuToggle.addEventListener('click', function(e) {
				e.preventDefault();

				// Toggle class 'in'
				mobileMenu.classList.toggle('in');

				// Toggle aria-expanded
				const isExpanded = mobileMenu.classList.contains('in');
				this.setAttribute('aria-expanded', isExpanded);

				// Toggle icon rotation (optional)
				const icon = this.querySelector('i');
				if (icon) {
					icon.style.transform = isExpanded ? 'rotate(90deg)' : 'rotate(0deg)';
					icon.style.transition = 'transform 0.3s ease';
				}
			});

			// Close menu when clicking outside
			document.addEventListener('click', function(e) {
				if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
					mobileMenu.classList.remove('in');
					menuToggle.setAttribute('aria-expanded', 'false');
					const icon = menuToggle.querySelector('i');
					if (icon) {
						icon.style.transform = 'rotate(0deg)';
					}
				}
			});
		}
	});
</script>