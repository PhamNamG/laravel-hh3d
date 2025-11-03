document.addEventListener('DOMContentLoaded', function () {
  const menuToggle = document.querySelector('.navbar-toggle.expand-menu');
  const mobileMenu = document.getElementById('halim');
  const searchForm = document.getElementById('search-form');
  const searchFormToggle = document.querySelector('.navbar-toggle.expand-search-form');
  if (menuToggle && mobileMenu && searchForm && searchFormToggle) {
    searchFormToggle.addEventListener('click', function (e) {
      e.preventDefault();
      searchForm.classList.toggle('in');
    });
  }
  if (menuToggle && mobileMenu) {
    menuToggle.addEventListener('click', function (e) {
      e.preventDefault();

      // Toggle class 'in'
      mobileMenu.classList.toggle('in');

      // Toggle aria-expanded
      const isExpanded = mobileMenu.classList.contains('in');
      this.setAttribute('aria-expanded', isExpanded);

      // Toggle icon rotation (optional)
      const icon = this.querySelector('i');
      if (icon) {
        icon.style.transform = isExpanded ? 'rotate(90deg)' : 'rotate(0deg)';
        icon.style.transition = 'transform 0.3s ease';
      }
    });

    // Close menu when clicking outside
    document.addEventListener('click', function (e) {
      if (!menuToggle.contains(e.target) && !mobileMenu.contains(e.target)) {
        mobileMenu.classList.remove('in');
        menuToggle.setAttribute('aria-expanded', 'false');
        const icon = menuToggle.querySelector('i');
        if (icon) {
          icon.style.transform = 'rotate(0deg)';
        }
      }
    });
  }
});