/**
 * Search Autocomplete Component
 * Handles search input with debounced API calls and keyboard navigation
 */

document.addEventListener('DOMContentLoaded', function () {
  const searchInput = document.getElementById('search');
  const searchForm = document.getElementById('search-form-pc');
  const searchResults = document.getElementById('search-results');
  const searchSpinner = document.getElementById('search-spinner');

  if (!searchInput || !searchForm || !searchResults) {
    return; // Elements not found on this page
  }

  let searchTimeout = null;
  const API_BASE_URL = window.API_BASE_URL || 'https://hh3d.id.vn/api';

  /**
   * Perform search API call
   */
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
      console.log(result)
      if (result.length > 0) {
        displayResults(result);
      } else {
        displayResults([]);
      }
    } catch (error) {
      console.error('Search error:', error);
      hideLoading();
      displayResults([]);
    }
  }

  /**
   * Display autocomplete results
   */
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

    const baseUrl = window.location.origin;

    categories.forEach(category => {
      const li = document.createElement('li');
      li.className = 'exact_result';
      li.innerHTML = `
                <a title="${category.name}" href="${baseUrl}/phim/${category.slug}">
                   <div class="halim_list_item">
                            <div class="image">
                            <img width="125" height="200" src="${category.linkImg}" alt="${category.name}" 
                              class="wp-post-image img-responsive ">
                            </div>
                            <span class="label">${category.name}</span>
                            <span class="enName">${category.anotherName ?? ''}</span>
                        </div>
                </a>
            `;
      searchResults.appendChild(li);
    });

    searchResults.classList.remove('hidden');
  }

  /**
   * Show/hide loading spinner
   */
  function showLoading() {
    searchSpinner.classList.remove('hidden');
  }

  function hideLoading() {
    searchSpinner.classList.add('hidden');
  }

  function hideResults() {
    searchResults.classList.add('hidden');
  }

  /**
   * Input event listener (debounced)
   */
  searchInput.addEventListener('input', function (e) {
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

  /**
   * Handle form submit
   */
  searchForm.addEventListener('submit', function (e) {
    const query = searchInput.value.trim();
    if (!query) {
      e.preventDefault();
      alert('Vui lòng nhập từ khóa tìm kiếm');
    }
    // Form will submit normally to /search?q={query}
  });

  /**
   * Handle focus - show results if available
   */
  searchInput.addEventListener('focus', function () {
    const query = this.value.trim();
    if (query.length >= 2 && searchResults.children.length > 0) {
      searchResults.classList.remove('hidden');
    }
  });

  /**
   * Hide results when clicking outside
   */
  document.addEventListener('click', function (e) {
    if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
      hideResults();
    }
  });

  /**
   * Handle keyboard navigation (Arrow Up/Down, Enter)
   */
  searchInput.addEventListener('keydown', function (e) {
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

