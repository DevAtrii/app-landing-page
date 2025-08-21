<?php
// Include config for global variables
require_once __DIR__ . '/../config.php';
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . $common['appName'] : $common['appName'] . ' - ' . $common['appTitle']; ?></title>
<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : $common['appDescription']; ?>">

<!-- SEO Meta Tags -->
<meta name="keywords" content="subscription management, app, mobile, <?php echo strtolower($common['appName']); ?>, track subscriptions, save money">
<meta name="author" content="<?php echo $common['appName']; ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">

<!-- Open Graph Meta Tags -->
<meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - ' . $common['appName'] : $common['appName'] . ' - ' . $common['appTitle']; ?>">
<meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : $common['appDescription']; ?>">
<meta property="og:image" content="<?php echo $common['appIcon']; ?>">
<meta property="og:url" content="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo $common['appName']; ?>">

<!-- Twitter Card Meta Tags -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - ' . $common['appName'] : $common['appName'] . ' - ' . $common['appTitle']; ?>">
<meta name="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : $common['appDescription']; ?>">
<meta name="twitter:image" content="<?php echo $common['appIcon']; ?>">

<!-- Favicon -->
<link rel="icon" href="<?php echo $common['appIcon']; ?>" type="image/webp">
<link rel="apple-touch-icon" href="<?php echo $common['appIcon']; ?>">

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

<!-- Styles -->
<link href="./dist/output.css" rel="stylesheet">

<!-- Custom Font Styles -->
<style>
    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }
    
    h1, h2, h3, h4, h5, h6 {
        font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    }
    
    /* Custom font weight for better hierarchy */
    .font-heading {
        font-family: 'Plus Jakarta Sans', sans-serif;
    }
    
    .font-body {
        font-family: 'Inter', sans-serif;
    }
    
    /* Line clamp utility for better text truncation */
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Enhanced blur effects */
    .blur-2xl {
        filter: blur(40px);
    }
    
    .blur-3xl {
        filter: blur(64px);
    }
</style>
