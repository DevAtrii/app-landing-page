<?php
require_once 'config.php';
$preloadLcpImage = $home['screenshot'];
$heroRounded = $common['screenshotRoundedCorners'] ? 'hero__screenshot--rounded' : 'hero__screenshot--sharp';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include '_components/meta.php'; ?>
</head>
<body class="page">
    <?php include '_components/header.php'; ?>
    
    <section class="section section--hero">
        <div class="hero__decor perf-defer-motion" id="hero-decor">
            <svg class="hero__blob hero__blob--top animate-pulse" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,96.5,-2.9C96.2,12.2,89.6,26.9,80.1,39.6C70.6,52.3,58.2,63,44.2,71.4C30.2,79.8,15.1,85.9,0.3,85.4C-14.5,84.9,-29,77.8,-42.6,69.1C-56.2,60.4,-68.9,50.1,-77.4,37.3C-85.9,24.5,-90.2,9.2,-88.4,-5.4C-86.6,-20,-78.7,-33.9,-68.6,-45.1C-58.5,-56.3,-46.3,-64.8,-33.4,-72.5C-20.5,-80.2,-7.4,-87.1,4.4,-94.9C16.2,-102.7,30.6,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
            </svg>
            <svg class="hero__blob hero__blob--bottom animate-pulse animation-delay-2000" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path fill="currentColor" d="M47.7,-57.2C59.4,-47.3,64.8,-29.4,66.8,-11.9C68.8,5.6,67.4,22.7,58.8,36.2C50.2,49.7,34.4,59.6,17.3,64.1C0.2,68.6,-18.2,67.7,-34.5,59.9C-50.8,52.1,-64.9,37.4,-70.7,20.2C-76.5,3,-74,-16.7,-64.1,-31.6C-54.2,-46.5,-36.9,-56.6,-20.5,-61.2C-4.1,-65.8,11.4,-64.9,26.5,-61.5C41.6,-58.1,36,-67.1,47.7,-57.2Z" transform="translate(100 100)" />
            </svg>
            <div class="hero__dots">
                <div class="hero__dot hero__dot--1 animate-bounce"></div>
                <div class="hero__dot hero__dot--2 animate-bounce animation-delay-300"></div>
                <div class="hero__dot hero__dot--3 animate-ping animation-delay-700"></div>
            </div>
        </div>
        
        <div class="container hero__grid">
            <div class="hero__content">
                <div class="hero__eyebrow animate-fade-in-up">
                    <span class="hero__eyebrow-dot animate-pulse"></span>
                    Track every subscription in one place
                </div>
                
                <h1 class="hero__title animate-fade-in-up animation-delay-300">
                    <?php echo str_replace("text-blue-600", "text-highlight", $home['title']); ?>
                </h1>
                <p class="hero__desc animate-fade-in-up animation-delay-500">
                    <?php echo $home['description']; ?>
                </p>
                
                <div class="hero__actions animate-fade-in-up animation-delay-700">
                    <?php if ($common['appStoreUrl']): ?>
                        <a href="<?php echo $common['appStoreUrl']; ?>" target="_blank" class="store-badge">
                            <img src="./assets/app-store-download.svg" alt="Download on the App Store">
                        </a>
                    <?php endif; ?>
                    <?php if ($common['googlePlayUrl']): ?>
                        <a href="<?php echo $common['googlePlayUrl']; ?>" target="_blank" class="store-badge">
                            <img src="./assets/google-play-download.svg" alt="Get it on Google Play">
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="animate-fade-in-right animation-delay-600">
                <div class="hero__media-wrap">
                    <div class="hero__media-glow"></div>
                    <img src="<?php echo $home['screenshot']; ?>" 
                         alt="<?php echo $common['appName']; ?> Screenshot" 
                         width="405" height="868"
                         decoding="async"
                         fetchpriority="high"
                         class="hero__screenshot <?php echo $heroRounded; ?>">
                </div>
            </div>
        </div>
    </section>
    
    <div class="content-auto">
        <?php include '_components/how_it_works.php'; ?>
        <?php include '_components/feature_card_with_icon.php'; ?>
        <?php include '_components/feature_card_with_screenshot.php'; ?>
        <?php include '_components/review_card.php'; ?>
        <?php include '_components/home_blogs_section.php'; ?>
        <?php include '_components/bottom_download_cta.php'; ?>
    </div>
    
    <?php include '_components/footer.php'; ?>
    <script>
        requestAnimationFrame(function () {
            var el = document.getElementById('hero-decor');
            if (el) el.classList.add('motion-ready');
        });
    </script>
</body>
</html>
