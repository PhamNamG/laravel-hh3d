@extends('layouts.app')

@section('title', ($phim['name'] ?? 'Phim') . ' - ' . ($phim['anotherName'] ?? '') . ' - Xem Phim Online Vietsub - Hhkungfu')

@section('meta_description')
{{ ($phim['name'] ?? 'Phim') }} {{ isset($phim['anotherName']) ? '(' . $phim['anotherName'] . ')' : '' }} - {{ isset($phim['des']) ? Str::limit($phim['des'], 150) : 'Xem phim hoạt hình 3D Trung Quốc miễn phí' }}. {{ isset($phim['quality']) ? 'Chất lượng ' . strtoupper($phim['quality']) : '' }}{{ isset($phim['lang']) ? ', ' . $phim['lang'] : '' }}.
@endsection

@section('meta_keywords')
{{ $phim['name'] ?? 'phim' }}, {{ $phim['anotherName'] ?? '' }}, phim hoạt hình 3D, {{ isset($phim['tags']) ? implode(', ', array_column($phim['tags'], 'name')) : '' }}, xem phim online, {{ $phim['lang'] ?? 'vietsub' }}
@endsection

@section('canonical_url', url('/phim/' . ($phim['slug'] ?? '')))

@section('og_type', 'video.movie')
@section('og_title', ($phim['name'] ?? 'Phim') . ' - ' . ($phim['anotherName'] ?? ''))
@section('og_description', isset($phim['des']) ? Str::limit($phim['des'], 200) : 'Xem phim hoạt hình 3D Trung Quốc miễn phí')
@section('og_image', $phim['linkImg'] ?? asset('images/Logo.jpg'))
@section('og_url', url('/phim/' . ($phim['slug'] ?? '')))

@push('structured_data')
<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Movie",
		"name": "{{ $phim['name'] ?? 'Phim' }}",
		"alternateName": "{{ $phim['anotherName'] ?? '' }}",
		"description": "{{ isset($phim['des']) ? Str::limit($phim['des'], 200) : '' }}",
		"image": "{{ $phim['linkImg'] ?? '' }}",
		"url": "{{ url('/phim/' . ($phim['slug'] ?? '')) }}",
		@if(isset($phim['year']))
		"datePublished": "{{ $phim['year'] }}",
		@endif
		@if(isset($phim['rating']) && count($phim['rating']) > 0)
		"aggregateRating": {
			"@type": "AggregateRating",
			"ratingValue": "{{ number_format(array_sum($phim['rating']) / count($phim['rating']), 1) }}",
			"ratingCount": "{{ count($phim['rating']) }}",
			"bestRating": "5",
			"worstRating": "1"
		},
		@endif "genre": [
			@if(isset($phim['tags']))
			@foreach($phim['tags'] as $index => $tag)
			"{{ $tag['name'] ?? '' }}"
			@if($index < count($phim['tags']) - 1), @endif
			@endforeach
			@endif
		],
		@if(isset($phim['time']))
		"duration": "PT{{ $phim['time'] }}",
		@endif "inLanguage": "{{ $phim['lang'] ?? 'vi' }}",
		"countryOfOrigin": {
			"@type": "Country",
			"name": "{{ $phim['country'] ?? 'Trung Quốc' }}"
		}
	}
</script>
@endpush

@section('content')
{{-- Movie Not Found --}}
@if(isset($notFound) && $notFound)
<x-not-found
	icon="fa-film"
	title="Không tìm thấy phim"
	message="Rất tiếc, phim bạn đang tìm kiếm không tồn tại hoặc đã bị xóa." />
