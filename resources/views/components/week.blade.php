<section class="hot-movies">
  <ul class="nav nav-pills nav-justified halim-schedule-block schedule">
    <li role="presentation" style="width: 14%;" data-id="chu-nhat"><a href="javascript:;">Sun<br>Chủ nhật</a></li>
    <li role="presentation" style="width: 14%;" data-id="thu-2"><a href="javascript:;">Mon<br>Thứ Hai</a></li>
    <li role="presentation" style="width: 14%;" data-id="thu-3"><a href="javascript:;">Tue<br>Thứ Ba</a></li>
    <li role="presentation" style="width: 14%;" data-id="thu-4"><a href="javascript:;">Wed<br>Thứ Tư</a></li>
    <li role="presentation" style="width: 14%;" data-id="thu-5"><a href="javascript:;">Thu<br>Thứ Năm</a></li>
    <li role="presentation" style="width: 14%;" data-id="thu-6"><a href="javascript:;">Fri<br>Thứ Sáu</a></li>
    <li role="presentation" style="width: 14%;" data-id="thu-7"><a href="javascript:;">Sat<br>Thứ Bảy</a></li>
  </ul>
  <ul class="nav nav-pills nav-justified halim-schedule-block-mobile mt-3">
    <li role="presentation" class="active" id="moviesLatest"> <a href="{{ url('/') }}" title="Trang chủ"> <span class="h-text">Mới Cập Nhật</span> </a></li>
    <li role="presentation" id="pull"> <a href="javascript:;">Lịch Chiếu</a></li>
  </ul>
  <ul class="nav nav-pills nav-justified halim-schedule-block-mobile menu schedule">
    <li role="presentation" class="day-tab" data-id="chu-nhat"><a href="javascript:;">Sun<br>Chủ nhật</a></li>
    <li role="presentation" class="day-tab" data-id="thu-2"><a href="javascript:;">Mon<br>Thứ Hai</a></li>
    <li role="presentation" class="day-tab" data-id="thu-3"><a href="javascript:;">Tue<br>Thứ Ba</a></li>
    <li role="presentation" class="day-tab" data-id="thu-4"><a href="javascript:;">Wed<br>Thứ Tư</a></li>
    <li role="presentation" class="day-tab" data-id="thu-5"><a href="javascript:;">Thu<br>Thứ Năm</a></li>
    <li role="presentation" class="day-tab" data-id="thu-6"><a href="javascript:;">Fri<br>Thứ Sáu</a></li>
    <li role="presentation" class="day-tab" data-id="thu-7"><a href="javascript:;">Sat<br>Thứ Bảy</a></li>
  </ul>
  <div id="-ajax-box" class="halim_box halim-schedule-box">
    <div class="halim-ajax-popular-post-loading hidden"></div>
    <div class="halim_box mt-2" id="week-schedule-content">
      {{-- Content will be loaded via JavaScript --}}
    </div>
    <div class="clearfix"></div>
  </div>
</section>

@push('scripts')
<script>
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
        <div style="text-align: center; padding: 40px; color: #f44336;">
          <i class="fa-solid fa-circle-exclamation" style="font-size: 48px;"></i>
          <p style="margin-top: 15px; font-size: 16px;">Lỗi tải dữ liệu</p>
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

    // Mobile menu toggle
    document.getElementById('pull')?.addEventListener('click', function() {
      const menu = document.querySelector('.halim-schedule-block-mobile.menu');
      if (menu) {
        menu.style.display = menu.style.display === 'none' ? 'flex' : 'none';
      }
    });

    // Load today's schedule
    loadSchedule(currentDayId);
  });
</script>
@endpush

</section>