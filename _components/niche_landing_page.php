<?php
/**
 * Reusable niche landing page body (hero through FAQ).
 * Expects $nichePage array from niche_pages_data.php.
 */
if (empty($nichePage)) {
    return;
}

$downloadUrl = '/download' . ($EXTENSION ?? '');
$platform = htmlspecialchars($nichePage['platformName'] ?? 'Website');
$nicheKey = $nichePage['slug'] ?? '';
$urlPlaceholder = $nichePage['urlPlaceholder'] ?? 'https://yoursite.com';
$sections = $nichePage['sections'] ?? [];
$statsSection = $sections['stats'] ?? ['eyebrow' => 'Outcomes', 'title' => 'Why ' . $platform . ' founders choose an app', 'subtitle' => 'Real outcomes from wrapping your site instead of hoping mobile browsers convert.'];
$benefitsSection = $sections['benefits'] ?? ['eyebrow' => 'Benefits', 'title' => 'Built for ' . $platform . ', not generic wrappers'];
$useCasesSection = $sections['useCases'] ?? ['eyebrow' => 'Use cases', 'title' => 'Who this is for', 'subtitle' => $platform . ' owners who want a real Android app without rebuilding their stack from scratch.'];
$compareSection = $sections['compare'] ?? ['eyebrow' => 'Compare', 'title' => 'How WebInto.app compares', 'subtitle' => 'See why founders choose a signed Android shell over plugins, bookmarks, or a full native rewrite.'];
$faqSection = $sections['faq'] ?? ['eyebrow' => 'FAQ', 'title' => 'Frequently asked questions', 'subtitle' => 'Common questions about converting ' . $platform . ' to Android with WebInto.app.'];
$nicheTutorials = $nichePage['tutorials'] ?? [
    ['slug' => 'how-to-convert-website-to-android-app-without-coding', 'title' => 'Web to app step-by-step', 'icon' => 'rocket_launch'],
    ['slug' => 'how-to-upload-app-google-play-store', 'title' => 'Upload to Google Play', 'icon' => 'store'],
    ['slug' => 'onesignal-sdk-javascript-webinto-app', 'title' => 'Push notifications setup', 'icon' => 'notifications_active'],
];
$tutorialsIntro = $nichePage['tutorialsIntro'] ?? 'Our tutorials cover the wizard, Play Store upload, push notifications, and signing keys.';
$nicheHeroPartial = __DIR__ . '/niche/' . $nicheKey . '_hero.php';
$nicheHighlightPartial = __DIR__ . '/niche/' . $nicheKey . '_highlight.php';
$comparisonHeaders = $nichePage['comparisonHeaders'] ?? [
    'feature' => 'Feature',
    'other' => 'Typical plugin / wrapper',
    'webinto' => 'WebInto.app',
];
$otherKey = array_key_exists('plugin', $nichePage['comparison'][0] ?? [])
    ? 'plugin'
    : (array_key_exists('shopifyApp', $nichePage['comparison'][0] ?? []) ? 'shopifyApp' : (array_key_exists('rewrite', $nichePage['comparison'][0] ?? []) ? 'rewrite' : 'pwa'));

function niche_blog_url(string $slug): string
{
    global $LOCAL_DEV;

    return ($LOCAL_DEV ?? false)
        ? '/blogs.php?article=' . rawurlencode($slug)
        : '/blogs/' . rawurlencode($slug);
}

function niche_comparison_tone(string $value, bool $isWebinto): array
{
    $normalized = strtolower(trim($value));

    if ($isWebinto) {
        return ['icon' => 'check_circle', 'iconClass' => 'text-green-500', 'textClass' => 'font-bold text-brand-800'];
    }

    $negative = ['no', 'rare', 'limited', 'basic', 'varies', 'bookmark', 'unreliable', 'separate', 'weeks', 'months'];
    foreach ($negative as $word) {
        if (str_contains($normalized, $word)) {
            return ['icon' => 'cancel', 'iconClass' => 'text-red-400', 'textClass' => 'text-gray-600'];
        }
    }

    if (preg_match('/\$|\/mo\b|hire|often/', $normalized)) {
        return ['icon' => 'remove_circle', 'iconClass' => 'text-amber-500', 'textClass' => 'text-gray-600'];
    }

    if (in_array($normalized, ['yes', 'full control'], true) || str_starts_with($normalized, 'yes')) {
        return ['icon' => 'check_circle', 'iconClass' => 'text-green-500', 'textClass' => 'text-gray-700'];
    }

    return ['icon' => 'info', 'iconClass' => 'text-gray-400', 'textClass' => 'text-gray-600'];
}

