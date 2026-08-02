<?php
/**
 * Internationalization bootstrap and helpers.
 * Configure locales in config.php ($i18n), content in locales/{code}.php
 *
 * URL modes (controlled by $LOCAL_DEV):
 *   LOCAL_DEV = true  → ?lang=es query parameter
 *   LOCAL_DEV = false → /es/blogs, /es/faq, etc. (default locale unprefixed)
 */

function i18n_uses_query_param(): bool
{
    global $LOCAL_DEV;
    return !empty($LOCAL_DEV);
}

function i18n_allowed_locales(): array
{
    global $i18n;
    $default = $i18n['defaultLocale'] ?? 'en';
    return array_keys($i18n['locales'] ?? [$default => []]);
}

/** Extract locale code from a leading /{locale}/ path segment, or null */
function i18n_locale_from_path(string $path): ?string
{
    $path = '/' . ltrim($path, '/');
    $segments = explode('/', trim($path, '/'));
    $first = $segments[0] ?? '';

    if ($first !== '' && in_array($first, i18n_allowed_locales(), true)) {
        return $first;
    }

    return null;
}

/** Remove leading /{locale} segment from an internal path */
function i18n_strip_locale_from_path(string $path): string
{
    $path = '/' . ltrim($path, '/');
    $locale = i18n_locale_from_path($path);

    if ($locale === null) {
        return $path === '' ? '/' : $path;
    }

    $segments = explode('/', trim($path, '/'));
    array_shift($segments);

    return $segments === [] ? '/' : '/' . implode('/', $segments);
}

function i18n_request_path(): string
{
    return strtok($_SERVER['REQUEST_URI'] ?? '/', '?') ?: '/';
}

function i18n_bootstrap(): void
{
    global $i18n, $CURRENT_LOCALE;

    if (empty($i18n['enabled'])) {
        $CURRENT_LOCALE = $i18n['defaultLocale'] ?? 'en';
        return;
    }

    $default = i18n_default_locale();
    $allowed = i18n_allowed_locales();
    $param = $i18n['queryParam'] ?? 'lang';
    $cookieName = $i18n['cookieName'] ?? 'site_lang';
    $locale = null;
    $explicit = false;

    if (!i18n_uses_query_param()) {
        $pathLocale = i18n_locale_from_path(i18n_request_path());
        if ($pathLocale !== null) {
            $locale = $pathLocale;
            $explicit = true;
        }
    }

    if ($locale === null && !empty($_GET[$param]) && in_array($_GET[$param], $allowed, true)) {
        $locale = $_GET[$param];
        $explicit = true;
    }

    if (
        $locale === null
        && !empty($i18n['respectSavedLocale'])
        && !empty($_COOKIE[$cookieName])
        && in_array($_COOKIE[$cookieName], $allowed, true)
    ) {
        $locale = $_COOKIE[$cookieName];
    }

    if ($locale === null && !empty($i18n['detectFromBrowser']) && !empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $locale = i18n_match_browser_locale($_SERVER['HTTP_ACCEPT_LANGUAGE'], $allowed, $default);
    }

    $CURRENT_LOCALE = $locale ?? $default;

    if (!empty($i18n['persistInCookie']) && !empty($explicit)) {
        setcookie($cookieName, $CURRENT_LOCALE, [
            'expires' => time() + 60 * 60 * 24 * 365,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
            'httponly' => false,
            'samesite' => 'Lax',
        ]);
    }
}

function i18n_match_browser_locale(string $header, array $allowed, string $default): string
{
    preg_match_all('/\b([a-z]{2}(?:-[a-z]{2})?)\b/i', $header, $matches);
    foreach ($matches[1] ?? [] as $tag) {
        $tag = strtolower($tag);
        if (in_array($tag, $allowed, true)) {
            return $tag;
        }
        $short = substr($tag, 0, 2);
        if (in_array($short, $allowed, true)) {
            return $short;
        }
    }
    return $default;
}

function i18n_current_locale(): string
{
    global $CURRENT_LOCALE, $i18n;
    return $CURRENT_LOCALE ?? ($i18n['defaultLocale'] ?? 'en');
}

function i18n_default_locale(): string
{
    global $i18n;
    return $i18n['defaultLocale'] ?? 'en';
}

function i18n_is_enabled(): bool
{
    global $i18n;
    return !empty($i18n['enabled']);
}

function i18n_show_switcher(): bool
{
    global $i18n;
    return i18n_is_enabled() && !empty($i18n['showSwitcher']) && count($i18n['locales'] ?? []) > 1;
}

/** Translate UI string from $ui array in locale file */
function t(string $key, string $fallback = ''): string
{
    global $ui;
    return $ui[$key] ?? ($fallback !== '' ? $fallback : $key);
}

