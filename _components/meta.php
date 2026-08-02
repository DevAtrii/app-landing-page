<?php
require_once __DIR__ . '/../config.php';

$preloadLcpImage = $preloadLcpImage ?? null;
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? $pageTitle . ' - ' . $common['appName'] : $common['appName'] . ' - ' . $common['appTitle']; ?></title>
<meta name="description" content="<?php echo isset($pageDescription) ? $pageDescription : $common['appDescription']; ?>">

<meta name="keywords" content="subscription management, app, mobile, <?php echo strtolower($common['appName']); ?>, track subscriptions, save money, blog">
<meta name="author" content="<?php echo $common['appName']; ?>">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
<?php foreach (i18n_hreflang_links() as $alt): ?>
<link rel="alternate" hreflang="<?php echo htmlspecialchars($alt['hreflang']); ?>" href="<?php echo htmlspecialchars($alt['url']); ?>">
<?php endforeach; ?>

<meta property="og:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - ' . $common['appName'] : $common['appName'] . ' - ' . $common['appTitle']; ?>">
<meta property="og:description" content="<?php echo isset($pageDescription) ? $pageDescription : $common['appDescription']; ?>">
<meta property="og:image" content="<?php echo $common['appIcon']; ?>">
<meta property="og:url" content="<?php echo isset($canonicalUrl) ? $canonicalUrl : 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']; ?>">
<meta property="og:type" content="website">
<meta property="og:site_name" content="<?php echo $common['appName']; ?>">

<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?php echo isset($pageTitle) ? $pageTitle . ' - ' . $common['appName'] : $common['appName'] . ' - ' . $common['appTitle']; ?>">
<meta name="twitter:description" content="<?php echo isset($pageDescription) ? $pageDescription : $common['appDescription']; ?>">
<meta name="twitter:image" content="<?php echo $common['appIcon']; ?>">

<link rel="icon" href="<?php echo $common['appIcon']; ?>" type="image/webp">
<link rel="apple-touch-icon" href="<?php echo $common['appIcon']; ?>">

<link rel="preload" href="/css/base.css" as="style">
<link rel="preload" href="/css/style.css" as="style">
<link rel="preload" href="/assets/vendor/fonts/geist-sans-latin-900-normal.woff2" as="font" type="font/woff2" crossorigin>
<link rel="preload" href="/assets/vendor/fonts/geist-sans-latin-500-normal.woff2" as="font" type="font/woff2" crossorigin>
<?php if (!empty($preloadLcpImage)): ?>
<link rel="preload" href="<?php echo htmlspecialchars($preloadLcpImage); ?>" as="image" type="image/webp" fetchpriority="high">
<?php endif; ?>

<link rel="stylesheet" href="/assets/vendor/fonts/app-fonts-critical.css">
<link rel="stylesheet" href="/css/base.css">
<link rel="stylesheet" href="/css/style.css">

<link rel="stylesheet" href="/assets/vendor/fonts/app-fonts-deferred.css" media="print" onload="this.media='all'">
<link rel="stylesheet" href="/assets/vendor/fonts/material-icons.css" media="print" onload="this.media='all'">
<noscript>
    <link rel="stylesheet" href="/assets/vendor/fonts/app-fonts-deferred.css">
    <link rel="stylesheet" href="/assets/vendor/fonts/material-icons.css">
</noscript>
