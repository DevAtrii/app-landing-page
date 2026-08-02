#!/usr/bin/env python3
"""
Generate 16:9 WebP placeholder images for blog articles (step screenshots + cover).

Use before real Play Console / app screenshots are ready. Outputs match article image
conventions (1280×720, root-relative paths under assets/images and assets/cover).

Examples
--------

  # SHA-1 / Play Console article (prefix-based step filenames + cover)
  python3 scripts/generate-article-placeholders.py \\
    --prefix play-console-sha1-sha256 \\
    --cover-file how-to-get-sha1-sha256-google-play-console.webp \\
    --cover-line "How to Get SHA1 & SHA256" \\
    --cover-line "Keys in Google Play Console" \\
    --step "Open Protected with Play in Play Console" \\
    --step "Play Store Protection → Protection" \\
    --step "Manage Play App Signing" \\
    --step "App signing & upload key fingerprints" \\
    --step-footer "SHA-1 / SHA-256 / Firebase signing guide"

  # Custom step filenames (no numeric suffix)
  python3 scripts/generate-article-placeholders.py \\
    --step-output my-step-01.webp --step-label "Step 1" --step-text "Dashboard view" \\
    --step-output my-step-02.webp --step-label "Step 2" --step-text "Settings screen"

  # Cover only
  python3 scripts/generate-article-placeholders.py \\
    --cover-only \\
    --cover-file my-article.webp \\
    --cover-line "Article title line one" \\
    --cover-line "Article title line two"

Requires: Pillow (pip install Pillow)
"""

from __future__ import annotations

import argparse
import hashlib
import json
import random
import sys
from dataclasses import dataclass
from pathlib import Path

try:
    from PIL import Image, ImageDraw, ImageFont
except ImportError:
    print("Error: Pillow is required. Install with: pip install Pillow", file=sys.stderr)
    sys.exit(1)

ROOT = Path(__file__).resolve().parents[1]
DEFAULT_IMAGES_DIR = ROOT / "assets" / "images"
DEFAULT_COVER_DIR = ROOT / "assets" / "cover"
DEFAULT_WIDTH = 1280
DEFAULT_HEIGHT = 720
DEFAULT_QUALITY = 88

# Brand-ish colors (Tailwind blue / slate)
COLOR_BG = (248, 250, 252)
COLOR_HEADER = (37, 99, 235)
COLOR_TITLE = (17, 24, 39)
COLOR_MUTED = (100, 116, 139)
COLOR_COVER_BG = (239, 246, 255)
COLOR_COVER_ACCENT = (37, 99, 235)
COLOR_COVER_PURPLE = (124, 58, 237)
COLOR_COVER_DARK = (15, 23, 42)
COLOR_COVER_LIGHT = (248, 250, 252)


@dataclass
class StepSpec:
    """One step placeholder to render."""

    output_name: str
    badge: str
    label: str


def load_font(size: int, bold: bool = False) -> ImageFont.FreeTypeFont | ImageFont.ImageFont:
    candidates = [
        "/System/Library/Fonts/Supplemental/Arial Bold.ttf" if bold else "/System/Library/Fonts/Supplemental/Arial.ttf",
        "/Library/Fonts/Arial Bold.ttf" if bold else "/Library/Fonts/Arial.ttf",
        "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf" if bold else "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf",
    ]
    for path in candidates:
        if Path(path).exists():
            try:
                return ImageFont.truetype(path, size)
            except OSError:
                continue
    return ImageFont.load_default()


