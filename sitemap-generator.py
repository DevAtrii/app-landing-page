import os
import re
import glob
from datetime import datetime
from typing import List, Optional, Tuple
import xml.etree.ElementTree as ET

BASE_URL = "https://yoururl.app"
SITEMAP_FILE = "sitemap.xml"
ARTICLES_DIR = "articles"
AUTHORS_DIR = "authors"
TOOLS_DIR = "tools"
DEFAULT_LOCALE = "en"

# Root .php files to exclude (internals / config)
EXCLUDE_FILES = {
    "config.php",
    "BrowserDetection.php",
    "blogs.php",
    "authors.php",
}


def read_frontmatter_block(path: str, max_bytes: int = 4096) -> Optional[str]:
    try:
        with open(path, encoding="utf-8") as f:
            head = f.read(max_bytes)
    except OSError:
        return None
    if not head.startswith("---"):
        return None
    end = head.find("\n---", 3)
    if end == -1:
        return None
    return head[3:end]


def parse_frontmatter_date(path: str) -> Optional[str]:
    """Read optional ISO date from YAML frontmatter (date: YYYY-MM-DD)."""
    block = read_frontmatter_block(path, 2048)
    if block is None:
        return None
    m = re.search(r"^date:\s*[\"']?(\d{4}-\d{2}-\d{2})[\"']?\s*$", block, re.MULTILINE)
    return m.group(1) if m else None


def parse_frontmatter_publish_after(path: str) -> Optional[str]:
    """Read optional publish-after date (publish-after: YYYY-MM-DD)."""
    block = read_frontmatter_block(path)
    if block is None:
        return None
    m = re.search(
        r"^publish-after:\s*[\"']?(\d{4}-\d{2}-\d{2})[\"']?\s*$",
        block,
        re.MULTILINE,
    )
    if m:
        return m.group(1)
    m = re.search(
        r"^publish_after:\s*[\"']?(\d{4}-\d{2}-\d{2})[\"']?\s*$",
        block,
        re.MULTILINE,
    )
    return m.group(1) if m else None


def article_is_published(path: str, today: str) -> bool:
    publish_after = parse_frontmatter_publish_after(path)
    if publish_after is None:
        return True
    return today >= publish_after


def parse_frontmatter_replace(path: str) -> Optional[str]:
    """Return a redirect target when the article is a legacy replacement URL."""
    block = read_frontmatter_block(path)
    if block is None:
        return None
    m = re.search(r"^replace:\s*[\"']?([^\"'\s]+)[\"']?\s*$", block, re.MULTILINE)
    return m.group(1) if m else None


def parse_frontmatter_lang(path: str) -> str:
    block = read_frontmatter_block(path)
    if block is None:
        return DEFAULT_LOCALE
    m = re.search(r"^lang:\s*[\"']?([a-z]{2}(?:-[a-z]{2})?)\"?\s*$", block, re.MULTILINE | re.IGNORECASE)
    if not m:
        return DEFAULT_LOCALE
    return m.group(1).lower()


def discover_tool_urls() -> List[Tuple[str, str, str, str]]:
    """tools/**/index.php → /tools/{path} (clean URL, no .php)."""
    entries: List[Tuple[str, str, str, str]] = []
    if not os.path.isdir(TOOLS_DIR):
        return entries

    for dirpath, _, filenames in os.walk(TOOLS_DIR):
        if "index.php" not in filenames:
            continue

        rel_dir = os.path.relpath(dirpath, TOOLS_DIR)
        if rel_dir == ".":
            path = ""
        else:
            path = rel_dir.replace(os.sep, "/")

        url = f"{BASE_URL}/tools/{path}".rstrip("/")
        index_path = os.path.join(dirpath, "index.php")
        try:
            mtime = os.path.getmtime(index_path)
            lastmod = datetime.fromtimestamp(mtime).strftime("%Y-%m-%d")
        except OSError:
            lastmod = datetime.now().strftime("%Y-%m-%d")

        entries.append((url, lastmod, "monthly", "0.6"))

    return sorted(entries, key=lambda item: item[0])