function niche_render_comparison_cell(string $value, bool $isWebinto): string
{
    $tone = niche_comparison_tone($value, $isWebinto);

    return sprintf(
        '<span class="inline-flex items-start sm:items-center gap-2 %s"><span class="material-icons text-xl flex-shrink-0 %s" aria-hidden="true">%s</span><span>%s</span></span>',
        $tone['textClass'],
        $tone['iconClass'],
        $tone['icon'],
        htmlspecialchars($value)
    );
}

?>

<!-- Hero -->
<section class="pt-24 pb-20 relative overflow-hidden bg-[#fafafa]" id="niche-hero">
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <svg class="absolute -top-24 -right-24 w-96 h-96 text-brand-100 opacity-50" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path fill="currentColor" d="M44.7,-76.4C58.8,-69.2,71.8,-59.1,81.3,-46.3C90.8,-33.5,96.8,-18,96.5,-2.9C96.2,12.2,89.6,26.9,80.1,39.6C70.6,52.3,58.2,63,44.2,71.4C30.2,79.8,15.1,85.9,0.3,85.4C-14.5,84.9,-29,77.8,-42.6,69.1C-56.2,60.4,-68.9,50.1,-77.4,37.3C-85.9,24.5,-90.2,9.2,-88.4,-5.4C-86.6,-20,-78.7,-33.9,-68.6,-45.1C-58.5,-56.3,-46.3,-64.8,-33.4,-72.5C-20.5,-80.2,-7.4,-87.1,4.4,-94.9C16.2,-102.7,30.6,-83.6,44.7,-76.4Z" transform="translate(100 100)"/>
        </svg>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="text-center lg:text-left">
                <span class="inline-flex items-center px-4 py-2 rounded-full bg-brand-50 text-brand-600 font-bold text-sm mb-6">
                    <span class="w-2 h-2 rounded-full bg-brand-500 mr-2 animate-pulse"></span>
                    <?php echo htmlspecialchars($nichePage['badge']); ?>
                </span>
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-black font-heading text-gray-900 mb-6 leading-[1.1] tracking-tight">
                    <?php echo $nichePage['h1']; ?>
                </h1>
                <p class="text-lg md:text-xl text-gray-600 mb-8 leading-relaxed font-medium">
                    <?php echo htmlspecialchars($nichePage['heroDescription']); ?>
                </p>

                <!-- URL → Get App CTA -->
                <form id="niche-convert-form" class="max-w-xl mx-auto lg:mx-0" novalidate>
                    <label for="niche-site-url" class="sr-only">Website URL</label>
                    <div class="flex flex-col sm:flex-row gap-3 p-2 bg-white rounded-2xl border-2 border-gray-200 shadow-soft focus-within:border-brand-400 focus-within:ring-2 focus-within:ring-brand-100 transition-all">
                        <input type="url" id="niche-site-url" name="url" required
                            placeholder="<?php echo htmlspecialchars($urlPlaceholder); ?>"
                            class="flex-1 min-w-0 px-4 py-3.5 rounded-xl text-gray-900 placeholder-gray-400 font-medium focus:outline-none text-base"
                            autocomplete="url" inputmode="url">
                        <button type="submit" id="niche-get-app-btn"
                            class="inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-brand-500 hover:bg-brand-600 text-white font-bold text-base shadow-sm hover:shadow-float transition-all whitespace-nowrap">
                            <span class="material-icons text-xl" aria-hidden="true">rocket_launch</span>
                            Get App
                        </button>
                    </div>
                    <p id="niche-url-error" class="hidden mt-2 text-sm font-bold text-red-600 text-left" role="alert"></p>
                </form>
            </div>

            <div class="relative mx-auto max-w-sm lg:max-w-md">
                <?php if (is_file($nicheHeroPartial)) {
                    include $nicheHeroPartial;
                } else { ?>
                <div class="absolute inset-0 bg-gradient-to-tr from-brand-400 to-accent-400 rounded-[2.5rem] blur-2xl opacity-40 -z-10 translate-y-4"></div>
                <img src="<?php echo htmlspecialchars($nichePage['heroImage']); ?>"
                    alt="<?php echo $platform; ?> converted to Android app preview"
                    width="405" height="868" decoding="async"
                    class="w-full h-auto rounded-[2.5rem] border-8 border-white shadow-soft relative">
                <?php } ?>
            </div>
        </div>
    </div>
</section>

