<?php
require_once __DIR__ . '/../config.php';
?>

<header class="site-header">
    <div class="site-header__inner">
        <div class="site-header__bar">
            <a href="/" class="site-header__brand"
               aria-label="<?php echo htmlspecialchars($common['appName']); ?> — Home">
                <div class="site-header__logo-wrap">
                    <img src="<?php echo $common['appIcon']; ?>" alt="" width="40" height="40" decoding="async" fetchpriority="low"
                         class="site-header__logo" role="presentation">
                </div>
                <span class="site-header__name"><?php echo htmlspecialchars($common['appName']); ?></span>
            </a>
            
            <div>
                <?php if ($common['googlePlayUrl'] || $common['appStoreUrl']): ?>
                    <a href="/download<?php echo $EXTENSION; ?>" class="btn btn--primary">
                        <span class="material-icons" aria-hidden="true">rocket_launch</span>
                        <span><span class="site-header__cta-label-short">Download</span><span class="site-header__cta-label-full">Get Started</span></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
