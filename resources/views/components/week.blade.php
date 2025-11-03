@php
$days = [
'Monday' => 'Thứ Hai',
'Tuesday' => 'Thứ Ba',
'Wednesday' => 'Thứ Tư',
'Thursday' => 'Thứ Năm',
'Friday' => 'Thứ Sáu',
'Saturday' => 'Thứ Bảy',
'Sunday' => 'Chủ Nhật',
];
$months = [
'January' => 'Tháng Một',
'February' => 'Tháng Hai',
'March' => 'Tháng Ba',
'April' => 'Tháng Tư',
'May' => 'Tháng Năm',
'June' => 'Tháng Sáu',
'July' => 'Tháng Bảy',
'August' => 'Tháng Tám',
'September' => 'Tháng Chín',
'October' => 'Tháng Mười',
'November' => 'Tháng Mười Một',
'December' => 'Tháng Mười Hai',
];
$today = $days[date('l')] . ', ' . date('d') . ' ' . $months[date('F')] . ' ' . date('Y');
@endphp

<section class="hot-movies mt-5">
  <div class="section-bar clearfix">
    <h3 class="section-title"><span>Lịch phát sóng</span></h3>
    <div class="mt-2">Hôm nay, {{ $today }}</div>
  </div>
  <ul class="nav nav-pills nav-justified halim-schedule-block schedule">
    <li role="presentation" style="width: 14.28%;" data-id="chu-nhat"><a href="javascript:;">Sun<br>Chủ nhật</a></li>
    <li role="presentation" style="width: 14.28%;" data-id="thu-2"><a href="javascript:;">Mon<br>Thứ Hai</a></li>
    <li role="presentation" style="width: 14.28%;" data-id="thu-3"><a href="javascript:;">Tue<br>Thứ Ba</a></li>
    <li role="presentation" style="width: 14.28%;" data-id="thu-4"><a href="javascript:;">Wed<br>Thứ Tư</a></li>
    <li role="presentation" style="width: 14.28%;" data-id="thu-5"><a href="javascript:;">Thu<br>Thứ Năm</a></li>
    <li role="presentation" style="width: 14.28%;" data-id="thu-6"><a href="javascript:;">Fri<br>Thứ Sáu</a></li>
    <li role="presentation" style="width: 14.28%;" data-id="thu-7"><a href="javascript:;">Sat<br>Thứ Bảy</a></li>
  </ul>

  <div id="-ajax-box" class="halim_box halim-schedule-box">
    <div class="halim-ajax-popular-post-loading hidden"></div>
    <div class="halim_box mt-2" id="week-schedule-content">
      {{-- Content will be loaded via JavaScript --}}
    </div>
    <div class="clearfix"></div>
  </div>
</section>