<?php
require_once __DIR__ . '/../config.php';
?>

<section class="section features-icons">
    <div class="container">
        <div class="section-header">
            <h2 class="section-header__title"><?php echo $featuresIcons['title']; ?></h2>
            <p class="section-header__desc"><?php echo $featuresIcons['description']; ?></p>
        </div>
        
        <div class="features-icons__grid">
            <?php foreach ($featuresIcons['featuresList'] as $feature): ?>
                <div class="feature-icon-card">
                    <div class="feature-icon-card__inner">
                        <div class="feature-icon-card__icon-wrap">
                            <span class="material-icons"><?php echo $feature['icon']; ?></span>
                        </div>
                        <div>
                            <h3 class="feature-icon-card__title"><?php echo $feature['title']; ?></h3>
                            <p class="feature-icon-card__desc"><?php echo $feature['description']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
