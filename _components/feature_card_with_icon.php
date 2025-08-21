<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>

<section class="py-16 bg-white/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4"><?php echo $featuresIcons['title']; ?></h2>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto"><?php echo $featuresIcons['description']; ?></p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php foreach ($featuresIcons['featuresList'] as $feature): ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8 hover:shadow-md transition-shadow duration-300">
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                                <span class="material-icons text-blue-600 text-2xl"><?php echo $feature['icon']; ?></span>
                            </div>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold font-heading text-gray-900 mb-3"><?php echo $feature['title']; ?></h3>
                            <p class="text-gray-600 leading-relaxed font-body"><?php echo $feature['description']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
