<?php
$downloadUrl = '/download' . ($EXTENSION ?? '');
?>
<div class="blog-cta">
    <div class="blog-cta__inner">
        <div class="blog-cta__icon-wrap">
            <img src="<?php echo htmlspecialchars($common['appIcon']); ?>"
                alt="<?php echo htmlspecialchars($common['appName']); ?> app icon"
                width="64"
                height="64">
        </div>
        <div class="blog-cta__content">
            <p class="blog-cta__eyebrow">Ready to start?</p>
            <h2 class="blog-cta__title">Download <?php echo htmlspecialchars($common['appName']); ?></h2>
            <p class="blog-cta__desc">Get the app and take control of your subscriptions today.</p>
        </div>
        <a href="<?php echo htmlspecialchars($downloadUrl); ?>"
            class="store-badge store-badge--md"
            aria-label="Get <?php echo htmlspecialchars($common['appName']); ?> on Google Play">
            <img src="/assets/google-play-download.svg" alt="Get it on Google Play" width="192" height="57">
        </a>
    </div>
</div>
