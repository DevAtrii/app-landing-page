<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>

<header class="sticky top-0 z-50 backdrop-blur-sm bg-white/80 border-b border-gray-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left side - App Icon and Name -->
            <a href="/" class="flex items-center space-x-3 hover:opacity-80 transition-opacity duration-200">
                <img src="<?php echo $common['appIcon']; ?>" alt="<?php echo $common['appName']; ?>" class="w-10 h-10 rounded-xl shadow-sm">
                <span class="text-xl font-semibold font-heading text-gray-900"><?php echo $common['appName']; ?></span>
            </a>
            
            <!-- Right side - Download Button -->
            <div class="flex items-center">
                <?php if ($common['googlePlayUrl'] || $common['appStoreUrl']): ?>
                    <a href="/download.php" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                        <span class="material-icons text-lg mr-2">download</span>
                        Download
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>
