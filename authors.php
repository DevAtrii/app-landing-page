<?php
/**
 * authors.php — Author directory + profile pages
 *
 * Local:  /authors.php              → all authors
 *          /authors.php?author=slug  → author profile + their articles
 * Production: /authors, /authors/{slug}
 * i18n production: /es/authors/{slug}
 */

require_once 'config.php';
require_once '_components/markdown_parser.php';
require_once '_components/authors.php';

$BLOGS_DIR = __DIR__ . '/articles';
$BASE_URL = 'https://' . ($_SERVER['HTTP_HOST'] ?? 'localhost');
$authorSlug = isset($_GET['author']) ? preg_replace('/[^a-zA-Z0-9\-_]/', '', $_GET['author']) : null;

if ($authorSlug) {
    $profile = getAuthorProfile($authorSlug);

    if ($profile === null) {
        http_response_code(404);
        die('Author not found.');
    }

    $authorArticles = getBlogsByAuthor($BLOGS_DIR, $authorSlug);
    $pageTitle = $profile['name'];
    $pageDescription = $profile['bio'] !== ''
        ? mb_substr(strip_tags($profile['bio']), 0, 160)
        : sprintf(t('author_page_desc'), $profile['name'], $common['appName']);
    $canonicalUrl = $BASE_URL . author_url($authorSlug);
    $ogImage = !empty($profile['photo'])
        ? $BASE_URL . $profile['photo']
        : $BASE_URL . $common['appIcon'];

    $sameAs = [];
    foreach ($profile['social'] ?? [] as $url) {
        if (is_string($url) && $url !== '') {
            $sameAs[] = $url;
        }
    }

    $schemaPageType = 'author';
    $schemaContext = [
        'profile' => $profile,
        'canonicalUrl' => $canonicalUrl,
        'sameAs' => $sameAs,
    ];
    ?>
    <!DOCTYPE html>
    <html lang="<?php echo htmlspecialchars(i18n_current_locale()); ?>">
    <head>
        <?php include '_components/meta.php'; ?>
        <meta property="og:type" content="profile">
        <meta property="og:image" content="<?php echo htmlspecialchars($ogImage); ?>">
        <meta name="author" content="<?php echo htmlspecialchars($profile['name']); ?>">
    </head>
    <body class="page page--alt">
        <?php include '_components/header.php'; ?>

        <section class="author-page">
            <div class="container container--blog">
                <nav class="blog-breadcrumb">
                    <a href="<?php echo i18n_locale_url($LOCAL_DEV ? '/index.php' : '/'); ?>"><?php echo htmlspecialchars(t('blog_home')); ?></a>
                    <span class="material-icons blog-breadcrumb__sep">chevron_right</span>
                    <a href="<?php echo author_url(); ?>"><?php echo htmlspecialchars(t('author_directory_title')); ?></a>
                    <span class="material-icons blog-breadcrumb__sep">chevron_right</span>
                    <span class="blog-breadcrumb__current"><?php echo htmlspecialchars($profile['name']); ?></span>
                </nav>

                <header class="author-page__header">
                    <div class="author-page__identity">
                        <?php if (!empty($profile['photo'])): ?>
                            <img src="<?php echo htmlspecialchars($profile['photo']); ?>"
                                 alt="<?php echo htmlspecialchars($profile['name']); ?>"
                                 class="author-page__photo"
                                 width="120"
                                 height="120"
                                 loading="eager"
                                 onerror="this.classList.add('is-hidden')">
                        <?php endif; ?>
                        <div class="author-page__avatar-fallback<?php echo !empty($profile['photo']) ? ' is-hidden' : ''; ?>">
                            <span class="material-icons">person</span>
                        </div>
                        <div>
                            <h1 class="author-page__name"><?php echo htmlspecialchars($profile['name']); ?></h1>
                            <?php if (!empty($profile['role'])): ?>
                                <p class="author-page__role"><?php echo htmlspecialchars($profile['role']); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php if (!empty($profile['bio'])): ?>
                        <p class="author-page__bio"><?php echo nl2br(htmlspecialchars($profile['bio'])); ?></p>
                    <?php endif; ?>
                </header>

                <div class="author-page__articles">
                    <h2 class="author-page__articles-title"><?php echo htmlspecialchars(t('author_articles_title')); ?></h2>
                    <?php if (empty($authorArticles)): ?>
                        <p class="author-page__empty"><?php echo htmlspecialchars(t('author_articles_empty')); ?></p>
                    <?php else: ?>
                        <div class="author-page__grid">
                            <?php foreach ($authorArticles as $blog): ?>
                                <a href="<?php echo blog_url($blog['slug']); ?>" class="blog-card">
                                    <div class="blog-card__cover" style="height:10rem">
                                        <span class="material-icons">article</span>
                                        <?php if (!empty($blog['image'])): ?>
                                            <img src="<?php echo htmlspecialchars($blog['image']); ?>"
                                                 alt="<?php echo htmlspecialchars($blog['title']); ?>"
                                                 onerror="this.style.display='none'">
                                        <?php endif; ?>
                                    </div>
                                    <div class="blog-card__body" style="padding:1.25rem">
                                        <div class="blog-card__meta">
                                            <?php if (!empty($blog['date'])): ?>
                                                <time><?php echo date('M j, Y', strtotime($blog['date'])); ?></time>
                                            <?php endif; ?>
                                        </div>
                                        <h3 class="blog-card__title" style="font-size:1rem"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                        <?php if (!empty($blog['description'])): ?>
                                            <p class="blog-card__excerpt line-clamp-2" style="font-size:0.875rem"><?php echo htmlspecialchars($blog['description']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>

        <?php include '_components/footer.php'; ?>
    </body>
    </html>
    <?php
    exit;
}

// ─── Author directory ─────────────────────────────────────────────────────────
$allAuthors = getAllAuthors();
$pageTitle = t('author_directory_title');
$pageDescription = t('author_directory_desc');
$canonicalUrl = $BASE_URL . author_url();
$schemaPageType = 'author_directory';
$schemaContext = [
    'title' => $pageTitle,
    'description' => $pageDescription,
    'url' => $canonicalUrl,
];
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars(i18n_current_locale()); ?>">
<head>
    <?php include '_components/meta.php'; ?>
</head>
<body class="page page--alt">
    <?php include '_components/header.php'; ?>

    <section class="author-directory">
        <div class="container">
            <div class="section-header section-header--lg">
                <span class="section-header__badge"><?php echo htmlspecialchars(t('blog_blogs')); ?></span>
                <h1 class="section-header__title section-header__title--xl"><?php echo htmlspecialchars(t('author_directory_title')); ?></h1>
                <p class="section-header__desc section-header__desc--lg"><?php echo htmlspecialchars(t('author_directory_desc')); ?></p>
            </div>

            <?php if (empty($allAuthors)): ?>
                <div class="blog-list__empty">
                    <span class="material-icons">people</span>
                    <p><?php echo htmlspecialchars(t('author_directory_empty')); ?></p>
                </div>
            <?php else: ?>
                <div class="author-directory__grid">
                    <?php foreach ($allAuthors as $author): ?>
                        <a href="<?php echo author_url($author['slug']); ?>" class="author-card">
                            <?php if (!empty($author['photo'])): ?>
                                <img src="<?php echo htmlspecialchars($author['photo']); ?>"
                                     alt=""
                                     class="author-card__photo"
                                     width="72"
                                     height="72"
                                     loading="lazy"
                                     onerror="this.style.display='none'">
                            <?php else: ?>
                                <span class="author-card__avatar" aria-hidden="true">
                                    <span class="material-icons">person</span>
                                </span>
                            <?php endif; ?>
                            <span class="author-card__name"><?php echo htmlspecialchars($author['name']); ?></span>
                            <?php if (!empty($author['role'])): ?>
                                <span class="author-card__role"><?php echo htmlspecialchars($author['role']); ?></span>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include '_components/footer.php'; ?>
</body>
</html>
