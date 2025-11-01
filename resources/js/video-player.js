// ========================================
// SERVER VIDEO PLAYER SYSTEM WITH AD BLOCKER
// ========================================

// Global state
let adBlockEnabled = true;
let currentIframe = null;

// Thêm tham số chặn quảng cáo vào URL
function addAdBlockParams(url) {
    if (!adBlockEnabled) return url;
    
    try {
        const urlObj = new URL(url);
        
        // Các tham số để giảm quảng cáo
        const adBlockParams = {
            'autoplay': '1',
            'mute': '0',
            'controls': '1',
            'modestbranding': '1',
            'rel': '0',
            'showinfo': '0',
            'iv_load_policy': '3',
            'disablekb': '0',
            'fs': '1',
            'playsinline': '1',
            'widget': '1',
            'app': 'embed'
        };
        
        Object.entries(adBlockParams).forEach(([key, value]) => {
            urlObj.searchParams.set(key, value);
        });
        
        return urlObj.toString();
    } catch (e) {
        return url;
    }
}

// Chặn quảng cáo và popup trong iframe
function blockAdsAndPopups() {
    if (!currentIframe || !currentIframe.contentWindow) return;
    
    try {
        const iframeDoc = currentIframe.contentWindow.document;
        
        // Danh sách selector cho ads
        const adSelectors = [
            '[id*="ad"]',
            '[class*="ad"]',
            '[class*="advertisement"]',
            '[class*="popup"]',
            '[class*="overlay"]',
            '.ad-container',
            '.ads',
            '.advertisement',
            '.popup-overlay',
            '.modal-overlay'
        ];
        
        // Xóa các phần tử quảng cáo
        adSelectors.forEach(selector => {
            const elements = iframeDoc.querySelectorAll(selector);
            elements.forEach(el => {
                if (el instanceof HTMLElement) {
                    el.style.display = 'none';
                    el.remove();
                }
            });
        });
        
        // Chặn window.open (popup)
        currentIframe.contentWindow.open = () => null;
        
        // Chặn các sự kiện click không mong muốn
        iframeDoc.addEventListener('click', (e) => {
            const target = e.target;
            if (target.tagName === 'A' && target.getAttribute('target') === '_blank') {
                e.preventDefault();
                e.stopPropagation();
            }
        }, true);
        
    } catch (e) {
        // Cross-origin restrictions - không thể truy cập iframe content
        console.log('Cannot access iframe due to CORS policy');
    }
}

// Function để load video từ URL
function loadVideoPlayer(videoUrl, serverName) {
    const playerWrapper = document.getElementById('halim-player-wrapper');
    
    if (!videoUrl || videoUrl.trim() === '') {
        playerWrapper.innerHTML = `
            <div style="display: flex; align-items: center; justify-content: center; height: 400px; background: #000; color: #fff;">
                <p><i class="fa-solid fa-triangle-exclamation"></i> Server này chưa có video</p>
            </div>
        `;
        return;
    }
    
    // Thêm ad block params nếu enabled
    const finalUrl = addAdBlockParams(videoUrl);
    
    // Tất cả đều dùng iframe
    playerWrapper.innerHTML = `
        <iframe 
            id="video-iframe"
            class="metaframe rptss" 
            src="${finalUrl}" 
            referrerpolicy="unsafe-url" 
            scrolling="no" 
            frameborder="0" 
            width="100%" 
            height="400" 
            allowfullscreen="true" 
            webkitallowfullscreen="true" 
            mozallowfullscreen="true" 
            allow="autoplay; fullscreen"
            sandbox="allow-scripts allow-same-origin allow-presentation allow-forms"
        ></iframe>
    `;
    
    // Lưu reference và setup ad blocker
    currentIframe = document.getElementById('video-iframe');
    
    if (adBlockEnabled && currentIframe) {
        // Block ads khi iframe load xong
        currentIframe.addEventListener('load', () => {
            setTimeout(blockAdsAndPopups, 2000);
        });
        
        // Show ad block status
        showAdBlockStatus();
    }
}

