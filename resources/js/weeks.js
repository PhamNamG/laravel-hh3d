document.addEventListener('DOMContentLoaded', function() {
  // Mapping
  const container = document.getElementById('week-schedule-content');
  const dayIdToName = {
    'chu-nhat': 'Chủ nhật',
    'thu-2': 'Thứ 2',
    'thu-3': 'Thứ 3',
    'thu-4': 'Thứ 4',
    'thu-5': 'Thứ 5',
    'thu-6': 'Thứ 6',
    'thu-7': 'Thứ 7'
  };

  // Get today
  function getTodayId() {
    const dayMap = {
      0: 'chu-nhat',
      1: 'thu-2',
      2: 'thu-3',
      3: 'thu-4',
      4: 'thu-5',
      5: 'thu-6',
      6: 'thu-7'
    };
    return dayMap[new Date().getDay()];
  }

  let currentDayId = getTodayId();

  // Set active tab
  function setActiveTab(dayId) {
    document.querySelectorAll('li[data-id]').forEach(tab => {
      tab.classList.remove('active');
      if (tab.getAttribute('data-id') === dayId) {
        tab.classList.add('active');
      }
    });
  }

  // Render movie card
  function renderMovieCard(anime) {
    const quality = anime.quality || 'FULL HD';
    let episode = anime.sumSeri || 'N/A';

    // Get latest episode
    if (anime.products && Array.isArray(anime.products) && anime.products.length > 0) {
      const latestEpisode = anime.products[0].seri;
      if (latestEpisode) {
        episode = `${latestEpisode}/${anime.sumSeri || '?'}`;
      }
    }

    const posterUrl = anime.linkImg || 'https://via.placeholder.com/300x400?text=No+Image';
    const slug = anime.slug || '#';
    const name = anime.name || 'Phim';
    const anotherName = anime.anotherName || '';

    return `
    <article class="col-md-3 col-sm-3 col-xs-6 thumb grid-item post-${anime._id || ''}">
      <div class="halim-item">
        <a class="halim-thumb" href="{{ url('/phim') }}/${slug}" title="${name}">
          <figure>
            <img width="300" height="400" 
                 src="${posterUrl}" 
                 alt="${name}" 
                 class="wp-post-image img-responsive">
          </figure>
          <span class="status">${quality}</span>
          <span class="episode">Tập ${episode}</span>
          <div class="halim-post-title-box">
            <div class="halim-post-title">
              <h2 class="entry-title">${name}</h2>
              ${anotherName ? `<p class="original_title">${anotherName}</p>` : ''}
            </div>
          </div>
        </a>
      </div>
    </article>
  `;
  }

  // Load schedule
  async function loadSchedule(dayId) {
    currentDayId = dayId;
    setActiveTab(dayId);

    const dayName = dayIdToName[dayId];

    // Show loading
    container.innerHTML = '<div style="text-align: center; padding: 40px; color: #999;"><i class="fa-solid fa-spinner fa-spin"></i> Đang tải...</div>';

    try {
      const response = await fetch(`/api/week?w=${encodeURIComponent(dayName)}`);
      const result = await response.json();

      if (result.success && result.data && result.data.content) {
        const content = result.data.content;
        if (content.length === 0) {
          container.innerHTML = `
          <div style="text-align: center; padding: 40px; color: #ccc;">
            <i class="fa-solid fa-calendar-xmark" style="font-size: 48px; opacity: 0.5;"></i>
            <p style="margin-top: 15px; font-size: 16px;">Không có phim nào trong ngày này</p>
          </div>
        `;
        } else {
          container.innerHTML = content.map(anime => renderMovieCard(anime)).join('');
        }
      } else {
        throw new Error('Invalid response');
      }

    } catch (error) {
      console.error('Error loading schedule:', error);
      container.innerHTML = `
      <div class="item">
              <p style="color: #ccc; text-align: center; padding: 20px;">
                  Chưa có dữ liệu
              </p>
          </div>
    `;
    }
  }

  // Tab click
  document.querySelectorAll('li[data-id]').forEach(tab => {
    tab.addEventListener('click', function() {
      const dayId = this.getAttribute('data-id');
      loadSchedule(dayId);
    });
  });

  // Load today's schedule
  loadSchedule(currentDayId);
});