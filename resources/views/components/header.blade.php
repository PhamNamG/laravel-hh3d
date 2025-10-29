<header class="header">
    <div class="container">
        <div class="header-content">
            <!-- Logo -->
            <div class="logo">
                <a href="{{ url('/') }}">
                    <img src="{{ asset('images/logo.jpg') }}" alt="HH3D Logo" class="logo-img">
                </a>
            </div>
            
            <!-- Search Bar -->
            <div class="search-bar">
                <form action="{{ url('/search') }}" method="GET">
                    <input 
                        type="text" 
                        name="q" 
                        placeholder="Tìm kiếm..." 
                        class="search-input"
                    >
                    <button type="submit" class="search-button">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.35-4.35"></path>
                        </svg>
                    </button>
                </form>
            </div>
            
            <!-- Auth Buttons -->
            <div class="auth-buttons">
                <button class="btn btn-icon btn-refresh" title="Làm mới">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.2"/>
                    </svg>
                </button>
                <button class="btn btn-icon btn-user">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </button>
                <button class="btn btn-icon btn-login">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4M10 17l5-5-5-5M13.8 12H3"/>
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Navigation Menu -->
        <nav class="nav-menu">
            <a href="{{ url('/') }}" class="nav-item active">Trang Chủ</a>
            <a href="{{ url('/the-loai') }}" class="nav-item">Thể Loại</a>
            <a href="{{ url('/lich-chieu') }}" class="nav-item">Lịch Chiếu</a>
            <a href="{{ url('/moi-cap-nhat') }}" class="nav-item">Mới Cập Nhật</a>
            <a href="{{ url('/xem-nhieu') }}" class="nav-item">Xem Nhiều</a>
            <a href="{{ url('/hoan-thanh') }}" class="nav-item">Hoàn Thành</a>
        </nav>
    </div>
</header>

