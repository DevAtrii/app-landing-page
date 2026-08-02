<?php
/**
 * Config-driven JSON-LD structured data.
 * Page types and @type values are defined in config.php → $schema['pages'].
 *
 * Each template sets $schemaPageType (e.g. 'home', 'blog_post') before including meta.php.
 * Optional $schemaContext passes page-specific data (article meta, author profile, etc.).
 */

function schema_page_config(string $pageType): array
{
    global $schema;

    return $schema['pages'][$pageType] ?? ($schema['pages']['default'] ?? ['type' => 'WebPage']);
}

function schema_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';

    return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
}

function schema_absolute(string $path): string
{
    if (preg_match('#^https?://#i', $path)) {
        return $path;
    }

    return schema_base_url() . $path;
}

function schema_filter_nulls(array $data): array
{
    $filtered = [];

    foreach ($data as $key => $value) {
        if ($value === null || $value === '') {
            continue;
        }

        if (is_array($value)) {
            $nested = schema_filter_nulls($value);
            if ($nested !== []) {
                $filtered[$key] = $nested;
            }
            continue;
        }

        $filtered[$key] = $value;
    }

    return $filtered;
}

function schema_organization(): array
{
    global $common, $schema;

    $org = array_merge([
        '@type' => 'Organization',
        'name' => $common['appName'],
        'url' => schema_base_url() . (function_exists('i18n_locale_url') ? i18n_locale_url($GLOBALS['LOCAL_DEV'] ? '/index.php' : '/') : '/'),
        'logo' => [
            '@type' => 'ImageObject',
            'url' => schema_absolute($common['appIcon']),
        ],
    ], $schema['organization'] ?? []);

    return schema_filter_nulls($org);
}

function schema_aggregate_rating(): ?array
{
    global $common;

    $rating = $common['appRatingAppStore']['rating'] ?? ($common['appRatingGooglePlay']['rating'] ?? null);
    $count = $common['appRatingAppStore']['totalReviews'] ?? ($common['appRatingGooglePlay']['totalReviews'] ?? null);

    if ($rating === null) {
        return null;
    }

    return schema_filter_nulls([
        '@type' => 'AggregateRating',
        'ratingValue' => $rating,
        'ratingCount' => $count,
        'bestRating' => 5,
        'worstRating' => 1,
    ]);
}

function schema_store_urls(): array
{
    global $common;

    $urls = [];

    if (!empty($common['appStoreUrl'])) {
        $urls[] = $common['appStoreUrl'];
    }
    if (!empty($common['googlePlayUrl'])) {
        $urls[] = $common['googlePlayUrl'];
    }

    return $urls;
}

function schema_build_home(array $cfg): ?array
{
    global $common, $home;

    $node = array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'MobileApplication',
        'name' => $common['appName'],
        'description' => strip_tags($common['appDescription']),
        'url' => schema_base_url() . (function_exists('i18n_locale_url') ? i18n_locale_url($GLOBALS['LOCAL_DEV'] ? '/index.php' : '/') : '/'),
        'image' => schema_absolute($home['screenshot'] ?? $common['appIcon']),
        'softwareVersion' => $common['appVersion'] ?? null,
        'operatingSystem' => 'iOS, Android',
        'applicationCategory' => 'UtilitiesApplication',
        'offers' => [
            '@type' => 'Offer',
            'price' => '0',
            'priceCurrency' => 'USD',
        ],
        'publisher' => schema_organization(),
    ], $cfg['properties'] ?? []);

    $rating = schema_aggregate_rating();
    if ($rating !== null) {
        $node['aggregateRating'] = $rating;
    }

    $stores = schema_store_urls();
    if ($stores !== []) {
        $node['downloadUrl'] = count($stores) === 1 ? $stores[0] : $stores;
    }

    return schema_filter_nulls($node);
}

function schema_build_blog(array $cfg, array $context): ?array
{
    global $common;

    return schema_filter_nulls(array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'Blog',
        'name' => $common['appName'] . ' Blog',
        'url' => $context['url'] ?? schema_base_url() . blog_url(),
        'description' => $context['description'] ?? $common['appDescription'],
        'publisher' => schema_organization(),
    ], $cfg['properties'] ?? []));
}

function schema_build_blog_post(array $cfg, array $context): ?array
{
    global $common;

    $node = array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'BlogPosting',
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => $context['canonicalUrl'] ?? '',
        ],
        'headline' => $context['title'] ?? '',
        'description' => $context['description'] ?? '',
        'image' => !empty($context['image']) ? [$context['image']] : null,
        'datePublished' => $context['datePublished'] ?? null,
        'dateModified' => $context['dateModified'] ?? ($context['datePublished'] ?? null),
        'publisher' => schema_organization(),
    ], $cfg['properties'] ?? []);

    $authors = $context['authors'] ?? [];
    if (count($authors) === 1) {
        $node['author'] = $authors[0];
    } elseif ($authors !== []) {
        $node['author'] = $authors;
    } else {
        $node['author'] = schema_organization();
    }

    return schema_filter_nulls($node);
}