// Hiển thị trạng thái ad block
function showAdBlockStatus() {
    const playerWrapper = document.getElementById('halim-player-wrapper');
    
    // Xóa status cũ nếu có
    const oldStatus = playerWrapper.querySelector('.ad-block-status');
    if (oldStatus) oldStatus.remove();
    
    if (adBlockEnabled) {
        playerWrapper.style.position = 'relative';
    }
}

// Toggle Ad Block
function toggleAdBlock() {
    adBlockEnabled = !adBlockEnabled;
    updateAdBlockButton();
    
    // Reload video với settings mới
    const activeButton = document.querySelector('.play-listsv.active');
    if (activeButton) {
        const serverUrl = activeButton.getAttribute('data-server-url');
        const serverName = activeButton.textContent.trim();
        loadVideoPlayer(serverUrl, serverName);
    }
}


// Generate server buttons dynamically
function generateServerButtons(allServers) {
    const container = document.getElementById('halim-ajax-list-server');
    
    // Filter chỉ lấy servers có video (không rỗng)
    const availableServers = allServers.filter(server => server.url && server.url.trim() !== '');
    
    if (availableServers.length === 0) {
        container.innerHTML = `
            <div style="padding: 20px; color: #ccc; text-align: center;">
                <i class="fa-solid fa-circle-exclamation"></i> Chưa có server nào khả dụng
            </div>
        `;
        return;
    }
    
    // Generate buttons HTML
    let buttonsHTML = '';
    availableServers.forEach((server, index) => {
        const isActive = index === 0 ? 'active' : '';
        const btnClass = server.type === 'thuyetminh' ? 'btn-vippro' : 'btn-viptik';
        
        buttonsHTML += `
            <span class="${btnClass} get-eps play-listsv box-shadow ${isActive}" 
                  data-server-id="${server.id}" 
                  data-server-url="${server.url}"
                  title="${server.name}">
                ${server.icon} ${server.name}
            </span>
        `;
    });
    
    container.innerHTML = buttonsHTML;
    
    // Auto-load first server
    if (availableServers.length > 0) {
        const firstServer = availableServers[0];
        loadVideoPlayer(firstServer.url, firstServer.name);
    }
}

// Initialize video player
function initVideoPlayer(episodeData) {
    // Định nghĩa tất cả servers theo thứ tự ưu tiên
    const allServers = [
        {
            id: 'voiceOverLink',
            name: 'Thuyết Minh 1',
            url: episodeData.voiceOverLink || '',
            type: 'thuyetminh',
            icon: '🎤',
            priority: 1
        },
        {
            id: 'dailyMotionServer',
            name: 'Vietsub 1',
            url: episodeData.dailyMotionServer || '',
            type: 'vietsub',
            icon: '📺',
            priority: 2
        },
        {
            id: 'server2',
            name: 'Server 2',
            url: episodeData.server2 || '',
            type: 'vietsub',
            icon: '🎬',
            priority: 3
        },
        {
            id: 'link',
            name: 'Server 3',
            url: episodeData.link || '',
            type: 'vietsub',
            icon: '🎞️',
            priority: 4
        },
        {
            id: 'voiceOverLink2',
            name: 'Thuyết Minh 2',
            url: episodeData.voiceOverLink2 || '',
            type: 'thuyetminh',
            icon: '🎙️',
            priority: 5
        }
    ];
    
    // Generate buttons
    generateServerButtons(allServers);
    
    // Handle button clicks
    const container = document.getElementById('halim-ajax-list-server');
    container.addEventListener('click', function(e) {
        const button = e.target.closest('.play-listsv');
        if (!button) return;
        
        // Remove active from all
        container.querySelectorAll('.play-listsv').forEach(btn => {
            btn.classList.remove('active');
        });
        
        // Add active to clicked
        button.classList.add('active');
        
        // Load video
        const serverUrl = button.getAttribute('data-server-url');
        const serverName = button.textContent.trim();
        
        loadVideoPlayer(serverUrl, serverName);
    });
}

// Auto-init when DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        if (window.episodeData) {
            initVideoPlayer(window.episodeData);
        }
    });
} else {
    if (window.episodeData) {
        initVideoPlayer(window.episodeData);
    }
}