def discover_author_urls() -> List[Tuple[str, str, str, str]]:
    entries: List[Tuple[str, str, str, str]] = []
    today = datetime.now().strftime("%Y-%m-%d")

    entries.append((f"{BASE_URL}/authors", today, "monthly", "0.6"))

    if not os.path.isdir(AUTHORS_DIR):
        return entries

    for file in sorted(glob.glob(os.path.join(AUTHORS_DIR, "*.php"))):
        slug = os.path.splitext(os.path.basename(file))[0]
        if not re.match(r"^[a-z0-9\-_]+$", slug):
            continue
        entries.append((f"{BASE_URL}/authors/{slug}", today, "monthly", "0.6"))

    return entries


def main():
    ET.register_namespace("", "http://www.sitemaps.org/schemas/sitemap/0.9")

    if os.path.exists(SITEMAP_FILE):
        tree = ET.parse(SITEMAP_FILE)
        root = tree.getroot()
    else:
        root = ET.Element("{http://www.sitemaps.org/schemas/sitemap/0.9}urlset")
        tree = ET.ElementTree(root)

    # Rebuild the managed blog section on every run so stale query URLs and
    # legacy redirect sources are removed instead of accumulating forever.
    for url_el in list(root):
        loc_el = next((child for child in url_el if child.tag.endswith("loc")), None)
        loc = (loc_el.text or "") if loc_el is not None else ""
        if loc in {f"{BASE_URL}/blogs", f"{BASE_URL}/blogs.php"} or loc.startswith(
            f"{BASE_URL}/blogs/"
        ) or loc in {f"{BASE_URL}/authors", f"{BASE_URL}/authors.php"} or loc.startswith(
            f"{BASE_URL}/authors/"
        ):
            root.remove(url_el)

    existing_urls = set()
    for el in root.iter():
        if el.tag.endswith("}loc") or el.tag == "loc":
            existing_urls.add(el.text)

    to_add = []
    today = datetime.now().strftime("%Y-%m-%d")

    # Canonical clean blog index.
    to_add.append((f"{BASE_URL}/blogs", today, "weekly", "0.8"))
    to_add.extend(discover_author_urls())

    # ── 1. Root-level .php pages ──────────────────────────────────────────────
    for file in glob.glob("*.php"):
        if file in EXCLUDE_FILES or file == "index.php":
            continue
        url = f"{BASE_URL}/{file}"
        if url not in existing_urls:
            to_add.append((url, today, "monthly", "0.6"))

    # ── 2. Blog articles: articles/*.md → /blogs/{slug} (nginx → blogs.php) ───
    pattern = os.path.join(ARTICLES_DIR, "*.md")
    for file in sorted(glob.glob(pattern)):
        if parse_frontmatter_replace(file):
            continue
        if not article_is_published(file, today):
            continue
        slug = os.path.splitext(os.path.basename(file))[0]
        lang = parse_frontmatter_lang(file)
        if lang != DEFAULT_LOCALE:
            url = f"{BASE_URL}/{lang}/blogs/{slug}"
        else:
            url = f"{BASE_URL}/blogs/{slug}"
        if url in existing_urls:
            continue
        lastmod = parse_frontmatter_date(file) or today
        to_add.append((url, lastmod, "monthly", "0.8"))

    # ── 3. Tool pages: tools/**/index.php → /tools/{path} ─────────────────────
    for url, lastmod, changefreq, priority in discover_tool_urls():
        if url in existing_urls:
            continue
        to_add.append((url, lastmod, changefreq, priority))

    # ── 4. Add missing entries ────────────────────────────────────────────────
    for url, lastmod, changefreq, priority in to_add:
        print(f"  + Adding: {url}")
        url_el = ET.SubElement(root, "url")
        ET.SubElement(url_el, "loc").text = url
        ET.SubElement(url_el, "lastmod").text = lastmod
        ET.SubElement(url_el, "changefreq").text = changefreq
        ET.SubElement(url_el, "priority").text = priority

    if to_add:
        if hasattr(ET, "indent"):
            ET.indent(tree, space="    ")
        tree.write(SITEMAP_FILE, encoding="UTF-8", xml_declaration=True)
        print(f"\n✅ Sitemap updated — {len(to_add)} new URL(s) added.")
    else:
        print("✅ Sitemap is already up to date.")


if __name__ == "__main__":
    main()