/** Build localized internal URL (query param in dev, /{lang}/ prefix in production) */
function i18n_locale_url(string $url, ?string $locale = null): string
{
    global $i18n;

    if (!i18n_is_enabled()) {
        return $url;
    }

    if (preg_match('#^https?://#i', $url)) {
        return $url;
    }

    $locale = $locale ?? i18n_current_locale();
    $default = i18n_default_locale();
    $param = $i18n['queryParam'] ?? 'lang';

    $qPos = strpos($url, '?');
    $path = $qPos !== false ? substr($url, 0, $qPos) : $url;
    $query = $qPos !== false ? substr($url, $qPos + 1) : '';

    $path = i18n_strip_locale_from_path($path);

    if (i18n_uses_query_param()) {
        parse_str($query, $params);
        if ($locale === $default) {
            unset($params[$param]);
        } else {
            $params[$param] = $locale;
        }
        $qs = http_build_query($params);
        return $path . ($qs !== '' ? '?' . $qs : '');
    }

    if ($locale === $default) {
        $newPath = $path;
    } elseif ($path === '/') {
        $newPath = '/' . $locale;
    } else {
        $newPath = '/' . $locale . $path;
    }

    return $newPath . ($query !== '' ? '?' . $query : '');
}

/** Add lang query to internal footer/nav links */
function i18n_localize_link_list(array &$items): void
{
    foreach ($items as &$item) {
        if (empty($item['isExternal']) && !empty($item['link'])) {
            $item['link'] = i18n_locale_url($item['link']);
        }
    }
}

function i18n_localize_footer(array &$footer): void
{
    if (!i18n_is_enabled()) {
        return;
    }
    if (!empty($footer['navigation'])) {
        i18n_localize_link_list($footer['navigation']);
    }
    if (!empty($footer['legal'])) {
        i18n_localize_link_list($footer['legal']);
    }
    if (!empty($footer['convertLinks'])) {
        i18n_localize_link_list($footer['convertLinks']);
    }
    if (!empty($footer['toolLinks'])) {
        i18n_localize_link_list($footer['toolLinks']);
    }
}

function i18n_hreflang_links(): array
{
    global $i18n;

    if (!i18n_is_enabled()) {
        return [];
    }

    $links = [];
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $path = i18n_strip_locale_from_path(i18n_request_path());
    $query = $_SERVER['QUERY_STRING'] ?? '';

    if (i18n_uses_query_param() && $query !== '') {
        parse_str($query, $params);
        unset($params[$i18n['queryParam'] ?? 'lang']);
        $query = http_build_query($params);
    }

    $suffix = $query !== '' ? '?' . $query : '';

    foreach ($i18n['locales'] ?? [] as $code => $_meta) {
        $links[] = [
            'hreflang' => $code,
            'url' => $scheme . '://' . $host . i18n_locale_url($path, $code) . $suffix,
        ];
    }

    $default = i18n_default_locale();
    $links[] = [
        'hreflang' => 'x-default',
        'url' => $scheme . '://' . $host . i18n_locale_url($path, $default) . $suffix,
    ];

    return $links;
}

function i18n_switcher_url(string $locale): string
{
    global $i18n;

    $path = i18n_strip_locale_from_path(i18n_request_path());
    $param = $i18n['queryParam'] ?? 'lang';
    $query = $_SERVER['QUERY_STRING'] ?? '';

    parse_str($query, $params);
    unset($params[$param]);

    if (i18n_uses_query_param()) {
        if ($locale !== i18n_default_locale()) {
            $params[$param] = $locale;
        }
        $qs = http_build_query($params);
        return $path . ($qs !== '' ? '?' . $qs : '');
    }

    $url = i18n_locale_url($path, $locale);
    $qs = http_build_query($params);

    return $url . ($qs !== '' ? '?' . $qs : '');
}

/** Production: 301 redirect ?lang=xx to /xx/path */
function i18n_redirect_legacy_lang_query(): void
{
    global $i18n;

    if (!i18n_is_enabled() || i18n_uses_query_param()) {
        return;
    }

    $param = $i18n['queryParam'] ?? 'lang';
    if (empty($_GET[$param])) {
        return;
    }

    $locale = $_GET[$param];
    if (!in_array($locale, i18n_allowed_locales(), true)) {
        return;
    }

    if (i18n_locale_from_path(i18n_request_path()) !== null) {
        return;
    }

    $path = i18n_strip_locale_from_path(i18n_request_path());
    $params = $_GET;
    unset($params[$param]);
    $target = i18n_locale_url($path, $locale);
    $qs = http_build_query($params);

    if ($qs !== '') {
        $target .= (str_contains($target, '?') ? '&' : '?') . $qs;
    }

    header('Location: ' . $target, true, 301);
    exit;
}
