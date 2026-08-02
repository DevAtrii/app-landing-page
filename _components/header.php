<?php
require_once __DIR__ . '/../config.php';
?>

<header class="site-header">
    <div class="site-header__inner">
        <div class="site-header__bar">
            <a href="<?php echo i18n_locale_url($LOCAL_DEV ? '/index.php' : '/'); ?>" class="site-header__brand"
               aria-label="<?php echo htmlspecialchars($common['appName']); ?> — Home">
                <div class="site-header__logo-wrap">
                    <img src="<?php echo $common['appIcon']; ?>" alt="" width="40" height="40" decoding="async" fetchpriority="low"
                         class="site-header__logo" role="presentation">
                </div>
                <span class="site-header__name"><?php echo htmlspecialchars($common['appName']); ?></span>
            </a>
            
            <div class="site-header__actions">
                <?php if ($common['googlePlayUrl'] || $common['appStoreUrl']): ?>
                    <a href="<?php echo i18n_locale_url('/download' . $EXTENSION); ?>" class="btn btn--primary">
                        <span class="material-icons" aria-hidden="true">rocket_launch</span>
                        <span><span class="site-header__cta-label-short"><?php echo htmlspecialchars(t('header_download')); ?></span><span class="site-header__cta-label-full"><?php echo htmlspecialchars(t('header_get_started')); ?></span></span>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