function schema_build_author(array $cfg, array $context): ?array
{
    $profile = $context['profile'] ?? [];

    return schema_filter_nulls(array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'ProfilePage',
        'mainEntity' => schema_filter_nulls([
            '@type' => 'Person',
            'name' => $profile['name'] ?? '',
            'jobTitle' => $profile['role'] ?? null,
            'description' => $profile['bio'] ?? null,
            'url' => $context['canonicalUrl'] ?? null,
            'image' => !empty($profile['photo']) ? schema_absolute($profile['photo']) : null,
            'sameAs' => !empty($context['sameAs']) ? $context['sameAs'] : null,
        ]),
    ], $cfg['properties'] ?? []));
}

function schema_build_author_directory(array $cfg, array $context): ?array
{
    return schema_filter_nulls(array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'CollectionPage',
        'name' => $context['title'] ?? 'Authors',
        'description' => $context['description'] ?? '',
        'url' => $context['url'] ?? schema_base_url() . author_url(),
    ], $cfg['properties'] ?? []));
}

function schema_build_faq(array $cfg, array $context): ?array
{
    global $faqs;

    $entities = [];
    foreach ($faqs['faqsList'] ?? [] as $category) {
        foreach ($category['faqs'] ?? [] as $faq) {
            if (empty($faq['title']) || empty($faq['description'])) {
                continue;
            }
            $entities[] = [
                '@type' => 'Question',
                'name' => strip_tags($faq['title']),
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq['description']),
                ],
            ];
        }
    }

    return schema_filter_nulls(array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'FAQPage',
        'name' => $faqs['title'] ?? 'FAQ',
        'description' => $context['description'] ?? ($faqs['description'] ?? ''),
        'url' => $context['url'] ?? null,
        'mainEntity' => $entities !== [] ? $entities : null,
    ], $cfg['properties'] ?? []));
}

function schema_build_contact(array $cfg, array $context): ?array
{
    global $common;

    return schema_filter_nulls(array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'ContactPage',
        'name' => $context['title'] ?? t('contact_title'),
        'description' => $context['description'] ?? t('contact_subtitle'),
        'url' => $context['url'] ?? null,
        'mainEntity' => schema_filter_nulls([
            '@type' => 'Organization',
            'name' => $common['appName'],
            'email' => $common['supportEmail'] ?? null,
            'contactPoint' => !empty($common['supportEmail']) ? [
                '@type' => 'ContactPoint',
                'contactType' => 'customer support',
                'email' => $common['supportEmail'],
            ] : null,
        ]),
    ], $cfg['properties'] ?? []));
}

function schema_build_web_page(array $cfg, array $context): ?array
{
    global $common;

    return schema_filter_nulls(array_merge([
        '@context' => 'https://schema.org',
        '@type' => $cfg['type'] ?? 'WebPage',
        'name' => $context['title'] ?? $common['appName'],
        'description' => $context['description'] ?? $common['appDescription'],
        'url' => $context['url'] ?? ($context['canonicalUrl'] ?? null),
        'isPartOf' => [
            '@type' => 'WebSite',
            'name' => $common['appName'],
            'url' => schema_base_url(),
        ],
    ], $cfg['properties'] ?? []));
}

function schema_build_graph(string $pageType, array $context): ?array
{
    $cfg = schema_page_config($pageType);

    return match ($pageType) {
        'home' => schema_build_home($cfg),
        'blog' => schema_build_blog($cfg, $context),
        'blog_post' => schema_build_blog_post($cfg, $context),
        'author' => schema_build_author($cfg, $context),
        'author_directory' => schema_build_author_directory($cfg, $context),
        'faq' => schema_build_faq($cfg, $context),
        'contact' => schema_build_contact($cfg, $context),
        default => schema_build_web_page($cfg, $context),
    };
}

function schema_build_additional(string $pageType, array $context): array
{
    $cfg = schema_page_config($pageType);
    $graphs = [];

    foreach ($cfg['additional'] ?? [] as $extra) {
        $extraType = $extra['type'] ?? 'WebPage';
        $mergedCfg = array_merge($cfg, ['type' => $extraType, 'properties' => $extra['properties'] ?? []]);

        $graph = match ($extraType) {
            'Organization' => schema_filter_nulls(array_merge(
                ['@context' => 'https://schema.org', '@type' => 'Organization'],
                $extra['properties'] ?? [],
                schema_organization()
            )),
            'MobileApplication', 'SoftwareApplication', 'WebApplication' => schema_build_home($mergedCfg),
            default => schema_build_web_page($mergedCfg, $context),
        };

        if ($graph !== null && $graph !== []) {
            $graphs[] = $graph;
        }
    }

    return $graphs;
}

/** Output JSON-LD script tag(s) for the current page */
function schema_render(string $pageType, array $context = []): void
{
    global $schema;

    if (empty($schema['enabled'])) {
        return;
    }

    $graphs = [];
    $primary = schema_build_graph($pageType, $context);

    if ($primary !== null && $primary !== []) {
        $graphs[] = $primary;
    }

    foreach (schema_build_additional($pageType, $context) as $extra) {
        $graphs[] = $extra;
    }

    foreach ($graphs as $graph) {
        echo '<script type="application/ld+json">';
        echo json_encode($graph, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        echo '</script>' . "\n";
    }
}
