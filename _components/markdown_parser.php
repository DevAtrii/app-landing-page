<?php
/**
 * Markdown Parser Helper
 * 
 * Parses a .md file and returns:
 *   - 'meta'    => associative array of YAML frontmatter fields
 *   - 'content' => raw markdown body (after frontmatter)
 *   - 'html'    => rendered HTML of the body
 */

require_once __DIR__ . '/Parsedown.php';

function parseBlogFile(string $filePath): ?array {
    if (!file_exists($filePath)) {
        return null;
    }

    $raw = file_get_contents($filePath);

    // Parse YAML-style frontmatter between --- markers
    $meta = [];
    $body = $raw;

    if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)/s', $raw, $matches)) {
        $frontmatter = $matches[1];
        $body        = $matches[2];

        // Parse each "key: value" line
        foreach (explode("\n", $frontmatter) as $line) {
            if (preg_match('/^(\w+)\s*:\s*(.+)$/', trim($line), $m)) {
                $key   = trim($m[1]);
                $value = trim($m[2], " \t\n\r\0\x0B\"'");
                $meta[$key] = $value;
            }
        }
    }

    $parsedown = new Parsedown();
    $parsedown->setSafeMode(false); // allow raw HTML in .md files

    return [
        'meta'    => $meta,
        'content' => $body,
        'html'    => $parsedown->text($body),
    ];
}

/**
 * Whether the mobile download banner should show for a blog post.
 * Frontmatter `banner: false` disables it; omitted or `true` enables (default).
 */
function blogBannerEnabled(array $meta): bool
{
    if (!isset($meta['banner'])) {
        return true;
    }
    $value = strtolower(trim((string) $meta['banner']));
    return !in_array($value, ['false', '0', 'no', 'off'], true);
}

/**
 * Whether the bottom download CTA should show on a blog post.
 * Frontmatter `cta: false` disables it; omitted or `true` enables (default).
 */
function blogCtaEnabled(array $meta): bool
{
    if (!isset($meta['cta'])) {
        return true;
    }
    $value = strtolower(trim((string) $meta['cta']));
    return !in_array($value, ['false', '0', 'no', 'off'], true);
}

/**
 * Resolve frontmatter `replace` to a target article slug (basename without .md).
 * Accepts slug, slug.md, or slug.webp in frontmatter.
 */
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
 * Scans the blogs/ directory and returns a sorted array of all blog post metadata.
 * Posts with frontmatter `replace` are omitted (they redirect to another article).
 * Each entry has keys: slug, title, description, date, image, author.
 */
function getAllBlogs(string $blogsDir): array {
    $files = glob($blogsDir . '/*.md');
    $blogs = [];

    foreach ($files as $file) {
        $parsed = parseBlogFile($file);
        if (!$parsed) continue;

        $slug = basename($file, '.md');
        $meta = $parsed['meta'];

        if (blogReplaceTargetSlug($meta) !== null) {
            continue;
        }

        $blogs[] = [
            'slug'        => $slug,
            'title'       => $meta['title']       ?? 'Untitled',
            'description' => $meta['description'] ?? '',
            'date'        => $meta['date']         ?? '',
            'image'       => $meta['image']        ?? '',
            'author'      => $meta['author']       ?? '',
            'category'    => $meta['category']     ?? 'General',
        ];
    }

    // Sort by date descending (newest first)
    usort($blogs, fn($a, $b) => strcmp($b['date'], $a['date']));

    return $blogs;
}
