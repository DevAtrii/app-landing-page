<?php
/**
 * blogs.php — Unified Blog List + Article Renderer
 *
 * - /blogs.php              → paginated list of all posts
 * - /blogs.php?article=slug → renders the individual blog post
 *
 * To add a new post: just drop a .md file in the articles/ folder.
 * No database, no build step, no manual configuration needed.
 *
 * FRONTMATTER KEYS (inside each .md file):
 *   title       - (string) Article headline
 *   description - (string) Meta description & card excerpt
 *   author      - (string) Author display name
 *   date        - (string) ISO 8601 date, e.g. 2026-04-05
 *   image       - (string) Absolute path to cover image, e.g. /assets/hero.webp
 *   category    - (string) Optional category name, e.g. "Tutorials"
 *   banner      - (bool)   Show mobile download banner on scroll (default: true)
 *   cta         - (bool)   Show bottom download CTA in article (default: true)
 *   replace     - (string) Slug of another article; requests 301 redirect to that post
 */

require_once 'config.php';
require_once '_components/markdown_parser.php';

$BLOGS_DIR = __DIR__ . '/articles';
$BASE_URL = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'webinto.app');

/**
 * Generate a blog URL that works in both environments:
 *   LOCAL_DEV = true  → /blogs.php?article=slug   (query-based, no nginx needed)
 *   LOCAL_DEV = false → /blogs/slug                (clean URL, nginx rewrite required)
 */
function blog_url(string $slug = ''): string
{
    global $LOCAL_DEV;
    if ($slug === '') {
        // Link to the blog list page
        return $LOCAL_DEV ? '/blogs.php' : '/blogs';
    }
    return $LOCAL_DEV
        ? '/blogs.php?article=' . rawurlencode($slug)
        : '/blogs/' . rawurlencode($slug);
}

// ─── Route Decision ───────────────────────────────────────────────────────────

$articleSlug = isset($_GET['article']) ? preg_replace('/[^a-zA-Z0-9\-_]/', '', $_GET['article']) : null;

