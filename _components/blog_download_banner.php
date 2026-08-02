<?php
$bannerUrl = !empty($common['googlePlayUrl']) ? $common['googlePlayUrl'] : '/';
?>
<div id="blog-download-banner"
    class="blog-download-banner"
    role="region"
    aria-label="Download app"
    aria-hidden="true">
    <div class="blog-download-banner__inner">
        <img src="<?php echo htmlspecialchars($common['appIcon']); ?>"
            alt=""
            class="blog-download-banner__icon"
            width="40"
            height="40">
        <span class="blog-download-banner__name"><?php echo htmlspecialchars($common['appName']); ?></span>
        <a href="<?php echo htmlspecialchars($bannerUrl); ?>"
            target="_blank"
            rel="noopener noreferrer"
            class="blog-download-banner__btn">Get App</a>
        <button type="button"
            id="blog-download-banner-close"
            class="blog-download-banner__close"
            aria-label="Dismiss download banner">
            <span class="material-icons">close</span>
        </button>
    </div>
</div>
<script>
(function () {
    var banner = document.getElementById('blog-download-banner');
    if (!banner) return;
    var SCROLL_THRESHOLD = 120;
    var storageKey = 'w2a-blog-banner-dismissed';
    if (sessionStorage.getItem(storageKey)) { banner.remove(); return; }
    document.body.classList.add('blog-banner-active');
    var closeBtn = document.getElementById('blog-download-banner-close');
    var visible = false;
    function setVisible(show) {
        if (show === visible) return;
        visible = show;
        banner.setAttribute('aria-hidden', show ? 'false' : 'true');
        banner.classList.toggle('is-visible', show);
    }
    function onScroll() { setVisible(window.scrollY >= SCROLL_THRESHOLD); }
    function dismiss() {
        sessionStorage.setItem(storageKey, '1');
        banner.remove();
        document.body.classList.remove('blog-banner-active');
        window.removeEventListener('scroll', onScroll, { passive: true });
    }
    closeBtn.addEventListener('click', dismiss);
    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll();
})();
</script>
