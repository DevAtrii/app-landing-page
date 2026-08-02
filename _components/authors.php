<?php
/**
 * Blog author registry and helpers.
 * Author profiles live in authors/{slug}.php
 */

function author_slugify(string $value): string
{
    $slug = strtolower(trim($value));
    $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
    return trim($slug, '-') ?: '';
}

function author_registry(): array
{
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $cache = [];
    $dir = dirname(__DIR__) . '/authors';

    if (!is_dir($dir)) {
        return $cache;
    }

    foreach (glob($dir . '/*.php') as $file) {
        $slug = basename($file, '.php');
        if (!preg_match('/^[a-z0-9\-_]+$/', $slug)) {
            continue;
        }

        $data = require $file;
        if (!is_array($data)) {
            continue;
        }

        $data['slug'] = $slug;
        $cache[$slug] = $data;
    }

    return $cache;
}

function author_exists(string $slug): bool
{
    return isset(author_registry()[$slug]);
}

/** Author profile for the active (or given) locale */
function getAuthorProfile(string $slug, ?string $locale = null): ?array
{
    $registry = author_registry();
    if (!isset($registry[$slug])) {
        return null;
    }

    $author = $registry[$slug];
    $locale = $locale ?? (function_exists('i18n_current_locale') ? i18n_current_locale() : 'en');
    $default = function_exists('i18n_default_locale') ? i18n_default_locale() : 'en';
    $loc = $author['locales'][$locale] ?? $author['locales'][$default] ?? null;

    if ($loc === null) {
        return null;
    }

    return [
        'slug' => $slug,
        'name' => $loc['name'] ?? $slug,
        'role' => $loc['role'] ?? '',
        'bio' => $loc['bio'] ?? '',
        'photo' => $author['photo'] ?? '',
        'social' => $author['social'] ?? [],
        'url' => author_url($slug),
    ];
}

function getAllAuthors(?string $locale = null): array
{
    $locale = $locale ?? (function_exists('i18n_current_locale') ? i18n_current_locale() : 'en');
    $authors = [];

    foreach (author_registry() as $slug => $_data) {
        $profile = getAuthorProfile($slug, $locale);
        if ($profile !== null) {
            $authors[] = $profile;
        }
    }

    usort($authors, fn($a, $b) => strcasecmp($a['name'], $b['name']));

    return $authors;
}

/**
 * Parse author slugs from article frontmatter.
 * Supports `author: slug` and `authors: slug-a, slug-b`
 */
function blogArticleAuthorSlugs(array $meta): array
{
    $slugs = [];

    if (!empty($meta['authors'])) {
        foreach (preg_split('/\s*,\s*/', (string) $meta['authors']) as $part) {
            $part = author_slugify($part);
            if ($part !== '') {
                $slugs[] = $part;
            }
        }
    }

    if (empty($slugs) && !empty($meta['author'])) {
        $raw = trim((string) $meta['author']);
        foreach (preg_split('/\s*,\s*/', $raw) as $part) {
            $slug = author_slugify($part);
            if ($slug !== '' && author_exists($slug)) {
                $slugs[] = $slug;
            } elseif ($slug !== '' && !author_exists($slug)) {
                // Legacy display name — try slug match, else keep as pseudo entry handled in resolve
                $slugs[] = $slug;
            }
        }
    }

    return array_values(array_unique($slugs));
}

/**
 * Resolved author rows for templates + JSON-LD.
 * Registered authors are linked; unknown strings render as plain text.
 */
function blogArticleAuthors(array $meta, ?string $locale = null): array
{
    $locale = $locale ?? (function_exists('i18n_current_locale') ? i18n_current_locale() : 'en');
    $resolved = [];
    $slugs = blogArticleAuthorSlugs($meta);

    if ($slugs !== []) {
        foreach ($slugs as $slug) {
            $profile = getAuthorProfile($slug, $locale);
            if ($profile !== null) {
                $resolved[] = array_merge($profile, ['linked' => true]);
                continue;
            }

            $resolved[] = [
                'slug' => null,
                'name' => str_replace('-', ' ', ucwords($slug, '-')),
                'role' => '',
                'bio' => '',
                'photo' => '',
                'social' => [],
                'url' => null,
                'linked' => false,
            ];
        }

        return $resolved;
    }

    if (!empty($meta['author'])) {
        $resolved[] = [
            'slug' => null,
            'name' => trim((string) $meta['author']),
            'role' => '',
            'bio' => '',
            'photo' => '',
            'social' => [],
            'url' => null,
            'linked' => false,
        ];
    }

    return $resolved;
}

/** Published articles that list this author slug */
function getBlogsByAuthor(string $blogsDir, string $authorSlug, ?string $locale = null): array
{
    require_once __DIR__ . '/markdown_parser.php';

    $authorSlug = author_slugify($authorSlug);
    $blogs = getAllBlogs($blogsDir, $locale);

    return array_values(array_filter($blogs, function ($blog) use ($authorSlug) {
        return in_array($authorSlug, $blog['author_slugs'] ?? [], true);
    }));
}

/** Schema.org Person nodes for BlogPosting JSON-LD */
function blogAuthorsJsonLd(array $authors, string $baseUrl): array
{
    $nodes = [];

    foreach ($authors as $author) {
        if (empty($author['name'])) {
            continue;
        }

        $node = [
            '@type' => 'Person',
            'name' => $author['name'],
        ];

        if (!empty($author['url'])) {
            $node['url'] = $baseUrl . $author['url'];
        }

        if (!empty($author['photo'])) {
            $node['image'] = $baseUrl . $author['photo'];
        }

        if (!empty($author['role'])) {
            $node['jobTitle'] = $author['role'];
        }

        $nodes[] = $node;
    }

    return $nodes;
}
