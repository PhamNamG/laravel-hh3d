<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- SEO Meta Tags --}}
    <title>@yield('title', 'Hhkungfu - Xem Phim Hoạt Hình 3D')</title>
    <meta name="description" content="@yield('meta_description', 'Xem phim hoạt hình 3D Trung Quốc miễn phí, chất lượng cao. Cập nhật phim mới nhất mỗi ngày.')">
    <meta name="keywords" content="@yield('meta_keywords', 'phim hoạt hình 3D, phim Trung Quốc, xem phim online, phim miễn phí')">
    <meta name="author" content="Hhkungfu">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="@yield('canonical_url', url()->current())">

    {{-- Open Graph / Facebook --}}
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="@yield('og_url', url()->current())">
    <meta property="og:title" content="@yield('og_title', 'Hhkungfu - Xem Phim Hoạt Hình 3D Trung Quốc')">
    <meta property="og:description" content="@yield('og_description', 'Xem phim hoạt hình 3D Trung Quốc miễn phí')">
    <meta property="og:image" content="@yield('og_image', asset('images/banner_kungfu.jpg'))">
    <meta property="og:site_name" content="Hhkungfu">

    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:url" content="@yield('og_url', url()->current())">
    <meta name="twitter:title" content="@yield('og_title', 'Hhkungfu - Xem Phim Hoạt Hình 3D Trung Quốc')">
    <meta name="twitter:description" content="@yield('og_description', 'Xem phim hoạt hình 3D Trung Quốc miễn phí')">
    <meta name="twitter:image" content="@yield('og_image', asset('images/banner_kungfu.jpg'))">

    {{-- Structured Data --}}
    @stack('structured_data')
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-77PR8S0BHM"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-77PR8S0BHM');
    </script>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    {{-- Vite Assets (All custom CSS/JS bundled here) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js','resources/css/app2.css'])
    
    @stack('styles')
</head>

<body class="home blog wp-custom-logo wp-embed-responsive wp-theme-videohazz halimthemes halimmovies">
    @include('components.header')
    @include('components.navbar')

    <main class="main-content mt-3">
        <div class="container">
            <div id="wrapper" class="row">
                {{-- Main Content Area --}}
                @yield('content')

                {{-- Sidebar - Tự động có ở tất cả trang --}}
                @if(!isset($hideSidebar) || !$hideSidebar)
                <aside id="sidebar" class="col-xs-12 col-sm-12 col-md-4">
                    <x-sidebar-popular :categories="$popularCategories" />
                </aside>
                @endif
            </div>
        </div>
    </main>

    @include('components.footer')

    <!-- Bootstrap JS Bundle (includes Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>