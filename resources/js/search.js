document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.querySelector('input[name="q"]');

  const searchForm = document.querySelector('.search-form');
  searchForm?.addEventListener('submit', function(e) {
      const query = searchInput.value.trim();
      if (!query) {
          e.preventDefault();
          alert('Vui lòng nhập từ khóa tìm kiếm');
          searchInput.focus();
      }
  });

  const clearBtn = document.querySelector('.clear-search');
  clearBtn?.addEventListener('click', function() {
      searchInput.value = '';
      searchInput.focus();
  });
});