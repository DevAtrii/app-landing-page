<?php
require_once __DIR__ . '/../config.php';
?>

<footer class="site-footer">
    <div class="site-footer__decor">
        <svg class="site-footer__decor-circle" fill="currentColor" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="50"></circle>
        </svg>
        <svg class="site-footer__decor-square" fill="currentColor" viewBox="0 0 100 100">
            <rect width="100" height="100" rx="20" transform="rotate(45 50 50)"></rect>
        </svg>
    </div>

    <div class="container site-footer__inner">
        <div class="site-footer__grid">
            <div class="site-footer__brand-col">
                <div class="site-footer__brand">
                    <div class="site-footer__brand-icon-wrap">
                        <img src="<?php echo $common['appIcon']; ?>" alt="<?php echo $common['appName']; ?>" class="site-footer__brand-icon">
                    </div>
                    <span class="site-footer__brand-name"><?php echo $common['appName']; ?></span>
                </div>
                <p class="site-footer__desc"><?php echo $footer['description']; ?></p>
                
                <div class="site-footer__downloads">
                    <?php if ($common['appStoreUrl']): ?>
                        <a href="<?php echo $common['appStoreUrl']; ?>" target="_blank" class="store-badge store-badge--md">
                            <img src="./assets/app-store-download.svg" alt="Download on the App Store">
                        </a>
                    <?php endif; ?>
                    <?php if ($common['googlePlayUrl']): ?>
                        <a href="<?php echo $common['googlePlayUrl']; ?>" target="_blank" class="store-badge store-badge--md">
                            <img src="/assets/google-play-download.svg" alt="Get it on Google Play">
                        </a>
                    <?php endif; ?>
                </div>
            </div>
            
            <div>
                <h3 class="site-footer__col-title"><?php echo htmlspecialchars(t('footer_navigation')); ?></h3>
                <ul class="site-footer__links">
                    <?php foreach ($footer['navigation'] as $nav): ?>
                        <li>
                            <a href="<?php echo $nav['link']; ?>" 
                               <?php echo $nav['isExternal'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>
                               class="site-footer__link">
                                <span class="site-footer__link-dot"></span>
                                <?php echo $nav['title']; ?>
                                <?php if ($nav['isExternal']): ?>
                                    <span class="material-icons">open_in_new</span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <?php if (!empty($footer['convertLinks'])): ?>
            <div>
                <h3 class="site-footer__col-title">Convert to App</h3>
                <ul class="site-footer__links">
                    <?php foreach ($footer['convertLinks'] as $link): ?>
                        <li>
                            <a href="<?php echo $link['link']; ?>" class="site-footer__link">
                                <span class="site-footer__link-dot"></span>
                                <?php echo $link['title']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <?php if (!empty($footer['toolLinks'])): ?>
            <div>
                <h3 class="site-footer__col-title">Tools</h3>
                <ul class="site-footer__links">
                    <?php foreach ($footer['toolLinks'] as $link): ?>
                        <li>
                            <a href="<?php echo $link['link']; ?>" class="site-footer__link">
                                <span class="site-footer__link-dot"></span>
                                <?php echo $link['title']; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>

            <div>
                <h3 class="site-footer__col-title"><?php echo htmlspecialchars(t('footer_follow')); ?></h3>
                <ul class="site-footer__links">
                    <?php foreach ($footer['socials'] as $social): ?>
                        <li>
                            <a href="<?php echo $social['link']; ?>" 
                               target="_blank" rel="noopener noreferrer"
                               class="site-footer__link site-footer__link--social">
                                <span class="site-footer__link-dot"></span>
                                <?php echo $social['title']; ?>
                                <span class="material-icons">open_in_new</span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

        <?php if (!empty($footer['resourceSections'])): ?>
        <div class="site-footer__resources">
            <?php foreach ($footer['resourceSections'] as $section): ?>
            <div>
                <h3 class="site-footer__col-title"><?php echo htmlspecialchars($section['title']); ?></h3>
                <ul class="site-footer__links" style="margin-bottom: 1.5rem;">
                    <?php foreach ($section['links'] as $link): ?>
                        <li>
                            <a href="<?php echo resource_blog_url($link['slug']); ?>" class="site-footer__link">
                                <span class="site-footer__link-dot"></span>
                                <?php echo htmlspecialchars($link['title']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <a href="<?php echo resource_blog_url('', $section['viewAllCategory'] ?? null); ?>" class="btn btn--outline">
                    <?php echo htmlspecialchars(t('footer_view_all')); ?>
                    <span class="material-icons">arrow_forward</span>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <div class="site-footer__legal">
            <div class="site-footer__legal-inner">
                <div class="site-footer__legal-start">
                    <?php include __DIR__ . '/lang_switcher.php'; ?>
                    <div class="site-footer__legal-links">
                    <?php foreach ($footer['legal'] as $legal): ?>
                        <a href="<?php echo $legal['link']; ?>" class="site-footer__legal-link">
                            <?php echo $legal['title']; ?>
                        </a>
                    <?php endforeach; ?>
                    </div>
                </div>
                <div class="site-footer__copyright">
                    <p class="site-footer__copyright-text"><?php echo $footer['copyright']; ?></p>
                    <p class="site-footer__message">
                        <?php echo str_replace("❤️", '<span class="site-footer__heart animate-pulse">❤️</span>', $footer['message']); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
