<?php
// Include config for global variables
require_once 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '_components/meta.php'; ?>
    <style>
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translate3d(0, 40px, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translate3d(40px, 0, 0);
            }
            to {
                opacity: 1;
                transform: translate3d(0, 0, 0);
            }
        }
        
        .animate-fade-in-up {
            animation: fadeInUp 0.8s ease-out forwards;
        }
        
        .animate-fade-in-right {
            animation: fadeInRight 0.8s ease-out forwards;
        }
        
        .animation-delay-300 { animation-delay: 0.3s; }
        .animation-delay-500 { animation-delay: 0.5s; }
        .animation-delay-600 { animation-delay: 0.6s; }
        .animation-delay-700 { animation-delay: 0.7s; }
        .animation-delay-900 { animation-delay: 0.9s; }
        
        /* Enhanced hover shadow */
        .hover\:shadow-3xl:hover {
            box-shadow: 0 35px 60px -12px rgba(0, 0, 0, 0.25);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 via-white to-blue-50 min-h-screen font-body">
    <!-- Header -->
    <?php include '_components/header.php'; ?>
    
    <!-- Hero Section -->
    <section class="pt-16 pb-20 relative overflow-hidden">
        <!-- Background Gradient -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-100/50 via-white/30 to-blue-100/50"></div>
        
        <!-- Animated Blurred Blobs -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute top-20 -left-32 w-96 h-96 bg-blue-400/30 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 -right-32 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl animate-pulse delay-1000"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-blue-300/20 rounded-full blur-2xl animate-pulse delay-500"></div>
        </div>
        
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Hero Content -->
                <div class="text-center lg:text-left">
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading text-gray-900 mb-6 leading-tight animate-fade-in-up opacity-0 animation-delay-300">
                        <?php echo $home['title']; ?>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed font-body animate-fade-in-up opacity-0 animation-delay-500">
                        <?php echo $home['description']; ?>
                    </p>
                    
                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-center items-center lg:justify-start mb-8 animate-fade-in-up opacity-0 animation-delay-700">
                        <?php if ($common['appStoreUrl']): ?>
                            <a href="<?php echo $common['appStoreUrl']; ?>" 
                               target="_blank" 
                               class="block w-48 h-14 hover:scale-110 hover:-translate-y-1 transition-all duration-300 transform shadow-lg hover:shadow-2xl group">
                                <img src="./assets/app-store-download.svg" alt="Download on the App Store" class="w-full h-full group-hover:brightness-110 transition-all duration-300">
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($common['googlePlayUrl']): ?>
                            <a href="<?php echo $common['googlePlayUrl']; ?>" 
                               target="_blank" 
                               class="block w-48 h-14 hover:scale-110 hover:-translate-y-1 transition-all duration-300 transform shadow-lg hover:shadow-2xl group">
                                <img src="./assets/google-play-download.svg" alt="Get it on Google Play" class="w-full h-full group-hover:brightness-110 transition-all duration-300">
                            </a>
                        <?php endif; ?>
                    </div>
                    
                 
                </div>
                
                <!-- Hero Image -->
                <div class="relative animate-fade-in-right opacity-0 animation-delay-600">
                    <div class="relative mx-auto max-w-sm hover:scale-105 transition-all duration-500 hover:rotate-1">
                        <?php 
                        $heroImageCornerClass = $common['screenshotRoundedCorners'] ? 'rounded-3xl' : 'rounded-none';
                        ?>
                        <img src="<?php echo $home['screenshot']; ?>" 
                             alt="<?php echo $common['appName']; ?> Screenshot" 
                             class="w-full   <?php echo $heroImageCornerClass; ?>   transition-all duration-500">
                     </div>
                    
                    <!-- Floating Elements -->
                    <div class="absolute top-1/3 -left-8 w-12 h-12 bg-green-400 rounded-full opacity-15 animate-ping delay-500"></div>
                    <div class="absolute bottom-1/3 -right-8 w-8 h-8 bg-yellow-400 rounded-full opacity-25 animate-bounce delay-700"></div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Feature Cards with Icons -->
    <?php include '_components/feature_card_with_icon.php'; ?>
    
    <!-- Feature Cards with Screenshots -->
    <?php include '_components/feature_card_with_screenshot.php'; ?>
    
    <!-- Reviews Section -->
    <?php include '_components/review_card.php'; ?>
    
    <!-- Bottom CTA -->
    <?php include '_components/bottom_download_cta.php'; ?>
    
    <!-- Footer -->
    <?php include '_components/footer.php'; ?>
</body>
</html>
