@extends('layouts.app')

@section('title', 'Xem nhiều')

@section('meta_description', 'Xem phim hoạt hình 3D Trung Quốc mới nhất, chất lượng cao Full HD. Cập nhật liên tục phim hoạt hình Trung Quốc hay nhất, xem phim online miễn phí tại Hhkungfu.')

@section('meta_keywords', 'phim hoạt hình 3D, phim hoạt hình Trung Quốc, xem phim online, phim 3D vietsub, phim hoạt hình miễn phí, phim Trung Quốc mới nhất')

@section('canonical_url', url('/xem-nhieu'))

@section('og_type', 'website')

@section('content')
<div id="main-contents" class="col-xs-12 col-sm-12 col-md-8">
    <div id="wrapper">
        <section class="hot-movies">
            <div class="section-bar clearfix">
                <h3 class="section-title"><span>Xem nhiều</span></h3>
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

            {{-- Pagination --}}
            @if($totalPages > 1)
            <div class="text-center">
                <ul class="page-numbers">
                    {{-- Previous Button --}}
                    @if($hasPrevPage)
                        <li>
                            <a class="prev page-numbers" href="{{ url('/xem-nhieu') }}?page={{ $currentPage - 1 }}">
                                « Trước
                            </a>
                        </li>
                    @endif

                    {{-- Page Numbers Logic --}}
                    @php
                        $start = max(1, $currentPage - 2);
                        $end = min($totalPages, $currentPage + 2);
                    @endphp

                    {{-- First Page + Dots --}}
                    @if($start > 1)
                        <li>
                            <a class="page-numbers" href="{{ url('/xem-nhieu') }}?page=1">1</a>
                        </li>
                        @if($start > 2)
                            <li><span class="page-numbers dots">…</span></li>
                        @endif
                    @endif

                    {{-- Middle Pages (Current ± 2) --}}
                    @for($i = $start; $i <= $end; $i++)
                        <li>
                            @if($i == $currentPage)
                                <span aria-current="page" class="page-numbers current">{{ $i }}</span>
                            @else
                                <a class="page-numbers" href="{{ url('/xem-nhieu') }}?page={{ $i }}">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor

                    {{-- Dots + Last Page --}}
                    @if($end < $totalPages)
                        @if($end < $totalPages - 1)
                            <li><span class="page-numbers dots">…</span></li>
                        @endif
                        <li>
                            <a class="page-numbers" href="{{ url('/xem-nhieu') }}?page={{ $totalPages }}">{{ $totalPages }}</a>
                        </li>
                    @endif

                    {{-- Next Button --}}
                    @if($hasNextPage)
                        <li>
                                <a class="next page-numbers" href="{{ url('/xem-nhieu') }}?page={{ $currentPage + 1 }}">
                                Tiếp »
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            @endif
        </section>
    </div>
</div>
@endsection