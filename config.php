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

// ─── Structured data (JSON-LD) ───────────────────────────────────────────────
// Set $schemaPageType on each page template (e.g. 'home', 'blog_post', 'faq').
// Change @type per page below — e.g. SoftwareApplication instead of MobileApplication.
$schema = [
    'enabled' => true,
    'organization' => [
        // Merged into publisher / Organization nodes
    ],
    'pages' => [
        'home' => [
            'type' => 'MobileApplication',
            'properties' => [
                'applicationCategory' => 'FinanceApplication',
                'operatingSystem' => 'iOS, Android',
            ],
        ],
        'blog' => [
            'type' => 'Blog',
        ],
        'blog_post' => [
            'type' => 'BlogPosting',
        ],
        'author' => [
            'type' => 'ProfilePage',
        ],
        'author_directory' => [
            'type' => 'CollectionPage',
        ],
        'faq' => [
            'type' => 'FAQPage',
        ],
        'contact' => [
            'type' => 'ContactPage',
        ],
        'legal' => [
            'type' => 'WebPage',
        ],
        'default' => [
            'type' => 'WebPage',
        ],
    ],
];

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

function blog_url(string $slug = ''): string
{
    global $LOCAL_DEV;

    if ($slug === '') {
        return i18n_locale_url($LOCAL_DEV ? '/blogs.php' : '/blogs');
    }

    $url = $LOCAL_DEV
        ? '/blogs.php?article=' . rawurlencode($slug)
        : '/blogs/' . rawurlencode($slug);

    return i18n_locale_url($url);
}

function blog_list_page_url(int $page = 1, ?string $category = null): string
{
    global $LOCAL_DEV;

    $path = $LOCAL_DEV ? '/blogs.php' : '/blogs';
    $params = [];

    if ($category !== null && $category !== '') {
        $params['category'] = $category;
    }
    if ($page > 1) {
        $params['page'] = $page;
    }

    $url = $path;
    if ($params !== []) {
        $url .= '?' . http_build_query($params);
    }

    return i18n_locale_url($url);
}

function author_url(string $slug = ''): string
{
    global $LOCAL_DEV;

    if ($slug === '') {
        return i18n_locale_url($LOCAL_DEV ? '/authors.php' : '/authors');
    }

    $url = $LOCAL_DEV
        ? '/authors.php?author=' . rawurlencode($slug)
        : '/authors/' . rawurlencode($slug);

    return i18n_locale_url($url);
}
