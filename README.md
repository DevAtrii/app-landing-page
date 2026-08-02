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

**Frontmatter keys:** `title`, `description`, `author`, `date`, `image`, `category`, `banner`, `cta`, `replace` (301 redirect to another slug).

**Components:** homepage teaser (`home_blogs_section.php`), sticky download banner, in-article CTA, Prism syntax highlighting.

See `readme_articles.md` for full authoring guide.

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
