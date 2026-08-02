<?php
require_once __DIR__ . '/../config.php';
$imgRounded = $common['screenshotRoundedCorners'] ? 'feature-screenshot-card__img--rounded' : 'feature-screenshot-card__img--sharp';
?>

<section class="section features-screenshots">
    <div class="container">
        <div class="section-header">
            <h2 class="section-header__title"><?php echo $featuresScreenshots['title']; ?></h2>
            <p class="section-header__desc"><?php echo $featuresScreenshots['description']; ?></p>
        </div>
        
        <div class="features-screenshots__grid">
            <?php foreach ($featuresScreenshots['featuresList'] as $index => $feature): ?>
                <div class="feature-screenshot-card">
                    <div class="feature-screenshot-card__header">
                        <div class="feature-screenshot-card__badge">
                            <span class="material-icons">star</span>
                            Feature <?php echo sprintf('%02d', $index + 1); ?>
                        </div>
                        <h3 class="feature-screenshot-card__title"><?php echo $feature['title']; ?></h3>
                        <p class="feature-screenshot-card__desc"><?php echo $feature['description']; ?></p>
                    </div>
                    <img src="<?php echo $feature['image']; ?>" 
                         alt="<?php echo $feature['title']; ?> Screenshot" 
                         class="feature-screenshot-card__img <?php echo $imgRounded; ?>"
                         loading="lazy">
                    <div class="feature-screenshot-card__pattern">
                        <div class="feature-screenshot-card__pattern-inner"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
