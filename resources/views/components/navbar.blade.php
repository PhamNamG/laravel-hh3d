{{-- Navigation Menu --}}
<nav id="main-menu" class="main-menu">
    <div class="container p-0">
        <ul class="nav-menu-list">
            <li class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                <a href="{{ url('/') }}">
                    <span>Trang Chủ</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('the-loai*') ? 'active' : '' }}">
                <a href="{{ url('/the-loai') }}">
                    <span>Thể Loại</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('lich-chieu*') ? 'active' : '' }}">
                <a href="{{ url('/lich-chieu') }}">
                    <span>Lịch Chiếu</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('moi-cap-nhat*') ? 'active' : '' }}">
                <a href="{{ url('/moi-cap-nhat') }}">
                    <span>Mới Cập Nhật</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('xem-nhieu*') ? 'active' : '' }}">
                <a href="{{ url('/xem-nhieu') }}">
                    <span>Xem Nhiều</span>
                </a>
            </li>
            <li class="nav-item {{ request()->is('hoan-thanh*') ? 'active' : '' }}">
                <a href="{{ url('/hoan-thanh') }}">
                    <span>Hoàn Thành</span>
                </a>
            </li>
        </ul>
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
    transition: all 0.3s 
ease;
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