<!-- Stats / graphs -->
<section class="py-24 bg-white border-y border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-14">
            <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 font-bold text-sm tracking-wider uppercase mb-4"><?php echo htmlspecialchars($statsSection['eyebrow']); ?></span>
            <h2 class="text-3xl md:text-4xl font-black font-heading text-gray-900 mb-4"><?php echo htmlspecialchars($statsSection['title']); ?></h2>
            <p class="text-lg text-gray-600 font-medium max-w-2xl mx-auto"><?php echo htmlspecialchars($statsSection['subtitle']); ?></p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
            <?php foreach ($nichePage['stats'] as $stat): ?>
                <div class="bg-gray-50 rounded-3xl border-2 border-gray-100 p-6 lg:p-7 hover:border-brand-200 hover:shadow-soft transition-all">
                    <div class="flex justify-between items-end gap-4 mb-4">
                        <span class="text-sm font-bold text-gray-600 uppercase tracking-wide leading-snug"><?php echo htmlspecialchars($stat['label']); ?></span>
                        <span class="text-2xl lg:text-3xl font-black font-heading text-brand-600 whitespace-nowrap"><?php echo htmlspecialchars($stat['value']); ?></span>
                    </div>
                    <div class="h-3.5 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full <?php echo htmlspecialchars($stat['color']); ?> rounded-full transition-all duration-1000" style="width: <?php echo (int) $stat['percent']; ?>%"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Benefits -->
<section class="py-24 bg-[#fafafa]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 font-bold text-sm uppercase tracking-wider mb-4"><?php echo htmlspecialchars($benefitsSection['eyebrow']); ?></span>
            <h2 class="text-3xl md:text-4xl font-black font-heading text-gray-900"><?php echo htmlspecialchars($benefitsSection['title']); ?></h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($nichePage['benefits'] as $benefit): ?>
                <div class="bg-white rounded-3xl border-2 border-gray-100 p-8 shadow-soft hover:shadow-float hover:-translate-y-1 transition-all">
                    <div class="w-14 h-14 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center mb-6">
                        <span class="material-icons text-3xl"><?php echo htmlspecialchars($benefit['icon']); ?></span>
                    </div>
                    <h3 class="text-xl font-black font-heading text-gray-900 mb-3"><?php echo htmlspecialchars($benefit['title']); ?></h3>
                    <p class="text-gray-600 font-medium leading-relaxed"><?php echo htmlspecialchars($benefit['text']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if (is_file($nicheHighlightPartial)) {
    include $nicheHighlightPartial;
} ?>

<!-- Use cases -->
<section class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-12 items-start">
            <div class="lg:col-span-7">
                <span class="inline-block py-1 px-3 rounded-full bg-accent-100 text-accent-800 font-bold text-sm tracking-wider uppercase mb-4"><?php echo htmlspecialchars($useCasesSection['eyebrow']); ?></span>
                <h2 class="text-3xl md:text-4xl font-black font-heading text-gray-900 mb-4"><?php echo htmlspecialchars($useCasesSection['title']); ?></h2>
                <p class="text-lg text-gray-600 font-medium mb-8 max-w-xl leading-relaxed">
                    <?php echo htmlspecialchars($useCasesSection['subtitle']); ?>
                </p>
                <ul class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <?php foreach ($nichePage['useCases'] as $case): ?>
                        <li class="flex items-start gap-3 p-4 rounded-2xl bg-gray-50 border border-gray-100 hover:border-brand-200 hover:bg-brand-50/40 transition-colors">
                            <span class="w-9 h-9 rounded-xl bg-brand-100 text-brand-600 flex items-center justify-center flex-shrink-0">
                                <span class="material-icons text-lg" aria-hidden="true">check</span>
                            </span>
                            <span class="text-gray-800 font-medium leading-snug pt-1"><?php echo htmlspecialchars($case); ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="lg:col-span-5">
                <div class="rounded-3xl border-2 border-brand-100 bg-gradient-to-br from-brand-50 via-white to-secondary-50 p-8 shadow-soft lg:sticky lg:top-28">
                    <div class="flex items-center gap-3 mb-5">
                        <span class="w-12 h-12 rounded-2xl bg-brand-500 text-white flex items-center justify-center shadow-sm">
                            <span class="material-icons text-2xl" aria-hidden="true">menu_book</span>
                        </span>
                        <div>
                            <h3 class="text-xl font-black font-heading text-gray-900">Learn the full workflow</h3>
                            <p class="text-sm text-gray-500 font-medium">Step-by-step guides</p>
                        </div>
                    </div>
                    <p class="text-gray-600 font-medium mb-6 leading-relaxed">
                        <?php echo htmlspecialchars($tutorialsIntro); ?>
                    </p>
                    <ul class="space-y-3">
                        <?php foreach ($nicheTutorials as $tutorial): ?>
                            <li>
                                <a href="<?php echo niche_blog_url($tutorial['slug']); ?>"
                                   class="group flex items-center gap-3 w-full px-4 py-3.5 rounded-xl bg-white border-2 border-gray-100 hover:border-brand-300 hover:bg-brand-50/60 shadow-sm hover:shadow-md transition-all">
                                    <span class="w-9 h-9 rounded-lg bg-brand-50 text-brand-600 flex items-center justify-center flex-shrink-0 group-hover:bg-brand-100 transition-colors">
                                        <span class="material-icons text-lg" aria-hidden="true"><?php echo htmlspecialchars($tutorial['icon']); ?></span>
                                    </span>
                                    <span class="flex-1 font-bold text-gray-900 group-hover:text-brand-700 transition-colors"><?php echo htmlspecialchars($tutorial['title']); ?></span>
                                    <span class="material-icons text-brand-400 group-hover:text-brand-600 group-hover:translate-x-0.5 transition-all" aria-hidden="true">arrow_forward</span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Comparison table -->
<section class="py-24 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block py-1 px-3 rounded-full bg-brand-100 text-brand-700 font-bold text-sm tracking-wider uppercase mb-4"><?php echo htmlspecialchars($compareSection['eyebrow']); ?></span>
            <h2 class="text-3xl md:text-4xl font-black font-heading text-gray-900 mb-4"><?php echo htmlspecialchars($compareSection['title']); ?></h2>
            <p class="text-lg text-gray-600 font-medium max-w-2xl mx-auto"><?php echo htmlspecialchars($compareSection['subtitle']); ?></p>
        </div>

        <div class="overflow-x-auto rounded-3xl border-2 border-gray-200 bg-white shadow-soft">
            <table class="w-full min-w-[560px] border-collapse">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th scope="col" class="text-left p-5 font-bold text-gray-900 border-b border-gray-200 w-[34%]"><?php echo htmlspecialchars($comparisonHeaders['feature']); ?></th>
                        <th scope="col" class="text-left p-5 font-bold text-gray-500 border-b border-gray-200 w-[33%]"><?php echo htmlspecialchars($comparisonHeaders['other']); ?></th>
                        <th scope="col" class="text-left p-5 font-bold text-brand-700 border-b border-brand-200 bg-brand-50/70 w-[33%]">
                            <span class="inline-flex items-center gap-2">
                                <span class="material-icons text-brand-500 text-lg" aria-hidden="true">verified</span>
                                <?php echo htmlspecialchars($comparisonHeaders['webinto']); ?>
                            </span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nichePage['comparison'] as $index => $row): ?>
                        <tr class="<?php echo $index % 2 === 0 ? 'bg-white' : 'bg-gray-50/50'; ?> border-b border-gray-100 last:border-0">
                            <td class="p-5 font-bold text-gray-900 align-middle"><?php echo htmlspecialchars($row['feature']); ?></td>
                            <td class="p-5 align-middle"><?php echo niche_render_comparison_cell($row[$otherKey] ?? '', false); ?></td>
                            <td class="p-5 align-middle bg-brand-50/40"><?php echo niche_render_comparison_cell($row['webinto'], true); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>

