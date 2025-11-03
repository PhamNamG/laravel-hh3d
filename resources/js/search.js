document.addEventListener('DOMContentLoaded', function() {
  // Auto focus on search input
  const searchInput = document.querySelector('input[name="q"]');
  if (searchInput && !searchInput.value) {
      searchInput.focus();
  }

  // Handle form submit
  const searchForm = document.querySelector('.search-form');
  searchForm?.addEventListener('submit', function(e) {
      const query = searchInput.value.trim();
      if (!query) {
          e.preventDefault();
          alert('Vui lòng nhập từ khóa tìm kiếm');
          searchInput.focus();
      }
  });

  // Clear button (if exists)
  const clearBtn = document.querySelector('.clear-search');
  clearBtn?.addEventListener('click', function() {
      searchInput.value = '';
      searchInput.focus();
  });
});