def render_step_placeholder(
    spec: StepSpec,
    *,
    width: int,
    height: int,
    hint: str,
    footer: str,
    quality: int,
    out_path: Path,
) -> None:
    im = Image.new("RGB", (width, height), COLOR_BG)
    draw = ImageDraw.Draw(im)
    margin = 48
    header_h = 72

    draw.rectangle([margin, margin, width - margin, height - margin], outline=COLOR_HEADER, width=3)
    draw.rectangle([margin, margin, width - margin, margin + header_h], fill=COLOR_HEADER)
    draw.text((margin + 24, margin + 20), f"Placeholder — {spec.badge}", fill=(255, 255, 255), font=load_font(32, True))
    draw.text((margin + 24, margin + header_h + 60), spec.label, fill=COLOR_TITLE, font=load_font(36, True))
    draw.text((margin + 24, margin + header_h + 120), hint, fill=COLOR_MUTED, font=load_font(24))
    if footer:
        draw.text((margin + 24, margin + header_h + 170), footer, fill=COLOR_MUTED, font=load_font(20))

    out_path.parent.mkdir(parents=True, exist_ok=True)
    im.save(out_path, "WEBP", quality=quality, method=6)


def render_cover_placeholder(
    lines: list[str],
    *,
    width: int,
    height: int,
    quality: int,
    out_path: Path,
    subtitle: str,
) -> None:
    im = Image.new("RGB", (width, height), COLOR_COVER_BG)
    draw = ImageDraw.Draw(im)
    y = height // 2 - (len(lines) * 40)
    for i, line in enumerate(lines):
        size = 48 if i == 0 else 40
        color = COLOR_TITLE if i == 0 else COLOR_COVER_ACCENT
        draw.text((64, y + i * 80), line, fill=color, font=load_font(size, True))
    draw.text(
        (64, y + len(lines) * 80 + 40),
        subtitle,
        fill=COLOR_MUTED,
        font=load_font(22),
    )
    out_path.parent.mkdir(parents=True, exist_ok=True)
    im.save(out_path, "WEBP", quality=quality, method=6)


def fit_title_lines(
    draw: ImageDraw.ImageDraw,
    title: str,
    *,
    max_width: int,
    max_lines: int = 4,
) -> tuple[list[str], ImageFont.FreeTypeFont | ImageFont.ImageFont]:
    """Wrap a cover title and reduce font size until it fits."""
    words = title.split()
    for font_size in range(62, 39, -2):
        font = load_font(font_size, True)
        lines: list[str] = []
        current = ""
        for word in words:
            candidate = f"{current} {word}".strip()
            bbox = draw.textbbox((0, 0), candidate, font=font)
            if current and bbox[2] - bbox[0] > max_width:
                lines.append(current)
                current = word
            else:
                current = candidate
        if current:
            lines.append(current)
        if len(lines) <= max_lines:
            return lines, font
    return lines[:max_lines], load_font(40, True)


