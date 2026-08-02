<?php
/**
 * Markdown Parser Helper
 */

require_once __DIR__ . '/Parsedown.php';

function parseBlogFile(string $filePath): ?array
{
    if (!file_exists($filePath)) {
        return null;
    }

    $raw = file_get_contents($filePath);
    $meta = [];
    $body = $raw;

    if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $raw, $matches)) {
        $frontmatter = $matches[1];
        $body = $matches[2];

        foreach (explode("\n", $frontmatter) as $line) {
            if (preg_match('/^([\w-]+)\s*:\s*(.+)$/', trim($line), $m)) {
                $key = trim($m[1]);
                $value = trim($m[2], " \t\n\r\0\x0B\"'");
                $meta[$key] = $value;
            }
        }
    }

    $parsedown = new Parsedown();
    $parsedown->setSafeMode(false);

    return [
        'meta' => $meta,
        'content' => $body,
        'html' => $parsedown->text($body),
    ];
}

function blogMetaDate(array $meta, string $key): ?string
{
    if (empty($meta[$key])) {
        return null;
    }
    $value = trim((string) $meta[$key]);
    $dt = DateTimeImmutable::createFromFormat('Y-m-d', $value);
    return ($dt && $dt->format('Y-m-d') === $value) ? $value : null;
}

/** Article visible on site when today >= publish-after (or key omitted). */
function blogIsPublished(array $meta, ?string $today = null): bool
{
    $publishAfter = blogMetaDate($meta, 'publish-after');
    if ($publishAfter === null) {
        $publishAfter = blogMetaDate($meta, 'publish_after');
    }
    if ($publishAfter === null) {
        return true;
    }

    $today = $today ?? (new DateTimeImmutable('today'))->format('Y-m-d');
    return $today >= $publishAfter;
}

function blogArticleLang(array $meta): string
{
    global $i18n;
    $default = $i18n['defaultLocale'] ?? 'en';
    $lang = strtolower(trim((string) ($meta['lang'] ?? $default)));
    return $lang !== '' ? $lang : $default;
}

function blogBannerEnabled(array $meta): bool
{
    if (!isset($meta['banner'])) {
        return true;
    }
    $value = strtolower(trim((string) $meta['banner']));
    return !in_array($value, ['false', '0', 'no', 'off'], true);
}

function blogCtaEnabled(array $meta): bool
{
    if (!isset($meta['cta'])) {
        return true;
    }
    $value = strtolower(trim((string) $meta['cta']));
    return !in_array($value, ['false', '0', 'no', 'off'], true);
}

function blogReplaceTargetSlug(array $meta): ?string
{
    if (empty($meta['replace'])) {
        return null;
    }

    $slug = trim((string) $meta['replace']);
    $slug = preg_replace('/\.(md|webp)$/i', '', $slug);
    $slug = preg_replace('/[^a-zA-Z0-9\-_]/', '', $slug);

    return $slug !== '' ? $slug : null;
}

/**
 * Scans articles/ and returns published posts for the active locale, newest first.
 */
function getAllBlogs(string $blogsDir, ?string $locale = null, bool $includeScheduled = false): array
{
    global $i18n;

    $locale = $locale ?? (function_exists('i18n_current_locale') ? i18n_current_locale() : ($i18n['defaultLocale'] ?? 'en'));
    $defaultLocale = $i18n['defaultLocale'] ?? 'en';
    $today = (new DateTimeImmutable('today'))->format('Y-m-d');

    $files = glob($blogsDir . '/*.md');
    $blogs = [];

    foreach ($files as $file) {
        $parsed = parseBlogFile($file);
        if (!$parsed) {
            continue;
        }

        $slug = basename($file, '.md');
        $meta = $parsed['meta'];

        if (blogReplaceTargetSlug($meta) !== null) {
            continue;
        }

        if (!$includeScheduled && !blogIsPublished($meta, $today)) {
            continue;
        }

        if (blogArticleLang($meta) !== $locale) {
            continue;
        }

        $blogs[] = [
            'slug' => $slug,
            'title' => $meta['title'] ?? 'Untitled',
            'description' => $meta['description'] ?? '',
            'date' => $meta['date'] ?? '',
            'image' => $meta['image'] ?? '',
            'author' => $meta['author'] ?? '',
            'category' => $meta['category'] ?? 'General',
            'lang' => blogArticleLang($meta),
            'publish-after' => blogMetaDate($meta, 'publish-after') ?? blogMetaDate($meta, 'publish_after'),
        ];
    }

    usort($blogs, fn($a, $b) => strcmp($b['date'], $a['date']));

    return $blogs;
}

/** Load article if published and matches locale; otherwise null */
function getPublishedBlogPost(string $blogsDir, string $slug, ?string $locale = null): ?array
{
    $filePath = $blogsDir . '/' . $slug . '.md';
    $post = parseBlogFile($filePath);
    if (!$post) {
        return null;
    }

    $locale = $locale ?? (function_exists('i18n_current_locale') ? i18n_current_locale() : 'en');

    if (!blogIsPublished($post['meta'])) {
        return null;
    }

    if (blogArticleLang($post['meta']) !== $locale) {
        return null;
    }

    return $post;
}
