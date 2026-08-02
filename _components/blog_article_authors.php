<?php
/**
 * Blog author byline — expects $articleAuthors array from blogArticleAuthors()
 */
if (empty($articleAuthors)) {
    return;
}
?>
<div class="blog-authors">
    <?php foreach ($articleAuthors as $author): ?>
        <?php if ($author['linked'] && !empty($author['url'])): ?>
            <a href="<?php echo htmlspecialchars($author['url']); ?>" class="blog-author-chip">
                <?php if (!empty($author['photo'])): ?>
                    <img src="<?php echo htmlspecialchars($author['photo']); ?>"
                         alt=""
                         class="blog-author-chip__photo"
                         width="32"
                         height="32"
                         loading="lazy"
                         onerror="this.style.display='none'">
                <?php else: ?>
                    <span class="blog-author-chip__avatar" aria-hidden="true">
                        <span class="material-icons">person</span>
                    </span>
                <?php endif; ?>
                <span class="blog-author-chip__text">
                    <span class="blog-author-chip__name"><?php echo htmlspecialchars($author['name']); ?></span>
                    <?php if (!empty($author['role'])): ?>
                        <span class="blog-author-chip__role"><?php echo htmlspecialchars($author['role']); ?></span>
                    <?php endif; ?>
                </span>
            </a>
        <?php else: ?>
            <span class="blog-author-chip blog-author-chip--static">
                <span class="blog-author-chip__avatar" aria-hidden="true">
                    <span class="material-icons">person</span>
                </span>
                <span class="blog-author-chip__text">
                    <span class="blog-author-chip__name"><?php echo htmlspecialchars($author['name']); ?></span>
                </span>
            </span>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
