#!/usr/bin/env python3
"""Validate generated blog articles against one or more brief manifests."""

from __future__ import annotations

import argparse
import json
import re
import sys
from pathlib import Path

from PIL import Image

ROOT = Path(__file__).resolve().parents[1]
ARTICLES_DIR = ROOT / "articles"
COVERS_DIR = ROOT / "assets" / "cover"
FORBIDDEN_PHRASES = (
    "in today's digital world",
    "it is important to note",
    "in conclusion, it is clear",
)


def parse_args() -> argparse.Namespace:
    parser = argparse.ArgumentParser(description=__doc__)
    parser.add_argument(
        "manifests",
        nargs="+",
        type=Path,
        help="JSON brief manifests to validate",
    )
    return parser.parse_args()


def load_articles(manifest_paths: list[Path]) -> list[dict[str, object]]:
    articles: list[dict[str, object]] = []
    seen: set[str] = set()
    for path in manifest_paths:
        resolved = path if path.is_absolute() else ROOT / path
        data = json.loads(resolved.read_text(encoding="utf-8"))
        for article in data.get("articles", []):
            slug = str(article.get("slug", "")).strip()
            if not slug:
                raise ValueError(f"{resolved}: article without slug")
            if slug in seen:
                raise ValueError(f"duplicate manifest slug: {slug}")
            seen.add(slug)
            articles.append(article)
    return articles


def frontmatter_value(text: str, key: str) -> str:
    match = re.search(rf'^{re.escape(key)}:\s+"(.*)"$', text, re.MULTILINE)
    return match.group(1) if match else ""


def validate_article(article: dict[str, object], known_slugs: set[str]) -> list[str]:
    slug = str(article["slug"])
    path = ARTICLES_DIR / f"{slug}.md"
    errors: list[str] = []
    if not path.exists():
        return [f"{slug}: article file is missing"]

    text = path.read_text(encoding="utf-8")
    parts = text.split("\n---\n", 1)
    if len(parts) != 2:
        return [f"{slug}: invalid frontmatter delimiters"]
    meta, body = parts

    description = frontmatter_value(meta, "description")
    if not 150 <= len(description) <= 160:
        errors.append(f"description is {len(description)} characters")

    title = frontmatter_value(meta, "title")
    if title != article.get("title"):
        errors.append("frontmatter title does not match brief")

    word_count = len(re.findall(r"\b[\w’'-]+\b", body))
    expected = str(article.get("wordCount", "2000-2500"))
    match = re.fullmatch(r"(\d+)-(\d+)", expected)
    low, high = (int(match.group(1)), int(match.group(2))) if match else (2000, 2500)
    if not low <= word_count <= high:
        errors.append(f"body has {word_count} words; expected {low}-{high}")

    if re.search(r"^# ", body, re.MULTILINE):
        errors.append("body contains an H1")
    if "—" in body:
        errors.append("body contains an em dash")
    if body.count("!") > 1:
        errors.append("body contains more than one exclamation mark")
    for phrase in FORBIDDEN_PHRASES:
        if phrase in body.lower():
            errors.append(f'body contains forbidden phrase "{phrase}"')

    h2_count = len(re.findall(r"^## ", body, re.MULTILINE))
    if not 7 <= h2_count <= 9:
        errors.append(f"body has {h2_count} H2 headings; expected 7-9 including Conclusion and FAQ")
    if "## Conclusion" not in body or "## FAQ" not in body:
        errors.append("Conclusion or FAQ section is missing")
    else:
        faq = body.split("## FAQ", 1)[1]
        question_count = len(re.findall(r"^### ", faq, re.MULTILINE))
        if not 4 <= question_count <= 5:
            errors.append(f"FAQ has {question_count} questions; expected 4-5")

    cover = COVERS_DIR / f"{slug}.webp"
    if not cover.exists():
        errors.append("cover image is missing")
    else:
        with Image.open(cover) as image:
            if image.format != "WEBP" or image.size != (1280, 720):
                errors.append(f"cover is {image.format} {image.size}; expected WEBP 1280x720")

    for target in re.findall(r"\]\(/blogs/([a-z0-9-]+)", body):
        if target not in known_slugs:
            errors.append(f"internal link target does not exist: {target}")

    return [f"{slug}: {error}" for error in errors]


def main() -> None:
    args = parse_args()
    articles = load_articles(args.manifests)
    known_slugs = {path.stem for path in ARTICLES_DIR.glob("*.md")}
    errors: list[str] = []
    for article in articles:
        errors.extend(validate_article(article, known_slugs))

    print(f"Validated {len(articles)} article brief(s).")
    if errors:
        for error in errors:
            print(f"ERROR: {error}")
        raise SystemExit(1)
    print("All checks passed.")


if __name__ == "__main__":
    main()