def render_production_cover(
    title: str,
    category: str,
    *,
    width: int,
    height: int,
    quality: int,
    out_path: Path,
    seed_text: str,
) -> None:
    """Render branded text artwork with deterministic decorative shapes."""
    im = Image.new("RGB", (width, height), COLOR_COVER_DARK)
    draw = ImageDraw.Draw(im, "RGBA")

    # Layered diagonal gradient bands keep the image crisp and compressible.
    for x in range(width):
        progress = x / max(width - 1, 1)
        color = (
            int(30 + 40 * progress),
            int(64 + 10 * progress),
            int(175 + 55 * progress),
            255,
        )
        draw.line([(x, 0), (x, height)], fill=color)

    seed = int(hashlib.sha256(seed_text.encode("utf-8")).hexdigest()[:16], 16)
    rng = random.Random(seed)
    shape_colors = [
        (255, 255, 255, 24),
        (*COLOR_COVER_PURPLE, 72),
        (14, 165, 233, 70),
        (99, 102, 241, 76),
    ]
    for _ in range(9):
        diameter = rng.randint(80, 260)
        x = rng.randint(width // 2, width + 60)
        y = rng.randint(-80, height - 20)
        color = rng.choice(shape_colors)
        if rng.choice([True, False]):
            draw.ellipse([x, y, x + diameter, y + diameter], fill=color)
        else:
            draw.rounded_rectangle(
                [x, y, x + diameter, y + diameter],
                radius=max(18, diameter // 5),
                fill=color,
            )

    # Text panel improves contrast while leaving shapes visible.
    panel = [58, 54, int(width * 0.72), height - 54]
    draw.rounded_rectangle(panel, radius=34, fill=(8, 15, 38, 198), outline=(255, 255, 255, 35), width=2)

    badge_font = load_font(22, True)
    badge_text = category.upper()
    badge_bbox = draw.textbbox((0, 0), badge_text, font=badge_font)
    badge_width = badge_bbox[2] - badge_bbox[0] + 34
    draw.rounded_rectangle([92, 92, 92 + badge_width, 136], radius=22, fill=(255, 255, 255, 34))
    draw.text((109, 101), badge_text, fill=(224, 231, 255, 255), font=badge_font)

    lines, title_font = fit_title_lines(draw, title, max_width=int(width * 0.58))
    line_bbox = draw.textbbox((0, 0), "Ag", font=title_font)
    line_height = line_bbox[3] - line_bbox[1] + 15
    y = 178
    for line in lines:
        draw.text((92, y), line, fill=(255, 255, 255, 255), font=title_font)
        y += line_height

    brand_font = load_font(25, True)
    draw.text((92, height - 105), "WebInto.app", fill=(191, 219, 254, 255), font=brand_font)
    draw.rounded_rectangle([width - 118, height - 118, width - 70, height - 70], radius=14, fill=(255, 255, 255, 225))
    draw.text((width - 105, height - 111), "W", fill=COLOR_COVER_ACCENT, font=load_font(27, True))

    out_path.parent.mkdir(parents=True, exist_ok=True)
    im.save(out_path, "WEBP", quality=quality, method=6)


def render_manifest_covers(
    manifest_path: Path,
    *,
    cover_dir: Path,
    width: int,
    height: int,
    quality: int,
) -> int:
    with manifest_path.open(encoding="utf-8") as handle:
        manifest = json.load(handle)
    articles = manifest.get("articles", [])
    if not isinstance(articles, list):
        raise SystemExit("Error: manifest 'articles' must be a list.")

    count = 0
    for article in articles:
        slug = str(article.get("slug", "")).strip()
        title = str(article.get("title", "")).strip()
        category = str(article.get("category", "Article")).strip() or "Article"
        if not slug or not title:
            raise SystemExit("Error: every manifest article needs a slug and title.")
        out_path = cover_dir / f"{slug}.webp"
        render_production_cover(
            title,
            category,
            width=width,
            height=height,
            quality=quality,
            out_path=out_path,
            seed_text=slug,
        )
        print(f"  cover  {out_path.relative_to(ROOT)} ({width}x{height})")
        count += 1
    return count


def build_step_specs(args: argparse.Namespace) -> list[StepSpec]:
    if args.step_output:
        if len(args.step_output) != len(args.step_text):
            raise SystemExit("Error: --step-output and --step-text must appear the same number of times.")
        specs = []
        for i, (name, text) in enumerate(zip(args.step_output, args.step_text, strict=True)):
            badge = args.step_label[i] if args.step_label and i < len(args.step_label) else f"Step {i + 1}"
            specs.append(StepSpec(output_name=name, badge=badge, label=text))
        return specs

    if not args.step:
        return []

    if not args.prefix:
        raise SystemExit("Error: --prefix is required when using --step (e.g. play-console-sha1-sha256).")

    specs = []
    for i, label in enumerate(args.step, start=1):
        specs.append(
            StepSpec(
                output_name=f"{args.prefix}-step-{i:02d}.webp",
                badge=f"Step {i}",
                label=label,
            )
        )
    return specs


def parse_args() -> argparse.Namespace:
    p = argparse.ArgumentParser(
        description="Generate 16:9 WebP placeholder images for blog articles.",
        formatter_class=argparse.RawDescriptionHelpFormatter,
        epilog=__doc__,
    )
    p.add_argument(
        "--images-dir",
        type=Path,
        default=DEFAULT_IMAGES_DIR,
        help=f"Directory for step placeholders (default: {DEFAULT_IMAGES_DIR.relative_to(ROOT)})",
    )
    p.add_argument(
        "--cover-dir",
        type=Path,
        default=DEFAULT_COVER_DIR,
        help=f"Directory for cover placeholder (default: {DEFAULT_COVER_DIR.relative_to(ROOT)})",
    )
    p.add_argument(
        "--manifest",
        type=Path,
        help="Generate production covers for every article in a JSON brief manifest",
    )
    p.add_argument(
        "--prefix",
        help="Filename prefix for steps: {prefix}-step-01.webp, -02, ... (required with --step)",
    )
    p.add_argument(
        "--step",
        action="append",
        default=[],
        metavar="LABEL",
        help="Step description line (repeatable). Auto-numbered as Step 1, 2, ...",
    )
    p.add_argument(
        "--step-output",
        action="append",
        metavar="FILE.webp",
        help="Exact step output filename under --images-dir (repeatable; use with --step-text)",
    )
    p.add_argument(
        "--step-text",
        action="append",
        metavar="TEXT",
        help="Step label text (repeatable; pairs with --step-output)",
    )
    p.add_argument(
        "--step-label",
        action="append",
        metavar="BADGE",
        help="Badge override e.g. 'Step 1' (optional; one per --step-output)",
    )
    p.add_argument(
        "--step-hint",
        default="Replace with screenshot (16:9)",
        help="Muted hint under each step label",
    )
    p.add_argument(
        "--step-footer",
        default="",
        help="Optional second muted line on step placeholders",
    )
    p.add_argument(
        "--cover-file",
        help="Cover output filename (e.g. how-to-get-sha1-sha256-google-play-console.webp)",
    )
    p.add_argument(
        "--cover-line",
        action="append",
        default=[],
        metavar="TEXT",
        help="Cover title line (repeatable; first line is largest)",
    )
    p.add_argument(
        "--cover-subtitle",
        default="Cover placeholder — replace with 16:9 artwork",
        help="Small text under cover title lines",
    )
    p.add_argument(
        "--cover-only",
        action="store_true",
        help="Generate only the cover image (no steps)",
    )
    p.add_argument(
        "--steps-only",
        action="store_true",
        help="Generate only step images (no cover)",
    )
    p.add_argument("--width", type=int, default=DEFAULT_WIDTH, help="Image width (default: 1280)")
    p.add_argument("--height", type=int, default=DEFAULT_HEIGHT, help="Image height (default: 720)")
    p.add_argument("--quality", type=int, default=DEFAULT_QUALITY, help="WebP quality 1-100 (default: 88)")
    return p.parse_args()


def main() -> None:
    args = parse_args()

    if args.manifest:
        manifest_path = args.manifest if args.manifest.is_absolute() else ROOT / args.manifest
        count = render_manifest_covers(
            manifest_path,
            cover_dir=args.cover_dir,
            width=args.width,
            height=args.height,
            quality=args.quality,
        )
        print(f"Done. {count} production cover(s)")
        return

    steps = [] if args.cover_only else build_step_specs(args)

    if not args.cover_only and not steps and not args.cover_line:
        print("Nothing to generate. Use --step, --step-output/--step-text, and/or --cover-line.", file=sys.stderr)
        sys.exit(1)

    if not args.steps_only and args.cover_line:
        if not args.cover_file:
            print("Error: --cover-file is required when using --cover-line.", file=sys.stderr)
            sys.exit(1)
        cover_path = args.cover_dir / args.cover_file
        render_cover_placeholder(
            args.cover_line,
            width=args.width,
            height=args.height,
            quality=args.quality,
            out_path=cover_path,
            subtitle=args.cover_subtitle,
        )
        print(f"  cover  {cover_path.relative_to(ROOT)} ({args.width}x{args.height})")

    for spec in steps:
        out_path = args.images_dir / spec.output_name
        render_step_placeholder(
            spec,
            width=args.width,
            height=args.height,
            hint=args.step_hint,
            footer=args.step_footer,
            quality=args.quality,
            out_path=out_path,
        )
        print(f"  step   {out_path.relative_to(ROOT)} ({args.width}x{args.height})")

    print(f"Done. {len(steps)} step(s)" + ("" if args.steps_only or not args.cover_line else " + cover"))


if __name__ == "__main__":
    main()
