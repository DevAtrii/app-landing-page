<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>

<section class="py-16 bg-white/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold font-heading text-gray-900 mb-4"><?php echo $ratings['title']; ?></h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto font-body"><?php echo $ratings['description']; ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($ratings['ratingsList'] as $review): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow duration-300">
                    <!-- Stars -->
                    <div class="flex mb-4">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-icons text-yellow-400 text-xl">
                                <?php echo $i <= $review['rating'] ? 'star' : 'star_border'; ?>
                            </span>
                        <?php endfor; ?>
                    </div>
                    
                    <!-- Review Text -->
                    <p style="    min-height: 80px;" class="text-gray-700 mb-6 leading-relaxed">"<?php echo $review['description']; ?>"</p>
                    
                    <!-- User Info -->
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full overflow-hidden mr-3">
                            <?php if (!empty($review['image'])): ?>
                                <img src="<?php echo $review['image']; ?>" 
                                     alt="<?php echo $review['title']; ?>" 
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-blue-600 text-lg">person</span>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900"><?php echo $review['title']; ?></p>
                            <p class="text-sm text-gray-500">Verified User</p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- App Store Ratings -->
        <div class="mt-12 grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php if ($common['appRatingAppStore']): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="mb-4">
                        <span class="material-icons text-4xl text-black mb-2">apple</span>
                        <h3 class="text-lg font-semibold text-gray-900">App Store</h3>
                    </div>
                    <div class="flex justify-center mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-icons text-yellow-400 text-xl">
                                <?php echo $i <= floor($common['appRatingAppStore']['rating']) ? 'star' : 'star_border'; ?>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $common['appRatingAppStore']['rating']; ?></p>
                    <p class="text-sm text-gray-500"><?php echo number_format($common['appRatingAppStore']['totalReviews']); ?> reviews</p>
                </div>
            <?php endif; ?>
            
            <?php if ($common['appRatingGooglePlay']): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 text-center">
                    <div class="mb-4">
                        <span class="material-icons text-4xl text-green-600 mb-2">android</span>
                        <h3 class="text-lg font-semibold text-gray-900">Google Play</h3>
                    </div>
                    <div class="flex justify-center mb-2">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="material-icons text-yellow-400 text-xl">
                                <?php echo $i <= floor($common['appRatingGooglePlay']['rating']) ? 'star' : 'star_border'; ?>
                            </span>
                        <?php endfor; ?>
                    </div>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $common['appRatingGooglePlay']['rating']; ?></p>
                    <p class="text-sm text-gray-500"><?php echo number_format($common['appRatingGooglePlay']['totalReviews']); ?> reviews</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>
