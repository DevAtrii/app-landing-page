<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>

<footer class="bg-gray-50 border-t border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- App Info -->
            <div class="md:col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <img src="<?php echo $common['appIcon']; ?>" alt="<?php echo $common['appName']; ?>" class="w-10 h-10 rounded-xl">
                    <span class="text-xl font-semibold font-heading text-gray-900"><?php echo $common['appName']; ?></span>
                </div>
                <p class="text-gray-600 mb-6 max-w-md font-body"><?php echo $footer['description']; ?></p>
                
                <!-- Download Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <?php if ($common['appStoreUrl']): ?>
                        <a href="<?php echo $common['appStoreUrl']; ?>" 
                           target="_blank" 
                           class="block w-40 h-12 hover:opacity-80 transition-opacity duration-200">
                            <img src="./assets/app-store-download.svg" alt="Download on the App Store" class="w-full h-full">
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($common['googlePlayUrl']): ?>
                        <a href="<?php echo $common['googlePlayUrl']; ?>" 
                           target="_blank" 
                           class="block w-40 h-12 hover:opacity-80 transition-opacity duration-200">
                            <img src="./assets/google-play-download.svg" alt="Get it on Google Play" class="w-full h-full">
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Navigation Links -->
            <div>
                <h3 class="text-lg font-semibold font-heading text-gray-900 mb-4">Navigation</h3>
                <ul class="space-y-2">
                    <?php foreach ($footer['navigation'] as $nav): ?>
                        <li>
                            <a href="<?php echo $nav['link']; ?>" 
                               <?php echo $nav['isExternal'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                               class="text-gray-600 hover:text-blue-600 transition-colors duration-200 flex items-center font-body">
                                <?php echo $nav['title']; ?>
                                <?php if ($nav['isExternal']): ?>
                                    <span class="material-icons text-sm ml-1">open_in_new</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            
            <!-- Social Links -->
            <div>
                <h3 class="text-lg font-semibold font-heading text-gray-900 mb-4">Follow Us</h3>
                <ul class="space-y-2">
                    <?php foreach ($footer['socials'] as $social): ?>
                        <li>
                            <a href="<?php echo $social['link']; ?>" 
                               target="_blank" 
                               rel="noopener noreferrer"
                               class="text-gray-600 hover:text-blue-600 transition-colors duration-200 flex items-center font-body">
                                <?php echo $social['title']; ?>
                                <span class="material-icons text-sm ml-1">open_in_new</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
        
        <!-- Legal Links -->
        <div class="border-t border-gray-200 mt-8 pt-8">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <div class="flex flex-wrap gap-6">
                    <?php foreach ($footer['legal'] as $legal): ?>
                        <a href="<?php echo $legal['link']; ?>" 
                           class="text-sm text-gray-500 hover:text-blue-600 transition-colors duration-200 font-body">
                            <?php echo $legal['title']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="text-center md:text-right">
                    <p class="text-sm text-gray-500 font-body"><?php echo $footer['copyright']; ?></p>
                    <p class="text-sm text-gray-400 mt-1 font-body"><?php echo $footer['message']; ?></p>
                </div>
            </div>
        </div>
    </div>
</footer>