<!-- FAQ -->
<section class="py-24 bg-white" id="faq">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <span class="inline-block py-1 px-3 rounded-full bg-secondary-100 text-secondary-800 font-bold text-sm tracking-wider uppercase mb-4"><?php echo htmlspecialchars($faqSection['eyebrow']); ?></span>
            <h2 class="text-3xl md:text-4xl font-black font-heading text-gray-900 mb-4"><?php echo htmlspecialchars($faqSection['title']); ?></h2>
            <p class="text-lg text-gray-600 font-medium"><?php echo htmlspecialchars($faqSection['subtitle']); ?></p>
        </div>
        <div class="space-y-4">
            <?php foreach ($nichePage['faq'] as $item): ?>
                <details class="group bg-white rounded-3xl border-2 border-gray-100 shadow-soft open:border-brand-200 open:shadow-md transition-all overflow-hidden">
                    <summary class="cursor-pointer list-none px-6 py-5 font-bold text-gray-900 flex justify-between items-center gap-4 hover:bg-brand-50/40 transition-colors">
                        <span class="text-left text-lg font-heading"><?php echo htmlspecialchars($item['q']); ?></span>
                        <span class="material-icons text-brand-500 group-open:rotate-180 transition-transform flex-shrink-0 bg-brand-50 rounded-full p-1" aria-hidden="true">expand_more</span>
                    </summary>
                    <div class="px-6 pb-6 pt-0 border-t border-gray-100">
                        <p class="text-gray-600 font-medium leading-relaxed pt-4"><?php echo htmlspecialchars($item['a']); ?></p>
                    </div>
                </details>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php
// JSON-LD FAQ
if (!empty($nichePage['faq'])) {
    $faqEntities = array_map(function ($item) {
        return [
            '@type' => 'Question',
            'name' => $item['q'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $item['a'],
            ],
        ];
    }, $nichePage['faq']);
    echo '<script type="application/ld+json">' . json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'FAQPage',
        'mainEntity' => $faqEntities,
    ], JSON_UNESCAPED_SLASHES | JSON_HEX_TAG) . '</script>';
}
