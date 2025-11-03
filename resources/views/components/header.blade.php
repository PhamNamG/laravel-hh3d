<header id="header">
    <div class="container">
        <div class="row" id="headwrap">
            <div class="col-md-3 col-sm-6 slogan">
                <p class="site-title" style="background-image: url(/images/logo.jpg)"> <a href="/" rel="home">HHKUNGFU - Phim Hoạt Hình Kungfu Trung Quốc Vietsub Độc Quyền</a></p>
            </div>
            <div class="col-md-5 col-sm-6 halim-search-form hidden-xs">
                <div class="header-nav">
                    <div class="col-xs-12">
                        <form id="search-form-pc" name="halimForm" role="search" method="GET" action="/search">
                            <div class="form-group">
                                <div class="input-group col-xs-12"> 
                                    <input id="search" type="text" name="q" value="{{ request('q') }}" class="form-control" data-toggle="tooltip" data-placement="bottom" data-original-title="Nhấn Enter để tìm kiếm" placeholder="Tìm kiếm phim..." autocomplete="off" required=""> 
                                    <i id="search-spinner" class="fa-solid fa-spinner hidden animate-spin"></i>
                                </div>
                            </div>
                        </form>
                        <ul class="ui-autocomplete ajax-results hidden" id="search-results"></ul>
                    </div>
                </div>
            </div>
            <div class="mobile-icon-menu col-md-3 d-none d-md-block">
                <div class="nav-items flex-wrap flex"> <a href="/lich-su" title="Lịch sử xem">
                        <div> <i class="fa-solid fa-clock-rotate-left"></i></div>
                    </a> <a href="/bookmark" title="Bookmark">
                        <div> <i class="fa-solid fa-bookmark"></i></div>
                    </a>
                    <div class="nav-menu-user"><a id="custom-open-login-modal" href="javascript:void(0)" title="Đăng Nhập">
                            <div><i class="fa-solid fa-right-to-bracket"></i></div>
                        </a></div>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Set API Base URL for JavaScript --}}
@push('scripts')
<script>
    window.API_BASE_URL = '{{ config("api.base_url") }}';
</script>
@endpush