@else
<main id="main-contents" class="col-xs-12 col-sm-12 col-md-8 single-movie p-3" data-id="{{ $phim['_id'] ?? '' }}">
	<section id="content">
		<div class="clearfix wrap-content">
			<div class="halim-movie-wrapper tpl-2">
				{{-- Movie Info Section --}}
				<div class="movie_info col-xs-12">
					{{-- Poster Column --}}
					<div class="movie-poster col-md-3">
						<img
							width="300"
							height="450"
							src="{{ $phim['linkImg'] ?? 'https://via.placeholder.com/300x450?text=No+Image' }}"
							alt="{{ $phim['name'] ?? 'Phim' }}"
							class="wp-post-image img-responsive">

						{{-- Watch Button --}}
						@if(count($episodes) > 0)
						<div class="halim-watch-box">
							<a href="{{ url('/xem/' . $episodes[0]['slug']) }}"
								class="btn btn-sm btn-danger watch-movie visible-xs-blockx">
								<i class="fa-solid fa-play"></i> Xem phim
							</a>
						</div>
						@endif
					</div>

					{{-- Film Info Column --}}
					<div class="film-poster col-md-8">
						<div class="movie-detail">
							<h1 class="entry-title m-0">{{ $phim['name'] ?? 'Không có tên' }}</h1>

							@if(isset($phim['anotherName']) && $phim['anotherName'])
							<h2 class="org_title">{{ $phim['anotherName'] }}</h2>
							@endif

							{{-- Thể Loại --}}
							@if(isset($phim['type']))
							<div class="list_cate">
								<div>Thể Loại:</div>
								<div>
									@foreach(explode(',', $phim['type']) as $type)
									<a href="#" rel="tag">{{ trim($type) }}</a>
									@endforeach
								</div>
							</div>
							@endif

							{{-- Tập mới nhất --}}
							@if(count($episodes) > 0)
							<div class="hh3d-new-ep">
								<div>Tập mới nhất:</div>
								<div>
									<span class="new-ep">
										{{ is_numeric($episodes[0]['seri']) ? 'Tập ' . $episodes[0]['seri'] : $episodes[0]['seri'] }}
										@if(isset($phim['sumSeri']))
										/{{ $phim['sumSeri'] }}
										@endif
										[{{ strtoupper($phim['quality'] ?? 'FHD') }}]
									</span>
								</div>
							</div>
							@endif

							{{-- Tình trạng --}}
							@if(isset($phim['status']))
							<div class="hh3d-info ">
								<div>Tình trạng:</div>
								<div>{{ $phim['status'] === 'completed' ? 'Hoàn thành' : 'Đang chiếu' }}</div>
							</div>
							@endif

							{{-- Lượt xem --}}
							<div class="hh3d-info">
								<div>Lượt xem:</div>
								<span class="new-ep">N/A</span>
							</div>

							{{-- Đánh Giá --}}
							@if(isset($phim['rating']) && count($phim['rating']) > 0)
							@php
							$avgRating = array_sum($phim['rating']) / count($phim['rating']);
							$ratingCount = count($phim['rating']);
							$ratingWidth = ($avgRating / 5) * 100;
							@endphp
							<div class="hh3d-rate">
								<div>Đánh Giá:</div>
								<div class="ratings_wrapper single-info">
									<div class="kk-star-ratings">
										<div class="kksr-stars">
											<div class="kksr-stars-inactive">
												@for($i = 1; $i <= 5; $i++)
													<div class="kksr-star" data-star="{{ $i }}" style="padding-right: 5px">
													<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
											</div>
											@endfor
										</div>
										<div class="kksr-stars-active">
											@for($i = 1; $i <= 5; $i++)
												<div class="kksr-star" style="padding-right: 5px">
												<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										@endfor
									</div>
								</div>
								<div class="kksr-legend" style="font-size: 12.8px;">
									{{ number_format($avgRating, 1) }}/5 - ({{ $ratingCount }} bình chọn)
								</div>
							</div>
						</div>
					</div>
					@endif

					@if(isset($phim['tags']))
					<div class="hh3d-info">
						<div>Thể loại:</div>
						<div>
							@foreach($phim['tags'] as $tag)
							<a href="{{ url('/tag/' . ($tag['slug'] ?? '')) }}" rel="tag">
								{{ $tag['name'] ?? '' }}
							</a>
							@endforeach
						</div>
					</div>
					@endif

					{{-- Năm phát hành --}}
					@if(isset($phim['year']))
					<div class="hh3d-info">
						<div>Năm phát hành:</div>
						<div>{{ $phim['year'] }}</div>
					</div>
					@endif

					{{-- Thời lượng --}}
					@if(isset($phim['time']))
					<div class="hh3d-info">
						<div>Thời lượng: </div>
						<div>{{ $phim['time'] }}</div>
					</div>
					@endif

					<div class="hh3d-rate">
						<div>Đánh Giá:</div>
						<div class="ratings_wrapper single-info">
							<div class="kk-star-ratings kksr-template">
								<div class="kksr-stars">
									<div class="kksr-stars-inactive">
										<div class="kksr-star" data-star="1" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" data-star="2" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" data-star="3" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" data-star="4" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" data-star="5" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
									</div>
									<div class="kksr-stars-active" style="width: 102.5px;">
										<div class="kksr-star" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
										<div class="kksr-star" style="padding-right: 5px">
											<div class="kksr-icon" style="width: 16px; height: 16px;"></div>
										</div>
									</div>
								</div>
								<div class="kksr-legend" style="font-size: 12.8px;"> 5/5 - (2 bình chọn)</div>
							</div>
						</div>
					</div>

					{{-- Ngôn ngữ --}}
					@if(isset($phim['lang']))
					<div class="hh3d-info">
						<div>Chất lượng: </div>
						<div>
							{{
        $phim['lang'] == 'Vietsub' ? 'Vietsub' : 
        ($phim['lang'] == 'ThuyetMinh' ? 'Thuyết Minh' : 
        ($phim['lang'] == 'ThuyetMinh-Vietsub' ? 'Thuyết Minh - Vietsub' : 'Unknown'))
    }}
						</div>

					</div>
					@endif
					<div class="hh3d-info align-items-center">
						<div>Cộng đồng: </div>
						<span class="social-buttons">
							<a href="https://zalo.me/g/hhkungfu" target="_blank" rel="noopener noreferrer" class="btn-social btn-zalo">
								<i class="fa-solid fa-comments"></i> Zalo Group
							</a>
							<a href="https://t.me/hhkungfu" target="_blank" rel="noopener noreferrer" class="btn-social btn-telegram">
								<i class="fa-brands fa-telegram"></i> Telegram
							</a>
						</span>
					</div>

				</div>
			</div>
		</div>
		</div>

		{{-- Episodes Section --}}
		<div id="collapseEps" class="halim-movie-wrapper tpl-2 info-movie">
			<div id="halim-list-server" class="list-eps-ajax">
				{{-- Vietsub Server --}}
				<div class="halim-server show_all_eps">
					<span class="halim-server-name">
						<i class="fa-solid fa-database"></i>#Vietsub
					</span>
					<ul id="listsv-1" class="halim-list-eps">
						@forelse($episodes as $episode)
						@if(isset($phim['isMovie']) && $phim['isMovie'] == 'drama')
						<li class="halim-episode halim-episode-1-tap-{{ $episode['seri'] ?? '' }} col-xs-3 col-sm-2 col-lg-1">
							<a
								data-post-id="{{ $phim['_id'] ?? '' }}"
								data-ep="tap-{{ $episode['seri'] ?? '' }}"
								data-sv="1"
								href="{{ url('/xem/' . $episode['slug']) }}"
								title="Tập {{ $episode['seri'] ?? '' }}">
								<span class="box-shadow halim-btn">
									{{ is_numeric($episode['seri']) ? 'Tập ' . $episode['seri'] : $episode['seri'] }}
								</span>
							</a>
						</li>
						@else
						<li class="halim-episode halim-episode-1-tap-{{ $episode['seri'] ?? '' }} col-xs-3 col-sm-2 col-lg-1">
							<a
								data-post-id="{{ $phim['_id'] ?? '' }}"
								href="{{ url('/xem/' . $episode['slug']) }}"
								title="Tập {{ $episode['seri'] ?? '' }}">
								<span class="box-shadow halim-btn">
									Full
								</span>
							</a>
						</li>
						@endif
						@empty
						<li class="col-md-12">
							<p style="color: #ccc; padding: 20px; text-align: center;">
								Chưa có tập phim nào
							</p>
						</li>
						@endforelse
					</ul>
					<div class="clearfix"></div>
				</div>

				{{-- Thuyết Minh Server (if available) --}}
				@if(isset($phim['thuyetMinh']) && $phim['thuyetMinh'])
				<div class="halim-server show_all_eps">
					<span class="halim-server-name">
						<i class="fa-solid fa-database"></i>#Thuyết Minh:
					</span>
					<ul id="listsv-2" class="halim-list-eps">
						@forelse($episodes as $episode)
						@if(isset($phim['isMovie']) && $phim['isMovie'] == 'drama')
						<li class="halim-episode halim-episode-1-tap-{{ $episode['seri'] ?? '' }} col-xs-3 col-sm-2 col-lg-1">
							<a
								data-post-id="{{ $phim['_id'] ?? '' }}"
								data-ep="tap-{{ $episode['seri'] ?? '' }}"
								data-sv="1"
								href="{{ url('/xem/' . $episode['slug']) }}"
								title="Tập {{ $episode['seri'] ?? '' }}">
								<span class="box-shadow halim-btn">
									{{ is_numeric($episode['seri']) ? 'Tập ' . $episode['seri'] : $episode['seri'] }}
								</span>
							</a>
						</li>
						@else
						<li class="halim-episode halim-episode-1-tap-{{ $episode['seri'] ?? '' }} col-xs-3 col-sm-2 col-lg-1">
							<a
								data-post-id="{{ $phim['_id'] ?? '' }}"
								href="{{ url('/xem/' . $episode['slug']) }}"
								title="Tập {{ $episode['seri'] ?? '' }}">
								<span class="box-shadow halim-btn">
									Full
								</span>
							</a>
						</li>
						@endif
						@empty
						<li class="col-md-12">
							<p style="color: #ccc; padding: 20px; text-align: center;">
								Chưa có tập phim nào
							</p>
						</li>
						@endforelse
					</ul>
					<div class="clearfix"></div>
				</div>
				@endif
			</div>

			<div class="clearfix"></div>

			{{-- Schedule Info --}}
			@if(isset($phim['week']) && count($phim['week']) > 0)
			<div class="halim_showtime_movies">
				Phim chiếu 1 tập vào {{ implode(', ', array_map(fn($w) => $w['name'] ?? '', $phim['week'])) }}
			</div>
			@endif

			{{-- Movie Description --}}
			@if(isset($phim['des']) && $phim['des'])
			<div class="entry-content htmlwrap clearfix">
				<h3 class="section-title"><span>Nội dung phim</span></h3>
				<div class="video-item halim-entry-box">
					<article>
						<p>{{ $phim['des'] }}</p>
					</article>
				</div>
			</div>
			@endif

			<x-comment-section />

			<div class="tags-list mt-5">
				<a href="/" rel="tag">
					hhkungffu
				</a>
				<a href="/" rel="tag">
					hhkungffu site
				</a>
				<a href="/" rel="tag">
					#hoạt hình kungffu
				</a>
			</div>
			
		</div>
		</div>
	</section>
</main>
@endif
@endsection