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
            <div class="mobile-icon-menu col-md-3">
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('search');
    const searchForm = document.getElementById('search-form-pc');
    const searchResults = document.getElementById('search-results');
    const searchSpinner = document.getElementById('search-spinner');
    
    let searchTimeout = null;
    const API_BASE_URL = '{{ config("api.base_url") }}';

    // Autocomplete search
    async function performSearch(query) {
        if (query.length < 2) {
            hideResults();
            return;
        }

        showLoading();

        try {
            const url = `${API_BASE_URL}/categorys/search?value=${encodeURIComponent(query)}`;
            const response = await fetch(url);
            const result = await response.json();

            hideLoading();

            if (result.success !== false && result.data) {
                displayResults(result.data);
            } else {
                displayResults([]);
            }
        } catch (error) {
            console.error('Search error:', error);
            hideLoading();
            displayResults([]);
        }
    }

    // Display autocomplete results
    function displayResults(categories) {
        searchResults.innerHTML = '';

        if (categories.length === 0) {
            searchResults.innerHTML = `
                <li class="search-result-item" style="padding: 15px; text-align: center; color: #999;">
                    Không tìm thấy kết quả
                </li>
            `;
            searchResults.classList.remove('hidden');
            return;
        }

        categories.forEach(category => {
            const li = document.createElement('li');
            li.className = 'search-result-item';
            li.innerHTML = `
                <a href="{{ url('/phim') }}/${category.slug}" style="display: flex; padding: 10px; align-items: center; gap: 10px; border-bottom: 1px solid #eee;">
                    <img src="${category.linkImg || 'https://via.placeholder.com/50x70?text=No+Image'}" 
                         alt="${category.name}" 
                         style="width: 50px; height: 70px; object-fit: cover; border-radius: 4px;">
                    <div style="flex: 1;">
                        <div style="font-weight: bold; color: #333;">${category.name}</div>
                        ${category.anotherName ? `<div style="font-size: 12px; color: #999;">${category.anotherName}</div>` : ''}
                        <div style="font-size: 11px; color: #666; margin-top: 4px;">
                            ${category.quality || 'HD'} - ${category.sumSeri ? 'Tập ' + category.sumSeri : 'N/A'}
                        </div>
                    </div>
                </a>
            `;
            searchResults.appendChild(li);
        });

        searchResults.classList.remove('hidden');
    }

    // Show/hide functions
    function showLoading() {
        searchSpinner.classList.remove('hidden');
    }

    function hideLoading() {
        searchSpinner.classList.add('hidden');
    }

    function hideResults() {
        searchResults.classList.add('hidden');
    }

    // Input event listener (debounced)
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();

        // Clear previous timeout
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }

        // Debounce: wait 500ms after user stops typing
        searchTimeout = setTimeout(() => {
            performSearch(query);
        }, 500);
    });

    // Handle form submit
    searchForm.addEventListener('submit', function(e) {
        const query = searchInput.value.trim();
        if (!query) {
            e.preventDefault();
            alert('Vui lòng nhập từ khóa tìm kiếm');
        }
        // Form will submit normally to /search?q={query}
    });

    // Handle focus
    searchInput.addEventListener('focus', function() {
        const query = this.value.trim();
        if (query.length >= 2 && searchResults.children.length > 0) {
            searchResults.classList.remove('hidden');
        }
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            hideResults();
        }
    });

    // Handle keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        const items = searchResults.querySelectorAll('.search-result-item a');
        const activeItem = searchResults.querySelector('.search-result-item.active');
        let currentIndex = -1;

        if (activeItem) {
            currentIndex = Array.from(items).indexOf(activeItem.parentElement);
        }

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (currentIndex < items.length - 1) {
                if (activeItem) activeItem.parentElement.classList.remove('active');
                items[currentIndex + 1].parentElement.classList.add('active');
            }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (currentIndex > 0) {
                if (activeItem) activeItem.parentElement.classList.remove('active');
                items[currentIndex - 1].parentElement.classList.add('active');
            }
        } else if (e.key === 'Enter' && activeItem) {
            e.preventDefault();
            activeItem.querySelector('a').click();
        }
    });
});
</script>

<style>
.ui-autocomplete.ajax-results {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: #fff;
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    margin-top: 5px;
}

.ui-autocomplete.ajax-results.hidden {
    display: none;
}

.search-result-item {
    list-style: none;
}

.search-result-item a {
    text-decoration: none;
    transition: background 0.2s;
}

.search-result-item a:hover,
.search-result-item.active a {
    background: #f5f5f5;
}

#search-spinner {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #999;
}

#search-spinner.hidden {
    display: none;
}

@keyframes spin {
    to { transform: translateY(-50%) rotate(360deg); }
}

.animate-spin {
    animation: spin 1s linear infinite;
}

.header-nav {
    position: relative;
}
</style>
@endpush