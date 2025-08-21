<?php 

// Include config for app store URLs
require_once 'config.php';

include "BrowserDetection.php";

$Browser = new foroco\BrowserDetection();

$useragent = $_SERVER['HTTP_USER_AGENT'];

// Get OS data (array):
$result = $Browser->getOS($useragent);

// Determine redirect URL based on OS
$redirectUrl = '/'; // Default to homepage

if (isset($result['os_family'])) {
    $osFamily = strtolower($result['os_family']);
    
    // Check for Android
    if ($osFamily === 'android' && !empty($common['googlePlayUrl'])) {
        $redirectUrl = $common['googlePlayUrl'];
    }
    // Check for iOS (iPhone/iPad)
    elseif (($osFamily === 'ios' || $osFamily === 'iphone' || $osFamily === 'ipad') && !empty($common['appStoreUrl'])) {
        $redirectUrl = $common['appStoreUrl'];
    }
    // For desktop/other OS, check if we have any app store URL as fallback
    elseif ($osFamily === 'desktop' || $osFamily === 'macintosh' || $osFamily === 'windows' || $osFamily === 'linux') {
        // For desktop, try App Store first (for macOS), then Google Play, then homepage
        $redirectUrl = '/';
    }
}

// Perform the redirect
header("Location: " . $redirectUrl);
exit();