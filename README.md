![SCR-20250821-tain](https://github.com/user-attachments/assets/b832addf-285f-44ee-a751-71d78927bcdf)

# Modern App Landing Page Template

A professional, ready-to-use landing page template for mobile apps. Built with PHP and Tailwind CSS, this template is designed for developers who want to quickly create a beautiful website for their mobile application.

## 🎨 Styling (base.css + style.css)

This template uses **plain CSS** instead of Tailwind. All visual theming lives in two files:

| File | Purpose |
|------|---------|
| `css/base.css` | Design tokens (colors, fonts, spacing, shadows), reset, animations, utilities |
| `css/style.css` | Component styles (header, hero, features, blog, FAQ, contact, footer, etc.) |

**To retheme the entire site:** edit CSS variables in `css/base.css` (`--color-brand-*`, `--color-secondary-*`, etc.) and adjust component rules in `css/style.css`.

No build step required for CSS changes — just refresh the browser.

```bash
npm run sync-fonts   # only needed once or when updating fonts
php -S localhost:8000
```

## 🚀 Quick Setup (5 Minutes)

1. **Install dependencies** (fonts only):
   ```bash
   npm install
   npm run sync-fonts
   ```

2. **Customize your app** (see below)

3. **Launch locally**:
   ```bash
   php -S localhost:8000
   ```

   Local dev uses `$LOCAL_DEV = true` in `config.php` — URLs include `.php` extensions and work without nginx.

## 🛠 Local Dev vs Production

Set in `config.php`:

```php
$LOCAL_DEV = true;   // local: php -S localhost:8000
$EXTENSION = $LOCAL_DEV ? ".php" : "";
$apiBaseUrl = $LOCAL_DEV ? 'http://localhost:8080' : 'https://api.example.com';
```

| Mode | Blog list | Blog article | Page URLs |
|------|-----------|--------------|-----------|
| Local (`$LOCAL_DEV = true`) | `/blogs.php` | `/blogs.php?article=slug` | `/faq.php` |
| Production | `/blogs` | `/blogs/slug` | `/faq` |

Production requires `nginx-rules.conf` for clean URLs. The deploy workflow (`.github/workflows/deploy.yml`) auto-sets `$LOCAL_DEV = false` on the VPS.

## 🌍 Internationalization (Locales)

Multi-language support is built in. Configure locales in `config.php`; page copy lives in `locales/{code}.php`.

### How it works

1. **`config.php`** defines which languages are enabled (`$i18n`).
2. **`_components/i18n.php`** bootstraps the active locale on every request.
3. **`locales/en.php`** holds all English content arrays (`$home`, `$common`, `$footer`, `$ui`, etc.).
4. **Other locales** (e.g. `locales/es.php`) load English first, then override translated strings.
5. **Templates** render copy via `t('key')` for short UI labels and locale arrays for longer sections.
6. **Internal links** use `i18n_locale_url('/path')` so the correct language is preserved.
7. **Footer dropdown** (`_components/lang_switcher.php`) lets visitors switch language.

Visiting `/` with no language in the URL always shows the **default locale** (English). Other languages require an explicit URL or the footer switcher.

### URL format

| Environment | Default (English) | Spanish example |
|-------------|-------------------|-----------------|
| Local (`$LOCAL_DEV = true`) | `/`, `/blogs.php`, `/faq.php` | `/?lang=es`, `/blogs.php?lang=es`, `/faq.php?lang=es` |
| Production | `/`, `/blogs`, `/faq` | `/es`, `/es/blogs`, `/es/faq` |

Production uses a **path prefix** for non-default locales. English stays unprefixed. Local dev uses a **`?lang=`** query parameter instead (no nginx locale rules needed).

Legacy production URLs with `?lang=es` are **301-redirected** to `/es/...`.

### Locale detection order

On each request, the active locale is resolved in this order:

1. **Path prefix** (production only) — e.g. `/es/blogs` → `es`
2. **Query parameter** (local dev) — e.g. `?lang=es` → `es`
3. **Cookie** — only if `'respectSavedLocale' => true` in config
4. **Browser `Accept-Language`** — only if `'detectFromBrowser' => true` in config
5. **Fallback** — `defaultLocale` (`en`)

### Config options (`config.php`)

```php
$i18n = [
    'enabled' => true,
    'defaultLocale' => 'en',
    'locales' => [
        'en' => ['label' => 'English', 'hreflang' => 'en'],
        'es' => ['label' => 'Español', 'hreflang' => 'es'],
    ],
    'showSwitcher' => true,           // footer language dropdown
    'persistInCookie' => true,        // save choice when user switches explicitly
    'cookieName' => 'site_lang',
    'queryParam' => 'lang',           // local dev only
    'detectFromBrowser' => false,     // false = / always English until user picks a language
    'respectSavedLocale' => false,    // false = cookie does not override unprefixed URLs
];
```

Set `'enabled' => false` to disable i18n entirely (site runs in English only).

### Content files

| File | Role |
|------|------|
| `locales/en.php` | Full default content — all `$home`, `$common`, `$footer`, `$faqs`, `$ui`, etc. |
| `locales/es.php` | `require`s `en.php`, then overrides translated keys |

**Short UI strings** live in the `$ui` array and are output with:

```php
<?php echo htmlspecialchars(t('header_download')); ?>
```

**Longer sections** (hero, features, FAQ) use the named arrays directly, e.g. `<?php echo $home['title']; ?>` — override those arrays in the locale file.

Spanish example pattern:

```php
<?php
require __DIR__ . '/en.php';

$ui = array_merge($ui, [
    'header_download' => 'Descargar',
    'blog_view_all' => 'Ver todos los artículos',
    // ...
]);

$home['title'] = "Controla tus <span class='text-highlight'>suscripciones</span> sin esfuerzo";
$common['appTitle'] = 'No pierdas el control de tus suscripciones';
// ...
```

### Links and SEO

Use helpers so links stay in the active language:

```php
i18n_locale_url('/faq' . $EXTENSION);     // any internal path
i18n_switcher_url('es');                  // footer switcher targets
resource_blog_url('my-slug');             // blog URLs (config.php)
blog_url('my-slug');                      // blog URLs (blogs.php)
```

`_components/meta.php` outputs **`hreflang`** alternate links automatically for each configured locale.

### Blog articles per locale

Tag articles in YAML frontmatter:

```yaml
lang: es
```

Articles without `lang` use `defaultLocale`. Only articles matching the visitor's active locale appear in listings; others return **404** on direct access.

See `readme_articles.md` for `publish-after`, `lang`, and other frontmatter keys.

### Adding a new locale

Example: adding French (`fr`).

**1. Register the locale in `config.php`:**

```php
'locales' => [
    'en' => ['label' => 'English', 'hreflang' => 'en'],
    'es' => ['label' => 'Español', 'hreflang' => 'es'],
    'fr' => ['label' => 'Français', 'hreflang' => 'fr'],
],
```

**2. Create `locales/fr.php`:**

```php
<?php
require __DIR__ . '/en.php';

$ui = array_merge($ui, [
    'header_download' => 'Télécharger',
    'blog_view_all' => 'Voir tous les articles',
    // translate every key you need from locales/en.php → $ui
]);

$home['title'] = 'Votre titre ici';
$home['description'] = 'Votre description ici';
// override $common, $footer, $faqs, $featuresIcons, etc. as needed
```

**3. Translate content** — at minimum override `$ui` and main page arrays (`$home`, `$common`, `$faqs`, `$bottomCta`, `$howItWorks`, …). Copy `locales/es.php` as a starting template.

**4. Add blog posts** (optional) — create `articles/*.md` with `lang: fr` in frontmatter.

**5. Production nginx** — locale rewrites in `nginx-rules.conf` already match any two-letter code (`[a-z]{2}`), so `/fr/blogs`, `/fr/faq`, etc. work without rule changes.

**6. Regenerate sitemap:**

```bash
python3 sitemap-generator.py
```

Non-default locale blog URLs are emitted as `/fr/blogs/slug`.

**7. Test locally:**

```
http://localhost:8000/?lang=fr
http://localhost:8000/blogs.php?lang=fr
```

Use the footer language dropdown to verify switching between locales.

### Key files

| File | Purpose |
|------|---------|
| `config.php` | `$i18n` config, loads locale file, `resource_blog_url()` |
| `_components/i18n.php` | Bootstrap, `t()`, `i18n_locale_url()`, hreflang helpers |
| `_components/lang_switcher.php` | Footer dropdown UI |
| `locales/en.php` | Default (English) content |
| `locales/es.php` | Example secondary locale |
| `nginx-rules.conf` | Production `/es/…` rewrites |

## 📝 Blog System

File-based blog — no database, no build step.

1. Add a `.md` file to `articles/` with YAML frontmatter:

```markdown
---
title: Your Article Title
description: 150–160 char meta description for SEO.
author: Your Team
date: 2026-01-15
image: /assets/cover/your-slug.webp
category: Guides
banner: true
cta: true
---

Your markdown content here...
```

2. Visit `/blogs.php` (local) or `/blogs` (production).

**Frontmatter keys:** `title`, `description`, `author`, `date`, `image`, `category`, `banner`, `cta`, `replace` (301 redirect to another slug), `publish-after` (scheduled publish), `lang` (locale code).

**Components:** homepage teaser (`home_blogs_section.php`), sticky download banner, in-article CTA, Prism syntax highlighting.

See `readme_articles.md` for full authoring guide (including `lang` and `publish-after`).

### Python content tools

| Script | Purpose |
|--------|---------|
| `scripts/generate-article-placeholders.py` | Generate 1280×720 WebP placeholders for covers & step screenshots |
| `scripts/validate-blog-content.py` | Validate articles against brief manifests (word count, H2s, FAQ, links) |
| `scripts/compose-article-screenshots.py` | Composite tutorial screenshots with phone mockup |
| `sitemap-generator.py` | Regenerate `sitemap.xml` with pages + blog posts + tools |

```bash
python3 scripts/generate-article-placeholders.py --cover-line "Your Title" -o assets/cover/my-slug.webp
python3 sitemap-generator.py
```

## 🎯 Niche Landing Pages (Optional)

Pre-built SEO landing page infrastructure for platform-specific pages (WordPress-to-App, Shopify-to-App, etc.):

- `_components/niche_landing_page.php`, `niche_pages_data.php`, `niche_modals.php`
- Entry points: `wordpress-to-app.php`, `shopify-to-app.php`, etc.

Enable in `config.php` footer:

```php
'convertLinks' => [
    ['title' => 'WordPress to App', 'link' => '/wordpress-to-app' . $EXTENSION, 'isExternal' => false],
],
```

Customize content in `_components/niche_pages_data.php`.

## 🔧 Tools (Optional)

Standalone tool pages under `tools/` (e.g. JKS upload certificate). Add to footer:

```php
'toolLinks' => [
    ['title' => 'JKS Upload Certificate', 'link' => '/tools/jks/upload-certificate' . ($LOCAL_DEV ? '/index.php' : ''), 'isExternal' => false],
],
```

Set `$apiBaseUrl` in `config.php` for backend API calls.

## 📝 How to Customize for Your App

### Step 1: Update App Information
Edit `config.php` and update these basic details:

```php
$common = [
    'appName' => "Your App Name",                    // Replace with your app name
    'appTitle' => "Your app tagline here",           // Main headline
    'appDescription' => "Describe what your app does", // App description
    'appIcon' => "/assets/app_icon.webp",            // Your app icon
    'supportEmail' => "support@yourapp.com",         // Your support email
    'appStoreUrl' => "https://apps.apple.com/...",   // Your App Store link
    'googlePlayUrl' => "https://play.google.com/...", // Your Google Play link
    'screenshotRoundedCorners' => true,              // true = rounded, false = sharp corners
];
```

### Step 2: Add Your App Screenshots
1. Place your screenshots in the `/assets/` folder
2. Update the paths in `config.php`:

```php
// Hero section screenshot
$home = [
    "screenshot" => "/assets/your_main_screenshot.png",
];

// Feature screenshots
$featuresScreenshots = [
    "featuresList" => [
        [
            "title" => "Easy Setup",
            "description" => "Get started in minutes",
            "image" => "/assets/feature_1.png",      // Your screenshot here
        ],
        [
            "title" => "Smart Notifications", 
            "description" => "Never miss important updates",
            "image" => "/assets/feature_2.png",      // Your screenshot here
        ],
        // Add more features...
    ]
];
```

### Step 3: Customize App Features
Update the features that appear on your homepage:

```php
// Features with icons (no screenshots needed)
$featuresIcons = [
    "featuresList" => [
        [
            "title" => "Fast & Reliable",
            "description" => "Lightning fast performance",
            "icon" => "speed",                       // Material Design icon name
        ],
        [
            "title" => "Secure",
            "description" => "Your data is always protected", 
            "icon" => "security",
        ],
    ]
];
```

### Step 4: Add Customer Reviews
```php
$ratings = [
    "ratingsList" => [
        [
            "title" => "John Smith",
            "description" => "This app changed how I work!",
            "rating" => 5,
            "image" => "/assets/user_1.jpg",         // User photo (optional)
        ],
        // Add more reviews...
    ]
];
```

### Step 5: Update Footer & Contact Info
```php
$footer = [
    'navigation' => [
        ['title' => 'About', 'link' => '/about'],
        ['title' => 'Features', 'link' => '/features'],
        ['title' => 'Support', 'link' => '/contact'],
    ],
    'socials' => [
        ['title' => 'Twitter', 'link' => 'https://twitter.com/yourapp'],
        ['title' => 'Instagram', 'link' => 'https://instagram.com/yourapp'],
    ],
];
```

## 🎨 Design Options

### Theming
Edit CSS variables in `css/base.css` to change colors, fonts, spacing, and shadows site-wide. Component layout and structure is in `css/style.css`.

### Screenshot Corners
Set `'screenshotRoundedCorners' => false` in config.php for sharp corners, or `true` for rounded corners.

## 📱 What You Get

- **Homepage**: Enhanced hero, how-it-works, features, reviews, blog teaser, download CTA
- **Multi-language**: Config-driven locales, footer switcher, path-prefix URLs in production
- **Blog System**: Markdown articles, pagination, categories, SEO/JSON-LD, syntax highlighting
- **Contact Page**: Contact form and information
- **FAQ Page**: Expandable questions and answers
- **Legal Pages**: Privacy policies and terms (required for app stores)
- **Smart Downloads**: Automatically detects user's device and shows correct download link
- **Redeem Code Campaign**: Giveaway page with JSON file storage
- **Niche Landing Pages**: Optional platform-specific SEO pages
- **Deploy Workflow**: GitHub Actions auto-release + VPS rsync deploy
- **Performance**: Self-hosted Geist fonts, LCP preload, deferred motion, content-visibility

## 🚀 Going Live

1. **Sync fonts** (if not done):
   ```bash
   npm run sync-fonts
   ```

2. **Configure nginx** using `nginx-rules.conf` for clean URLs

3. **Set `$LOCAL_DEV = false`** in `config.php` (or use deploy workflow)

4. **Upload files** to your web server (or configure GitHub Actions secrets: `VPS_SSH_KEY`, `VPS_HOST`, `VPS_PORT`, `VPS_USER`, `VPS_WEB_DIR`)

5. **Regenerate sitemap**: `python3 sitemap-generator.py`

6. **Test on mobile** to ensure everything works correctly

## 💡 Tips for Success

- **Use high-quality screenshots** - they're the most important element
- **Keep descriptions short** and focused on benefits
- **Add real customer reviews** - they build trust
- **Test on mobile devices** - most visitors will be on phones
- **Update app store ratings** regularly in config.php

## 🆘 Need Help?

- Check `config.php` - 90% of customization happens there
- All images go in `/assets/` folder
- Run `npm run sync-fonts` after cloning (fonts only)
- Test with `php -S localhost:8000`

---

**Ready to showcase your app? Start customizing config.php! 🚀**
