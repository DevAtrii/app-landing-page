# Articles guide for AI agents

This site has **no `/articles/` route**. All blog content lives as Markdown files in `articles/` and is rendered by `blogs.php`. Read this before creating, editing, or linking articles.

---

## How routing works

| Environment | Blog list | Single article |
|-------------|-----------|----------------|
| Production (`LOCAL_DEV = false` in `config.php`) | `/blogs` | `/blogs/{slug}` |
| Local dev (`LOCAL_DEV = true`) | `/blogs.php` | `/blogs.php?article={slug}` |

- **Slug** = filename without `.md` (e.g. `articles/how-to-upload-app-google-play-store.md` → slug `how-to-upload-app-google-play-store`).
- Nginx rewrites `/blogs/{slug}` to `blogs.php?article={slug}` in production.
- There is **no** `articles/` URL path. Never link to `/articles/...`.

---

## Adding a new article

1. Create `articles/your-article-slug.md` (lowercase, hyphens, descriptive).
2. Add YAML frontmatter between `---` markers (see below).
3. Write the body in Markdown below the closing `---`.
4. Add a cover image under `assets/cover/` and reference it in frontmatter.
5. Add inline images under `assets/images/` with descriptive filenames.
6. Run `python3 sitemap-generator.py` to add `/blogs/{slug}` to `sitemap.xml`.

No build step, database, or route registration is required beyond dropping the file.

---

## Frontmatter (metadata)

Supported keys parsed by `_components/markdown_parser.php` and `blogs.php`:

