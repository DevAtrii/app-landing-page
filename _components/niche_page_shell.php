<?php
/**
 * Full-page shell for niche landing pages.
 * Set before include: $nicheKey, $pageTitle, $pageDescription
 */
if (empty($nicheKey) || empty($pageTitle) || empty($pageDescription)) {
    http_response_code(500);
    die('Niche page configuration missing.');
}

$nichePages = require __DIR__ . '/niche_pages_data.php';
if (!isset($nichePages[$nicheKey])) {
    http_response_code(404);
    die('Niche page not found.');
}

$nichePage = $nichePages[$nicheKey];
$bottomCta = $nichePage['bottomCta'];
$scriptBase = basename($_SERVER['SCRIPT_NAME'] ?? '', '.php');
$canonicalUrl = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'webinto.app') . '/' . $scriptBase . ($EXTENSION ?? '');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include __DIR__ . '/meta.php'; ?>
</head>
<body class="page page--alt">
    <?php include __DIR__ . '/header.php'; ?>
    <?php include __DIR__ . '/niche_landing_page.php'; ?>
    <?php include __DIR__ . '/bottom_download_cta.php'; ?>
    <?php include __DIR__ . '/footer.php'; ?>
    <?php include __DIR__ . '/niche_modals.php'; ?>
</body>
</html>
