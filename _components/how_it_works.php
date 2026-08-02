<?php
require_once __DIR__ . '/../config.php';

if (empty($howItWorks['steps'])) {
    return;
}

$colorMap = [
    'brand' => 'how-it-works__icon--brand',
    'accent' => 'how-it-works__icon--accent',
    'secondary' => 'how-it-works__icon--secondary',
];
?>

<section class="section section--lg how-it-works">
    <div class="how-it-works__bg-blob how-it-works__bg-blob--1"></div>
    <div class="how-it-works__bg-blob how-it-works__bg-blob--2"></div>
    <div class="how-it-works__bg-blob how-it-works__bg-blob--3"></div>

    <div class="container">
        <div class="section-header section-header--lg">
            <?php if (!empty($howItWorks['badge'])): ?>
            <span class="section-header__badge section-header__badge--secondary"><?php echo htmlspecialchars($howItWorks['badge']); ?></span>
            <?php endif; ?>
            <h2 class="section-header__title section-header__title--xl"><?php echo htmlspecialchars($howItWorks['title']); ?></h2>
            <p class="section-header__desc section-header__desc--lg"><?php echo htmlspecialchars($howItWorks['description']); ?></p>
        </div>

        <div class="relative">
            <div class="how-it-works__line"></div>
            <div class="how-it-works__steps">
                <?php foreach ($howItWorks['steps'] as $index => $step):
                    $colorClass = 'how-it-works__icon--' . ($step['color'] ?? 'brand');
                ?>
                    <div class="how-it-works__step">
                        <div class="how-it-works__icon <?php echo $colorClass; ?>">
                            <span class="material-icons"><?php echo $step['icon']; ?></span>
                        </div>
                        <div class="how-it-works__number"><?php echo $index + 1; ?></div>
                        <h3 class="how-it-works__step-title"><?php echo htmlspecialchars($step['title']); ?></h3>
                        <p class="how-it-works__step-desc"><?php echo htmlspecialchars($step['description']); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>
