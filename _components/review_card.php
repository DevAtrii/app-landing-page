<?php
require_once __DIR__ . '/../config.php';
?>

<section class="section reviews">
    <div class="container">
        <div class="section-header">
            <h2 class="section-header__title"><?php echo $ratings['title']; ?></h2>
            <p class="section-header__desc"><?php echo $ratings['description']; ?></p>
        </div>
        
        <div class="reviews__grid">
            <?php foreach ($ratings['ratingsList'] as $review): ?>
                <div class="review-card">
                    <div class="review-card__stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-icons"><?php echo $i <= $review['rating'] ? 'star' : 'star_border'; ?></span>
                        <?php endfor; ?>
                    </div>
                    <p class="review-card__text">"<?php echo $review['description']; ?>"</p>
                    <div class="review-card__author">
                        <div class="review-card__avatar">
                            <?php if (!empty($review['image'])): ?>
                                <img src="<?php echo $review['image']; ?>" alt="<?php echo $review['title']; ?>">
                            <?php else: ?>
                                <div class="review-card__avatar-placeholder">
                                    <span class="material-symbols-outlined">person</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="review-card__name"><?php echo $review['title']; ?></p>
                            <p class="review-card__meta"><?php echo htmlspecialchars(t('reviews_verified')); ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="store-ratings">
            <?php if ($common['appRatingAppStore']): ?>
                <div class="store-rating-card">
                    <span class="material-icons store-rating-card__icon store-rating-card__icon--apple">apple</span>
                    <h3 class="store-rating-card__title">App Store</h3>
                    <div class="store-rating-card__stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-icons"><?php echo $i <= floor($common['appRatingAppStore']['rating']) ? 'star' : 'star_border'; ?></span>
                        <?php endfor; ?>
                    </div>
                    <p class="store-rating-card__score"><?php echo $common['appRatingAppStore']['rating']; ?></p>
                    <p class="store-rating-card__count"><?php echo number_format($common['appRatingAppStore']['totalReviews']); ?> <?php echo htmlspecialchars(t('store_rating_reviews')); ?></p>
                </div>
            <?php endif; ?>
            <?php if ($common['appRatingGooglePlay']): ?>
                <div class="store-rating-card">
                    <span class="material-icons store-rating-card__icon store-rating-card__icon--android">android</span>
                    <h3 class="store-rating-card__title">Google Play</h3>
                    <div class="store-rating-card__stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-icons"><?php echo $i <= floor($common['appRatingGooglePlay']['rating']) ? 'star' : 'star_border'; ?></span>
                        <?php endfor; ?>
                    </div>
                    <p class="store-rating-card__score"><?php echo $common['appRatingGooglePlay']['rating']; ?></p>
                    <p class="store-rating-card__count"><?php echo number_format($common['appRatingGooglePlay']['totalReviews']); ?> <?php echo htmlspecialchars(t('store_rating_reviews')); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