| Key | Required | Description |
|-----|----------|-------------|
| `title` | Yes | Page `<title>`, H1 on article page, Open Graph headline, JSON-LD. |
| `description` | Yes | Meta description, blog card excerpt, OG description. Target ~150–160 chars for SEO articles. |
| `author` | Recommended | Display name under the title (e.g. `"WebInto.app Team"`). |
| `date` | Recommended | ISO date `YYYY-MM-DD`. Used for sorting (newest first) and sitemap `lastmod`. |
| `image` | Recommended | Cover image path from site root, e.g. `"/assets/cover/my-article.webp"`. Used in blog cards, OG image, and hero on the article page. Prefer **16:9** WebP under `assets/cover/`. |
| `category` | Optional | Shown as a badge (e.g. `"Tutorials"`). Used for `?category=` filtering on the blog list. Defaults to `"General"` in listings if omitted. |
| `banner` | Optional | Mobile sticky download bar after scroll. **Default: on.** Set `banner: false` to disable. |
| `cta` | Optional | Bottom in-article download CTA (app icon + Google Play badge → `/download`). **Default: on.** Set `cta: false` to disable. |
| `replace` | Optional | **Redirect & hide:** 301 redirect this URL to another article slug; excluded from blog list and homepage blog section. See [Legacy redirects](#legacy-redirects). |

### Example frontmatter

```yaml
---
title: "How to Convert a Website Into an Android App Without Coding"
description: "Convert website to app without coding: web to APK step-by-step with WebInto.app. Remote updates, custom code, APK, AAB, and keystore."
author: "WebInto.app Team"
date: "2026-05-23"
image: "/assets/cover/how-to-convert-website-to-android-app-without-coding.webp"
category: "Tutorials"
---
```

### Boolean frontmatter values

For `banner` and `cta`, these count as **off**: `false`, `0`, `no`, `off` (case-insensitive). Anything else or omitted = **on**.

---

## Internal links between articles

**Always use production-style paths in Markdown**, even when developing locally:

```markdown
[custom HTML no-internet screen](/blogs/custom-html-no-internet-screen)
[OneSignal JavaScript bridge](/blogs/onesignal-sdk-javascript-webinto-app)
[Google Play upload guide](/blogs/how-to-upload-app-google-play-store)
```

### Rules

| Do | Don't |
|----|--------|
| `/blogs/{slug}` | `/articles/{slug}` |
| `/blogs/{slug}` | `{slug}` (bare slug, resolves relative to current URL) |
| `/blogs/{slug}` | `/blogs.php?article={slug}` in Markdown (dev-only; keep articles portable) |
| `/blogs` for the index | `/blogs.php` in Markdown |

**Slug** = target filename minus `.md`. To link to `articles/onesignal-sdk-javascript-webinto-app.md`, use `/blogs/onesignal-sdk-javascript-webinto-app`.

### External links

Use full URLs as normal:

```markdown
[WebInto.app](https://webinto.app)
[video tutorial](https://youtu.be/CvgGr70zqOE)
```

Product download from prose (optional): link to `/download` (smart redirect to Play Store on Android via `download.php`).

---

## Legacy redirects

When an old article is superseded, keep the file but add `replace` pointing at the new slug:

```yaml
---
replace: "how-to-convert-website-to-android-app-without-coding"
title: "How to Convert Any Website to an Android App in 2026 (The Complete Guide)"
...
---
```

Effects:

- Visiting `/blogs/convert-any-website-to-android-app` → **301** to `/blogs/how-to-convert-website-to-android-app-without-coding`
- Article **hidden** from blog list and home blog section
- Old URL can remain in sitemap or be removed manually; redirect handles traffic

`replace` accepts slug, `slug.md`, or `slug.webp`; only the slug is used.

---

## Images

### Cover (frontmatter `image`)

- Path: `assets/cover/{slug}.webp` (or similar descriptive name)
- Aspect ratio: **16:9** (matches `aspect-video` on article page)
- Reference: `image: "/assets/cover/your-slug.webp"`

### Inline step screenshots

- Path: `assets/images/` with SEO filenames (e.g. `webinto-app-enter-website-url.webp`)
- Reference with root-relative paths:

```markdown
![Enter website URL in WebInto.app wizard](/assets/images/webinto-app-enter-website-url.webp)

*Optional italic caption below the image.*
```

- Tutorial composites (phone mockup + text, 1280×720): regenerate with `python3 scripts/compose-article-screenshots.py` after updating raws in `assets/images/_raw-screenshots/`.
- **Gray step/cover placeholders** (before real screenshots exist): `python3 scripts/generate-article-placeholders.py` (see examples below).

#### Generate placeholder images (CLI)

```bash
python3 scripts/generate-article-placeholders.py \
  --prefix play-console-sha1-sha256 \
  --cover-file how-to-get-sha1-sha256-google-play-console.webp \
  --cover-line "How to Get SHA1 & SHA256" \
  --cover-line "Keys in Google Play Console" \
  --step "Open Protected with Play in Play Console" \
  --step "Play Store Protection → Protection" \
  --step "Manage Play App Signing" \
  --step "App signing & upload key fingerprints" \
  --step-hint "Replace with Play Console screenshot (16:9)" \
  --step-footer "SHA-1 / SHA-256 / Firebase signing guide"
```

| Flag | Purpose |
|------|---------|
| `--prefix` | Step files: `{prefix}-step-01.webp`, `-02`, … in `assets/images/` |
| `--step "..."` | Repeatable step label (auto-numbered) |
| `--step-output` + `--step-text` | Custom filenames instead of `--prefix` |
| `--cover-file` | Filename under `assets/cover/` |
| `--cover-line` | Repeatable cover title lines |
| `--cover-only` / `--steps-only` | Generate one type only |
| `--width` / `--height` | Default 1280×720 |

Requires Pillow: `pip install Pillow`

#### Generate production covers from the SEO brief manifest

The root `blog-content-briefs.json` file is the source for article titles, slugs,
and categories. Generate branded 1280×720 WebP covers for every brief with:

```bash
python3 scripts/generate-article-placeholders.py \
  --manifest blog-content-briefs.json
```

This mode automatically wraps long titles and renders a deterministic branded
background with geometric shapes. Output filenames are
`assets/cover/{slug}.webp`, matching article frontmatter.

Validate article structure, metadata, links, and covers against one or more
brief manifests:

```bash
python3 scripts/validate-blog-content.py \
  blog-content-briefs.json \
  remaining-blog-content-briefs.json
```

### Alt text

Write descriptive alt text in the `![...](...)` bracket. Good: `Upload app icon in WebInto.app First Impressions wizard step`. Avoid: `screenshot`, `image1`, `placeholder`.

---

## Markdown body conventions

- Parsed with **Parsedown** (`safeMode` off): raw HTML in `.md` is allowed.
- Code blocks get **Prism.js** highlighting in `blogs.php`.
- Tables are auto-wrapped in a scroll container on mobile.
- Use `---` horizontal rules sparingly between major sections.
- Match tone/style of existing tutorials in `articles/`.

---

## Automatic UI injected by `blogs.php`

These are **not** written in the Markdown file:

| Feature | Controlled by | Component |
|---------|---------------|-----------|
| Mobile sticky “Get App” bar | `banner` (default on) | `_components/blog_download_banner.php` |
| Bottom download CTA | `cta` (default on) | `_components/blog_article_download_cta.php` |
| Header, footer, breadcrumbs, JSON-LD | Always | `blogs.php` |

Do not duplicate the download CTA in Markdown unless there is a strong reason; use `cta: false` if the article should not show it.

---

## After publishing checklist

- [ ] Frontmatter complete (`title`, `description`, `date`, `image`)
- [ ] Internal links use `/blogs/{slug}` only
- [ ] Cover + inline images exist at referenced paths
- [ ] `python3 sitemap-generator.py` run if new slug
- [ ] Preview article at `/blogs/{slug}` (production) or `/blogs.php?article={slug}` (local)

---

## Current articles (slugs for internal linking)

| Slug | Notes |
|------|--------|
| `how-to-convert-website-to-android-app-without-coding` | Main web-to-app tutorial |
| `how-to-upload-app-google-play-store` | Play Console / AAB upload |
| `how-to-get-sha1-sha256-google-play-console` | SHA-1 / SHA-256 fingerprints (Firebase, new UI) |
| `custom-html-no-internet-screen` | Offline / error HTML |
| `onesignal-sdk-javascript-webinto-app` | Push notifications bridge |
| `permissions-bridge-javascript-webinto-app` | Permissions bridge |
| `screen-brightness-javascript-webinto-app` | Brightness bridge |
| `wordpress-site-to-android-app` | WordPress-specific |
| `pwa-to-android-app` | PWA comparison |
| `how-to-convert-html-to-app` | HTML to app |
| `convert-any-website-to-android-app` | **Redirects** → `how-to-convert-website-to-android-app-without-coding` (`replace` set) |

Glob `articles/*.md` for the full list; slugs change when files are added.

---

## Common mistakes (avoid)

1. **Linking to `/articles/...`** — route does not exist; use `/blogs/...`.
2. **Bare slug links** — `[guide](my-slug)` breaks depending on current URL; use `/blogs/my-slug`.
3. **Wrong cover path** — must start with `/assets/...`, not relative `assets/...` without leading slash (both may work in browser but leading slash is the project convention).
4. **Forgetting sitemap** — new articles are not discoverable by crawlers until `sitemap-generator.py` adds them.
5. **Setting `replace` without a valid target** — redirect is skipped if target `.md` missing; old article still renders and appears in listings.
6. **Assuming `articles/` folder URL** — the folder is storage only; public URLs always go through `blogs.php`.

---

## Key files reference

| File | Purpose |
|------|---------|
| `articles/*.md` | Article source |
| `blogs.php` | List + render + redirects |
| `_components/markdown_parser.php` | Frontmatter parse, `getAllBlogs()`, banner/CTA/replace helpers |
| `sitemap-generator.py` | Adds `/blogs/{slug}` entries |
| `scripts/compose-article-screenshots.py` | 1280×720 tutorial image compositor (phone mockup) |
| `scripts/generate-article-placeholders.py` | 1280×720 gray placeholder steps + cover |
| `_components/blog_article_download_cta.php` | In-article Play download CTA |
| `_components/blog_download_banner.php` | Mobile sticky banner |
