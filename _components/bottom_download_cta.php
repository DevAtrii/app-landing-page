<?php
require_once __DIR__ . '/../config.php';
?>

<section class="bottom-cta">
    <div class="container container--narrow">
        <div class="bottom-cta__card">
            <div class="bottom-cta__icon-wrap">
                <img src="<?php echo $common['appIcon']; ?>" alt="<?php echo $common['appName']; ?>">
            </div>
            <h2 class="bottom-cta__title"><?php echo $bottomCta['title']; ?></h2>
            <p class="bottom-cta__desc"><?php echo $bottomCta['description']; ?></p>
            <div class="bottom-cta__badges">
                <?php if ($common['appStoreUrl']): ?>
                    <a href="<?php echo $common['appStoreUrl']; ?>" target="_blank" class="store-badge store-badge--sm store-badge--subtle">
                        <img src="./assets/app-store-download.svg" alt="Download on the App Store">
                    </a>
                <?php endif; ?>
                <?php if ($common['googlePlayUrl']): ?>
                    <a href="<?php echo $common['googlePlayUrl']; ?>" target="_blank" class="store-badge store-badge--sm store-badge--subtle">
                        <img src="./assets/google-play-download.svg" alt="Get it on Google Play">
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>
