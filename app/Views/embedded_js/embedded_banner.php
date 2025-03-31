(function () {
    // 1. Configurable Settings (Modify Here)
    const config = {
        base_url: '<?= $baseUrl ?>', // Dynamic API base URL
        pseudo_id: '<?= $pseudoId ?>', // Unique Identifier
        position: 'top',  // Default position: 'top', 'bottom', 'left', 'right', 'center'
        width: '100%',    // Banner width
        height: '120px',  // Banner height
        zIndex: '9999',   // Ensure it's above content
        borderRadius: '0px',
        boxShadow: '0px 4px 10px rgba(0,0,0,0.2)',
        allowOverlap: false,
        refreshInterval: 6000, // Dynamic Refresh Interval (Default: 60 sec)
        errorRetryInterval: 5000 // Retry if request fails (Default: 5 sec)
    };

    // Merge with External Config (If provided by third party)
    if (window.bannerConfig) {
        Object.assign(config, window.bannerConfig);
        console.log('Custom Config Applied:', config);
    }

    // 2. Dynamic Fetch URL
    const API_URL = `${config.base_url}api/banner/${config.pseudo_id}`;

    // 3. Create Banner Container
    function createBannerContainer() {
        let container = document.getElementById('dynamic-banner-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'dynamic-banner-container';
            Object.assign(container.style, {
                width: config.width,
                height: config.height,
                boxShadow: config.boxShadow,
                borderRadius: config.borderRadius,
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                padding: '0',
                margin: '0',
                overflow: 'hidden',
                background: 'transparent'
            });

            // Positioning Logic
            if (config.position === 'top' || config.position === 'bottom') {
                container.style.position = config.allowOverlap ? 'absolute' : 'relative';
                config.position === 'top'
                    ? document.body.insertBefore(container, document.body.firstChild)
                    : document.body.appendChild(container);
            } else if (['right', 'left'].includes(config.position)) {
                Object.assign(container.style, {
                    position: 'fixed',
                    top: '50%',
                    transform: 'translateY(-50%)',
                    width: config.width,
                    height: config.height
                });
                config.position === 'right' ? (container.style.right = '0px') : (container.style.left = '0px');
                document.body.appendChild(container);
            } else if (config.position === 'center') {
                Object.assign(container.style, {
                    position: 'fixed',
                    top: '50%',
                    left: '50%',
                    transform: 'translate(-50%, -50%)',
                    zIndex: config.zIndex
                });
                document.body.appendChild(container);
            }
        }
        return container;
    }

    // 4. Create Banner
    function createBanner(data) {
        const container = createBannerContainer();
        document.getElementById('dynamic-banner')?.remove();

        const banner = document.createElement('img');
        Object.assign(banner, {
            id: 'dynamic-banner',
            src: data.image_url,
            alt: data.alt_text || 'Advertisement Banner'
        });
        Object.assign(banner.style, {
            width: '100%',
            height: '100%',
            cursor: 'pointer',
            borderRadius: config.borderRadius,
            objectFit: 'fill'
        });

        banner.addEventListener('click', () => {
            window.open(data.link_url, '_blank', 'noopener noreferrer');
        });

        container.innerHTML = '';
        container.appendChild(banner);
    }

    // 5. Load Banner Data
    async function loadBanner() {
        try {
            const response = await fetch(`${API_URL}?_=${Date.now()}`);
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            const data = await response.json();
            console.log('Client data:', data);
            if (data?.image_url) createBanner(data);
        } catch (err) {
            console.error('Banner error:', err.message);
            setTimeout(loadBanner, config.errorRetryInterval); // Retry after error
        }
    }

    // 6. Initialize on Load
    if (document.readyState === 'complete') loadBanner();
    else window.addEventListener('load', loadBanner);

    // 7. Auto-refresh with Dynamic Interval
    let refreshTimer = setInterval(loadBanner, config.refreshInterval);
    
    function updateRefreshInterval(newInterval) {
        clearInterval(refreshTimer);
        refreshTimer = setInterval(loadBanner, newInterval);
        console.log(`Banner refresh interval updated to ${newInterval}ms`);
    }

    // 8. Listen for Config Updates
    window.addEventListener('updateBannerConfig', (e) => {
        if (e.detail?.refreshInterval) {
            updateRefreshInterval(e.detail.refreshInterval);
        }
    });

    // 9. Cleanup on Page Exit
    window.addEventListener('beforeunload', () => clearInterval(refreshTimer));

})();
