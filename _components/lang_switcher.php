<?php
require_once __DIR__ . '/../config.php';

if (!i18n_show_switcher()) {
    return;
}

$current = i18n_current_locale();
$currentMeta = $i18n['locales'][$current] ?? [];
$currentLabel = $currentMeta['label'] ?? strtoupper($current);
?>
<div class="lang-switcher" data-lang-switcher>
    <button type="button"
            class="lang-switcher__trigger"
            aria-label="<?php echo htmlspecialchars(t('lang_switcher_label')); ?>"
            aria-haspopup="listbox"
            aria-expanded="false"
            aria-controls="lang-switcher-menu"
            id="lang-switcher-trigger">
        <span class="material-icons lang-switcher__icon" aria-hidden="true">language</span>
        <span class="lang-switcher__current"><?php echo htmlspecialchars($currentLabel); ?></span>
        <span class="material-icons lang-switcher__chevron" aria-hidden="true">expand_more</span>
    </button>
    <ul class="lang-switcher__menu is-hidden"
        role="listbox"
        id="lang-switcher-menu"
        aria-labelledby="lang-switcher-trigger">
        <?php foreach ($i18n['locales'] as $code => $meta): ?>
            <?php $active = $code === $current; ?>
            <li role="option" aria-selected="<?php echo $active ? 'true' : 'false'; ?>">
                <a href="<?php echo htmlspecialchars(i18n_switcher_url($code)); ?>"
                   class="lang-switcher__option<?php echo $active ? ' lang-switcher__option--active' : ''; ?>"
                   hreflang="<?php echo htmlspecialchars($meta['hreflang'] ?? $code); ?>"
                   <?php echo $active ? 'aria-current="true"' : ''; ?>>
                    <?php echo htmlspecialchars($meta['label'] ?? strtoupper($code)); ?>
                    <?php if ($active): ?>
                        <span class="material-icons lang-switcher__check" aria-hidden="true">check</span>
                    <?php endif; ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<script>
(function () {
    var switcher = document.querySelector('[data-lang-switcher]');
    if (!switcher || switcher.dataset.langInit) return;
    switcher.dataset.langInit = '1';

    var trigger = switcher.querySelector('.lang-switcher__trigger');
    var menu = switcher.querySelector('.lang-switcher__menu');
    if (!trigger || !menu) return;

    function closeMenu() {
        menu.classList.add('is-hidden');
        trigger.setAttribute('aria-expanded', 'false');
        switcher.classList.remove('lang-switcher--open');
    }

    function openMenu() {
        menu.classList.remove('is-hidden');
        trigger.setAttribute('aria-expanded', 'true');
        switcher.classList.add('lang-switcher--open');
    }

    trigger.addEventListener('click', function (e) {
        e.stopPropagation();
        if (menu.classList.contains('is-hidden')) {
            openMenu();
        } else {
            closeMenu();
        }
    });

    document.addEventListener('click', function () {
        closeMenu();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeMenu();
    });

    switcher.addEventListener('click', function (e) {
        e.stopPropagation();
    });
})();
</script>
