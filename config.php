<?php

// Local dev toggle — set to false in production (deploy workflow patches this automatically)
$LOCAL_DEV = true;
$EXTENSION = $LOCAL_DEV ? '.php' : '';

// ─── Internationalization ─────────────────────────────────────────────────────
$i18n = [
    'enabled' => true,
    'defaultLocale' => 'en',
    'locales' => [
        'en' => ['label' => 'English', 'hreflang' => 'en'],
        'es' => ['label' => 'Español', 'hreflang' => 'es'],
    ],
    'showSwitcher' => true,
    'persistInCookie' => true,
    'cookieName' => 'site_lang',
    'queryParam' => 'lang', // local dev only; production uses /{lang}/path prefixes
    'detectFromBrowser' => false, // plain / always English; use switcher or ?lang= / /es/…
    'respectSavedLocale' => false, // don't restore locale from cookie on unprefixed URLs
];

require_once __DIR__ . '/_components/i18n.php';
i18n_bootstrap();
i18n_redirect_legacy_lang_query();

$localeFile = __DIR__ . '/locales/' . i18n_current_locale() . '.php';
if (!is_file($localeFile)) {
    $localeFile = __DIR__ . '/locales/' . i18n_default_locale() . '.php';
}
require $localeFile;

i18n_localize_footer($footer);

/**
 * Blog URL helper for footer and other shared components.
 */
function resource_blog_url(string $slug = '', ?string $category = null): string
{
    global $LOCAL_DEV, $EXTENSION;

    if ($slug !== '') {
        $url = $LOCAL_DEV
            ? '/blogs.php?article=' . rawurlencode($slug)
            : '/blogs/' . rawurlencode($slug);
    } else {
        $url = '/blogs' . $EXTENSION;
        if ($category !== null && $category !== '') {
            $url .= '?category=' . rawurlencode($category);
        }
    }

    return i18n_locale_url($url);
}
