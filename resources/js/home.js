let currentPage = 1;

async function loadMoreCategories() {
  currentPage++;
  const button = event.target;
  button.disabled = true;
  button.textContent = 'Đang tải...';

  try {
    // Có thể implement AJAX load more ở đây
    window.location.href = `/?page=${currentPage}`;
  } catch (error) {
    console.error('Error loading more:', error);
    button.disabled = false;
    button.textContent = 'Xem Thêm';
  }
}

// Refresh button functionality
document.querySelector('.btn-refresh')?.addEventListener('click', () => {
  location.reload();
});

// Search form enhancement
const searchInput = document.querySelector('.search-input');
searchInput?.addEventListener('keypress', (e) => {
  if (e.key === 'Enter' && !searchInput.value.trim()) {
    e.preventDefault();
    alert('Vui lòng nhập từ khóa tìm kiếm');
  }
});