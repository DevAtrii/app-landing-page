<?php
require_once '_components/markdown_parser.php';

$BLOGS_DIR = __DIR__ . '/../articles';
$allBlogs = getAllBlogs($BLOGS_DIR);
$recentBlogs = array_slice($allBlogs, 0, 3);

if (!empty($recentBlogs)):
?>
<section class="section section--lg home-blogs">
    <div class="home-blogs__decor">
        <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,96.5,-2.9C96.2,12.2,89.6,26.9,80.1,39.6C70.6,52.3,58.2,63,44.2,71.4C30.2,79.8,15.1,85.9,0.3,85.4C-14.5,84.9,-29,77.8,-42.6,69.1C-56.2,60.4,-68.9,50.1,-77.4,37.3C-85.9,24.5,-90.2,9.2,-88.4,-5.4C-86.6,-20,-78.7,-33.9,-68.6,-45.1C-58.5,-56.3,-46.3,-64.8,-33.4,-72.5C-20.5,-80.2,-7.4,-87.1,4.4,-94.9C16.2,-102.7,30.6,-83.6,44.7,-76.4Z" transform="translate(100 100)" />
        </svg>
    </div>

    <div class="container">
        <div class="section-header section-header--lg">
            <span class="section-header__badge">Resources</span>
            <h2 class="section-header__title section-header__title--xl">Latest from our Blog</h2>
            <p class="section-header__desc section-header__desc--lg">Discover tips, tutorials, and updates to help you get the most out of <?php echo htmlspecialchars($common['appName']); ?>.</p>
        </div>

        <div class="home-blogs__grid">
            <?php foreach ($recentBlogs as $blog): ?>
                <a href="<?php echo $LOCAL_DEV ? '/blogs.php?article=' . rawurlencode($blog['slug']) : '/blogs/' . rawurlencode($blog['slug']); ?>"
                   class="blog-card">
                    <div class="blog-card__cover">
                        <span class="material-icons">article</span>
                        <?php if (!empty($blog['image'])): ?>
                            <img src="<?php echo htmlspecialchars($blog['image']); ?>"
                                alt="<?php echo htmlspecialchars($blog['title']); ?>"
                                onerror="this.style.display='none'">
                        <?php endif; ?>
                    </div>
                    <div class="blog-card__body">
                        <div class="blog-card__accent"></div>
                        <div class="blog-card__meta">
                            <?php if (!empty($blog['date'])): ?>
                                <time>
                                    <span class="material-icons">calendar_today</span>
                                    <?php echo date('M j, Y', strtotime($blog['date'])); ?>
                                </time>
                            <?php endif; ?>
                            <?php if (!empty($blog['category']) && $blog['category'] !== 'General'): ?>
                                <?php if (!empty($blog['date'])): ?><span class="blog-card__meta-sep">•</span><?php endif; ?>
                                <span class="blog-card__category"><?php echo htmlspecialchars($blog['category']); ?></span>
                            <?php endif; ?>
                        </div>
                        <h3 class="blog-card__title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                        <?php if (!empty($blog['description'])): ?>
                            <p class="blog-card__excerpt line-clamp-3"><?php echo htmlspecialchars($blog['description']); ?></p>
                        <?php endif; ?>
                        <div class="blog-card__link">
                            Read Article
                            <span class="material-icons">arrow_forward</span>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="home-blogs__footer">
            <a href="<?php echo $LOCAL_DEV ? '/blogs.php' : '/blogs'; ?>" class="btn btn--outline">
                View all articles
                <span class="material-icons">arrow_forward</span>
            </a>
        </div>
    </div>
</section>
<?php endif; ?>
