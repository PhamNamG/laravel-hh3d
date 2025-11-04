{{-- Navigation Menu --}}
<nav id="main-menu" class="main-menu">
	<div class="container p-md-0 p-0">
	<div  class="navbar halim-navbar main-navigation p-0">
	<div class="navbar-header w-100">
			<button aria-label="menu" type="button" class="navbar-toggle pull-left expand-menu" data-toggle="collapse" data-target="#halim" aria-expanded="false">
				<i class="fa-solid fa-bars-staggered"></i>
			</button>
			<button aria-label="search" type="button" class="navbar-toggle pull-right expand-search-form" data-toggle="collapse" data-target="#search-form" aria-expanded="false">
				<i class="fa-solid fa-magnifying-glass"></i>
			</button>
		</div>
			<div id="halim" class="collapse navbar-collapse">
				<ul id="menu-menu" class="nav navbar-nav navbar-left d-block">
					@foreach($menuItems as $item)
					<li itemscope="itemscope"
						itemtype="https://www.schema.org/SiteNavigationElement"
						id="menu-item-{{ $item['slug'] }}"
						class="menu-item menu-item-type-custom menu-item-object-custom {{ request()->is($item['pattern']) ? 'active' : '' }} {{ isset($item['dropdown']) ? ' menu-item-has-children dropdown' : '' }}menu-item-{{ $item['slug'] }} nav-item">

						@if(isset($item['dropdown']))
						<a title="{{ $item['name'] }}"
							href="{{ $item['url'] }}"
							data-toggle="dropdown"
							aria-haspopup="true"
							aria-expanded="false"
							class="dropdown-toggle nav-link"
							id="menu-item-dropdown-{{ $item['slug'] }}">
							{{ $item['name'] }}
						</a>
						<ul class="dropdown-menu" aria-labelledby="menu-item-dropdown-{{ $item['slug'] }}" role="menu">
							@foreach($item['children'] as $child)
							<li itemscope="itemscope"
								itemtype="https://www.schema.org/SiteNavigationElement"
								id="menu-item-{{ $child['slug'] }}"
								class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-{{ $child['slug'] }} nav-item">
								<a title="{{ $child['name'] }}"
									href="{{ url('/category/' . ($child['slug'] ?? '#')) }}"
									class="dropdown-item">
									{{ $child['name'] }}
								</a>
							</li>
							@endforeach
						</ul>
						@else
						<a title="{{ $item['name'] }}"
							href="{{ url($item['url']) }}"
							class="nav-link">
							{{ $item['name'] }}
						</a>
						@endif
					</li>
					@endforeach
				</ul>
			</div>
		{{-- Desktop Menu --}}
		<!-- <ul class="nav-menu-list">
			@foreach($menuItems as $item)
			<li class="nav-item {{ request()->is($item['pattern']) ? 'active' : '' }}">
				<a href="{{ url($item['url']) }}">
					<span>{{ $item['name'] }}</span>
				</a>
			</li>
			@endforeach
		</ul> -->

		{{-- Mobile Menu --}}


		{{-- Search Form --}}

		<div class="navbar-collapse collapse" id="search-form" aria-expanded="true">
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
	</div>
</nav>

<script>

</script>