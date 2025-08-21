<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>

<section class="py-16 bg-gray-50/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold font-heading text-gray-900 mb-4"><?php echo $featuresScreenshots['title']; ?></h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto font-body"><?php echo $featuresScreenshots['description']; ?></p>
        </div>
        
        <!-- Modern Feature Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featuresScreenshots['featuresList'] as $index => $feature): ?>
                <?php
                // Alternate layout patterns for visual interest
                $isEven = $index % 2 === 0;
                $cardTypes = [
                    'featured', // First card - large and prominent
                    'standard', // Regular cards
                    'standard',
                    'standard'     // Wide card spans 2 columns
                ];
                $cardType = $cardTypes[$index % 4];
                $spanClass = ($cardType === 'wide') ? 'md:col-span-2' : 'md:col-span-1';
                $isFeatured = $cardType === 'featured';
                ?>
                <div class="<?php echo $spanClass; ?> group">
                    <div style="max-height: 34rem;" class="relative bg-gradient-to-br from-white to-gray-50/50 rounded-3xl border border-gray-200/60 overflow-hidden hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-1">
                        
                        <!-- Card Header -->
                        <div class="relative p-6 pb-4 text-center">
                            <!-- Feature Badge -->
                            <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-700 mb-4">
                                <span class="material-icons text-sm mr-1">star</span>
                                Feature <?php echo sprintf('%02d', $index + 1); ?>
                            </div>
                            
                            <!-- Title and Description -->
                            <h3 class="text-xl lg:text-2xl font-bold font-heading text-gray-900 mb-3 leading-tight">
                                <?php echo $feature['title']; ?>
                            </h3>
                            <p class="text-gray-600 font-body leading-relaxed">
                                <?php echo $feature['description']; ?>
                            </p>
                        </div>
                        
                        <!-- Screenshot Section -->
                           <!-- Screenshot -->
                           <?php 
                                $imageCornerClass = $common['screenshotRoundedCorners'] ? 'rounded-2xl' : 'rounded-none';
                                ?>
                           <img src="<?php echo $feature['image']; ?>" 
                                     alt="<?php echo $feature['title']; ?> Screenshot" 
                                      style="max-width: 250px;     transform: translateY(2rem);"
                                     class="rounded-2xl relative px-6 pb-6  mx-auto w-full h-auto object-cover group-hover:scale-105 transition-transform duration-500  <?php echo $imageCornerClass; ?>"
                                     loading="lazy">
                        
                        
                        <!-- Subtle Background Pattern -->
                        <div class="absolute top-0 right-0 w-32 h-32 opacity-5">
                            <div class="w-full h-full bg-gradient-to-br from-blue-400 to-purple-600 rounded-full transform rotate-45 scale-150"></div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