if ($articleSlug) {
    // ─── SINGLE ARTICLE VIEW ─────────────────────────────────────────────────
    $filePath = $BLOGS_DIR . '/' . $articleSlug . '.md';
    $post = parseBlogFile($filePath);

    if (!$post) {
        http_response_code(404);
        die('Blog post not found.');
    }

    $meta = $post['meta'];

    $replaceSlug = blogReplaceTargetSlug($meta);
    if ($replaceSlug !== null) {
        $targetPath = $BLOGS_DIR . '/' . $replaceSlug . '.md';
        if (is_file($targetPath)) {
            header('Location: ' . $BASE_URL . blog_url($replaceSlug), true, 301);
            exit;
        }
    }
    $showDownloadBanner = blogBannerEnabled($meta);
    $showArticleCta = blogCtaEnabled($meta);
    $pageTitle = $meta['title'] ?? 'Blog Post';
    $pageDescription = $meta['description'] ?? '';
    $canonicalUrl = $BASE_URL . blog_url($articleSlug);
    $ogImage = !empty($meta['image']) ? $BASE_URL . $meta['image'] : $BASE_URL . $common['appIcon'];
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include '_components/meta.php'; ?>

        <!-- Article-specific Open Graph overrides -->
        <meta property="og:type" content="article">
        <meta property="og:image" content="<?php echo $ogImage; ?>">
        <meta property="article:published_time" content="<?php echo $meta['date'] ?? ''; ?>">
        <meta property="article:author" content="<?php echo $meta['author'] ?? ''; ?>">

        <!-- Prism.js for Syntax Highlighting -->
        <link href="/assets/vendor/prism/themes/prism-tomorrow.min.css" rel="stylesheet" />
        <link href="/assets/vendor/prism/plugins/toolbar/prism-toolbar.min.css" rel="stylesheet" />
        
        <!-- JSON-LD Structured Data -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "BlogPosting",
          "mainEntityOfPage": {
            "@type": "WebPage",
            "@id": "<?php echo $canonicalUrl; ?>"
          },
          "headline": <?php echo json_encode($pageTitle); ?>,
          "description": <?php echo json_encode($pageDescription); ?>,
          "image": ["<?php echo $ogImage; ?>"],
          "author": {
            "@type": "Organization",
            "name": <?php echo json_encode($meta['author'] ?? $common['appName']); ?>
          },
          "publisher": {
            "@type": "Organization",
            "name": <?php echo json_encode($common['appName']); ?>,
            "logo": {
              "@type": "ImageObject",
              "url": "<?php echo $BASE_URL . $common['appIcon']; ?>"
            }
          },
          "datePublished": "<?php echo $meta['date'] ?? ''; ?>",
          "dateModified": "<?php echo $meta['date'] ?? ''; ?>"
        }
        </script>
    </head>

    <body class="page page--alt">
        <?php include '_components/header.php'; ?>

        <article class="blog-article">
            <div class="blog-article__decor">
                <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true"><path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,96.5,-2.9C96.2,12.2,89.6,26.9,80.1,39.6C70.6,52.3,58.2,63,44.2,71.4C30.2,79.8,15.1,85.9,0.3,85.4C-14.5,84.9,-29,77.8,-42.6,69.1C-56.2,60.4,-68.9,50.1,-77.4,37.3C-85.9,24.5,-90.2,9.2,-88.4,-5.4C-86.6,-20,-78.7,-33.9,-68.6,-45.1C-58.5,-56.3,-46.3,-64.8,-33.4,-72.5C-20.5,-80.2,-7.4,-87.1,4.4,-94.9C16.2,-102.7,30.6,-83.6,44.7,-76.4Z" transform="translate(100 100)"/></svg>
            </div>
            <div class="container container--blog">

                <nav class="blog-breadcrumb">
                    <a href="/">Home</a>
                    <span class="material-icons blog-breadcrumb__sep">chevron_right</span>
                    <a href="<?php echo blog_url(); ?>">Blogs</a>
                    <span class="material-icons blog-breadcrumb__sep">chevron_right</span>
                    <span class="blog-breadcrumb__current"><?php echo htmlspecialchars($pageTitle); ?></span>
                </nav>

                <header class="blog-article__header">
                    <?php if (!empty($meta['category'])): ?>
                        <div style="margin-bottom:1rem">
                            <a href="?category=<?php echo urlencode($meta['category']); ?>" class="blog-article__category">
                                <?php echo htmlspecialchars($meta['category']); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    <h1 class="blog-article__title"><?php echo htmlspecialchars($pageTitle); ?></h1>
                    <div class="blog-article__meta">
                        <?php if (!empty($meta['author'])): ?>
                            <span class="blog-article__meta-item">
                                <span class="material-icons">person</span>
                                <?php echo htmlspecialchars($meta['author']); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($meta['date'])): ?>
                            <span class="blog-article__meta-item">
                                <span class="material-icons">calendar_today</span>
                                <?php echo date('F j, Y', strtotime($meta['date'])); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </header>

                <?php if (!empty($meta['image'])): ?>
                    <div class="blog-article__cover">
                        <img src="<?php echo htmlspecialchars($meta['image']); ?>"
                            alt="<?php echo htmlspecialchars($pageTitle); ?>"
                            onerror="this.style.display='none'">
                        <span class="material-icons">image</span>
                    </div>
                <?php endif; ?>

                <div class="blog-article__body">
                    <div class="prose">
                        <?php echo $post['html']; ?>
                    </div>

                    <?php if ($showArticleCta): ?>
                        <?php include '_components/blog_article_download_cta.php'; ?>
                    <?php endif; ?>
                </div>

                <script>
                    document.querySelectorAll('.prose table').forEach(function(table) {
                        if (table.parentElement.classList.contains('table-scroll')) return;
                        var wrapper = document.createElement('div');
                        wrapper.className = 'table-scroll';
                        table.parentNode.insertBefore(wrapper, table);
                        wrapper.appendChild(table);
                    });
                </script>

                <div class="blog-article__back">
                    <a href="<?php echo blog_url(); ?>" class="blog-article__back-link">
                        <span class="material-icons">arrow_back</span>
                        Back to all articles
                    </a>
                </div>
            </div>
        </article>

        <?php include '_components/footer.php'; ?>

        <?php if ($showDownloadBanner): ?>
            <?php include '_components/blog_download_banner.php'; ?>
        <?php endif; ?>

        <!-- Prism.js Scripts for Syntax Highlighting and Copy Button -->
        <script src="/assets/vendor/prism/prism.min.js"></script>
        <script src="/assets/vendor/prism/components/prism-markup.min.js"></script>
        <script src="/assets/vendor/prism/components/prism-css.min.js"></script>
        <script src="/assets/vendor/prism/components/prism-javascript.min.js"></script>
        <script src="/assets/vendor/prism/components/prism-json.min.js"></script>
        <script src="/assets/vendor/prism/components/prism-bash.min.js"></script>
        <script src="/assets/vendor/prism/plugins/toolbar/prism-toolbar.min.js"></script>
        <script src="/assets/vendor/prism/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js"></script>

        <script>
            // The markdown parser might output <pre><code>...</code></pre> without language classes.
            // Let's add a default language-markup class if none exists so Prism can style it,
            // or map the markdown parser's language class to Prism's format.
            document.addEventListener('DOMContentLoaded', (event) => {
                document.querySelectorAll('.prose pre code').forEach((block) => {
                    // Check if it already has a language class
                    let hasLang = false;
                    block.classList.forEach(cls => {
                        if (cls.startsWith('language-')) hasLang = true;
                    });
                    
                    if (!hasLang) {
                        block.classList.add('language-markup'); // Default to HTML/Markup
                    }
                });
                
                // Re-run Prism highlighting after adding classes
                if (window.Prism) {
                    Prism.highlightAll();
                }
            });
        </script>
    </body>

    </html>
    <?php
} else {
    // ─── BLOG LIST VIEW (with pagination) ─────────────────────────────────────
    $allBlogs = getAllBlogs($BLOGS_DIR);
    
    // Handle category filtering
    $activeCategory = isset($_GET['category']) ? trim($_GET['category']) : null;
    if ($activeCategory) {
        $allBlogs = array_filter($allBlogs, function($blog) use ($activeCategory) {
            return isset($blog['category']) && strtolower($blog['category']) === strtolower($activeCategory);
        });
        // Re-index array after filtering
        $allBlogs = array_values($allBlogs);
    }

    $totalBlogs = count($allBlogs);
    $itemsPerPage = 6;
    $totalPages = max(1, ceil($totalBlogs / $itemsPerPage));
    $currentPage = max(1, min($totalPages, (int) ($_GET['page'] ?? 1)));
    $offset = ($currentPage - 1) * $itemsPerPage;
    $pageBlogs = array_slice($allBlogs, $offset, $itemsPerPage);

    $pageTitle = $activeCategory ? htmlspecialchars($activeCategory) . ' Articles' : 'Blog';
    $pageDescription = 'Read the latest tutorials, guides, and updates from the ' . $common['appName'] . ' team.';
    
    // Build pagination base URL to preserve category query param
    $paginationBaseUrl = '?';
    if ($activeCategory) {
        $paginationBaseUrl .= 'category=' . urlencode($activeCategory) . '&';
    }
    
    $canonicalUrl = $BASE_URL . blog_url() . ($currentPage > 1 ? $paginationBaseUrl . 'page=' . $currentPage : ($activeCategory ? '?category=' . urlencode($activeCategory) : ''));
    ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <?php include '_components/meta.php'; ?>
        <!-- JSON-LD Blog listing structured data -->
        <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Blog",
          "name": "<?php echo $common['appName']; ?> Blog",
          "url": "<?php echo $BASE_URL . blog_url(); ?>",
          "description": "<?php echo $pageDescription; ?>"
        }
        </script>
    </head>

    <body class="page page--alt">
        <?php include '_components/header.php'; ?>

        <section class="blog-list-page">
            <div class="container">
                <div class="section-header section-header--lg">
                    <span class="section-header__badge"><?php echo $activeCategory ? 'Category' : 'Our blog'; ?></span>
                    <h1 class="section-header__title section-header__title--xl">
                        <?php echo $activeCategory ? htmlspecialchars($activeCategory) : 'Latest articles'; ?>
                    </h1>
                    <p class="section-header__desc section-header__desc--lg">
                        <?php echo $activeCategory 
                            ? 'Explore all our articles related to ' . htmlspecialchars($activeCategory) . '.'
                            : 'Insights, tutorials, and updates from the ' . htmlspecialchars($common['appName']) . ' team.'; ?>
                    </p>
                    <?php if ($activeCategory): ?>
                        <div style="margin-top:1.5rem">
                            <a href="<?php echo blog_url(); ?>" class="blog-article__back-link">
                                <span class="material-icons">close</span> Clear filter
                            </a>
                        </div>
                    <?php endif; ?>
                </div>

                <?php if (empty($pageBlogs)): ?>
                    <div class="blog-list__empty">
                        <span class="material-icons">article</span>
                        <p>No articles yet. Check back soon!</p>
                    </div>
                <?php else: ?>
                    <div class="blog-list__grid">
                        <?php foreach ($pageBlogs as $blog): ?>
                            <a href="<?php echo blog_url($blog['slug']); ?>" class="blog-card">
                                <div class="blog-card__cover" style="height:12rem">
                                    <span class="material-icons">article</span>
                                    <?php if (!empty($blog['image'])): ?>
                                        <img src="<?php echo htmlspecialchars($blog['image']); ?>"
                                            alt="<?php echo htmlspecialchars($blog['title']); ?>"
                                            onerror="this.style.display='none'">
                                    <?php endif; ?>
                                </div>
                                <div class="blog-card__body" style="padding:1.5rem">
                                    <div class="blog-card__meta">
                                        <?php if (!empty($blog['date'])): ?>
                                            <time><?php echo date('M j, Y', strtotime($blog['date'])); ?></time>
                                        <?php endif; ?>
                                        <?php if (!empty($blog['category']) && $blog['category'] !== 'General'): ?>
                                            <?php if (!empty($blog['date'])): ?><span class="blog-card__meta-sep">•</span><?php endif; ?>
                                            <span class="blog-card__category"><?php echo htmlspecialchars($blog['category']); ?></span>
                                        <?php endif; ?>
                                    </div>
                                    <h2 class="blog-card__title" style="font-size:1.125rem"><?php echo htmlspecialchars($blog['title']); ?></h2>
                                    <?php if (!empty($blog['description'])): ?>
                                        <p class="blog-card__excerpt line-clamp-3" style="font-size:0.875rem"><?php echo htmlspecialchars($blog['description']); ?></p>
                                    <?php endif; ?>
                                    <div class="blog-card__link" style="margin-top:1.25rem;font-size:0.875rem">
                                        Read article <span class="material-icons">arrow_forward</span>
                                    </div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <?php if ($totalPages > 1): ?>
                        <nav class="blog-pagination" aria-label="Pagination">
                            <?php if ($currentPage > 1): ?>
                                <a href="<?php echo $paginationBaseUrl; ?>page=<?php echo $currentPage - 1; ?>" class="blog-pagination__btn">
                                    <span class="material-icons">chevron_left</span> Previous
                                </a>
                            <?php endif; ?>

                            <?php
                            $range = 2;
                            for ($i = 1; $i <= $totalPages; $i++):
                                $near = abs($i - $currentPage) <= $range || $i === 1 || $i === $totalPages;
                                if (!$near) {
                                    if ($i === $currentPage - $range - 1 || $i === $currentPage + $range + 1) {
                                        echo '<span class="blog-pagination__info">…</span>';
                                    }
                                    continue;
                                }
                                $activeClass = $i === $currentPage ? ' blog-pagination__btn--active' : '';
                                ?>
                                <a href="<?php echo $paginationBaseUrl; ?>page=<?php echo $i; ?>"
                                    class="blog-pagination__btn<?php echo $activeClass; ?>">
                                    <?php echo $i; ?>
                                </a>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <a href="<?php echo $paginationBaseUrl; ?>page=<?php echo $currentPage + 1; ?>" class="blog-pagination__btn">
                                    Next <span class="material-icons">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </nav>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </section>

        <?php include '_components/footer.php'; ?>
    </body>

    </html>
    <?php
}
?>