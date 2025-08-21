<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>

<section class="py-20 bg-blue-50">
    
    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <!-- Main content -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl border border-white/60 shadow-xl   md:p-12">
            <!-- App icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-2xl mb-6">
                <img src="<?php echo $common['appIcon']; ?>" alt="<?php echo $common['appName']; ?>" class="  rounded-xl">
            </div>
            
            <!-- Title and description -->
            <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-4">
                <?php echo $bottomCta['title']; ?>
            </h2>
            <p class="text-lg text-gray-600 font-body mb-8 max-w-2xl mx-auto leading-relaxed">
                <?php echo $bottomCta['description']; ?>
            </p>
        
            
            <!-- Secondary download options -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center mb-8">
                <?php if ($common['appStoreUrl']): ?>
                    <a href="<?php echo $common['appStoreUrl']; ?>" 
                       target="_blank" 
                       class="block w-40 h-12 hover:scale-105 transition-all duration-200 opacity-80 hover:opacity-100">
                        <img src="./assets/app-store-download.svg" alt="Download on the App Store" class="w-full h-full">
                    </a>
                <?php endif; ?>
                
                <?php if ($common['googlePlayUrl']): ?>
                    <a href="<?php echo $common['googlePlayUrl']; ?>" 
                       target="_blank" 
                       class="block w-40 h-12 hover:scale-105 transition-all duration-200 opacity-80 hover:opacity-100">
                        <img src="./assets/google-play-download.svg" alt="Get it on Google Play" class="w-full h-full">
                    </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</section>
