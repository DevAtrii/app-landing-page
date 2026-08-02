#!/usr/bin/env python3
"""Compose 1280x720 article screenshots: text left, phone mockup right."""

from __future__ import annotations

from pathlib import Path

from PIL import Image, ImageDraw, ImageFilter, ImageFont

ROOT = Path(__file__).resolve().parents[1]
IMAGES = ROOT / "assets" / "images"
FRAME_PATH = IMAGES / "phone-mockup-frame.webp"

# Transparent screen hole in phone_frame_levendar.webp
SCREEN_BOX = (20, 20, 693, 1459)

CANVAS_W, CANVAS_H = 1280, 720
PHONE_TARGET_H = 640

# step_num, output filename, raw filename, title, body
STEPS = [
    (
        1,
        "webinto-app-dashboard-create-app.webp",
        "webinto-app-dashboard-create-app.webp",
        "Create a new app",
        "Open the WebInto.app dashboard and tap Create app to launch the six-step wizard.",
    ),
    (
        2,
        "webinto-app-enter-website-url.webp",
        "webinto-app-enter-website-url.webp",
        "Enter your website URL",
        "Paste your live HTTPS address. The app loads this page inside the Android WebView shell.",
    ),
    (
        3,
        "webinto-app-name-and-package.webp",
        "webinto-app-name-and-package.webp",
        "App name and package",
        "Set the customer-facing name and a unique package name for Google Play Console.",
    ),
    (
        4,
        "webinto-app-upload-app-icon.webp",
        "webinto-app-upload-app-icon.webp",
        "Upload your app icon",
        "Tap to select a launcher icon that represents your app on the home screen.",
    ),
    (
        4,
        "webinto-app-theme-colors-loader.webp",
        "webinto-app-theme-colors-loader.webp",
        "Theme and colors",
        "Pick primary color, light or dark theme, and optional dynamic colors with a live preview.",
    ),
    (
        5,
        "webinto-app-splash-screen-template.webp",
        "webinto-app-splash-screen-template.webp",
        "Pick a splash screen",
        "Choose a built-in splash template. Switch designs or edit custom HTML later.",
    ),
    (
        6,
        "webinto-app-plugins-bottom-navigation.webp",
        "webinto-app-plugins-bottom-navigation.webp",
        "Enable plugins",
        "Turn on bottom navigation, edge-to-edge layout, and cache. Then tap Create app.",
    ),
    (
        7,
        "webinto-app-preview-and-build.webp",
        "webinto-app-preview-and-build.webp",
        "Preview and build",
        "Preview your site inside the shell, then tap Build to generate a signed APK.",
    ),
    (
        9,
        "webinto-app-links-permissions-custom-code.webp",
        "webinto-app-links-permissions-custom-code.webp",
        "Links and custom code",
        "Scope link rules, permissions, and CSS or JavaScript injections by URL pattern.",
    ),
    (
        10,
        "webinto-app-download-apk-aab-keystore.webp",
        "webinto-app-download-apk-aab-keystore.webp",
        "Download APK and AAB",
        "Download the installable APK, Play-ready AAB, and signing keystore in one package.",
    ),
]


def load_font(size: int, bold: bool = False, heavy: bool = False) -> ImageFont.FreeTypeFont | ImageFont.ImageFont:
    if heavy:
        candidates = [
            "/System/Library/Fonts/SFNSDisplay-Bold.otf",
            "/System/Library/Fonts/Supplemental/Arial Bold.ttf",
            "/Library/Fonts/Arial Bold.ttf",
            "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf",
        ]
    elif bold:
        candidates = [
            "/System/Library/Fonts/SFNS.ttf",
            "/System/Library/Fonts/Supplemental/Arial Bold.ttf",
            "/Library/Fonts/Arial Bold.ttf",
            "/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf",
        ]
    else:
        candidates = [
            "/System/Library/Fonts/SFNS.ttf",
            "/System/Library/Fonts/Supplemental/Arial.ttf",
            "/Library/Fonts/Arial.ttf",
            "/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf",
        ]
    for path in candidates:
        if Path(path).exists():
            try:
                return ImageFont.truetype(path, size)
            except OSError:
                continue
    return ImageFont.load_default()


def gradient_background(w: int, h: int) -> Image.Image:
    top = (239, 246, 255)
    bottom = (240, 253, 250)
    img = Image.new("RGB", (w, h))
    px = img.load()
    for y in range(h):
        t = y / max(h - 1, 1)
        r = int(top[0] + (bottom[0] - top[0]) * t)
        g = int(top[1] + (bottom[1] - top[1]) * t)
        b = int(top[2] + (bottom[2] - top[2]) * t)
        for x in range(w):
            px[x, y] = (r, g, b)
    return img


def wrap_text(text: str, font: ImageFont.FreeTypeFont, max_width: int, draw: ImageDraw.ImageDraw) -> list[str]:
    words = text.split()
    lines: list[str] = []
    current: list[str] = []
    for word in words:
        trial = " ".join(current + [word])
        if draw.textlength(trial, font=font) <= max_width:
            current.append(word)
        else:
            if current:
                lines.append(" ".join(current))
            current = [word]
    if current:
        lines.append(" ".join(current))
    return lines


def build_phone(screenshot: Image.Image, frame: Image.Image) -> Image.Image:
    sx0, sy0, sx1, sy1 = SCREEN_BOX
    sw, sh = sx1 - sx0, sy1 - sy0
    screen = screenshot.convert("RGB").resize((sw, sh), Image.LANCZOS)
    phone = frame.copy()
    phone.paste(screen, (sx0, sy0))
    scale = PHONE_TARGET_H / phone.height
    new_w = int(phone.width * scale)
    return phone.resize((new_w, PHONE_TARGET_H), Image.LANCZOS)


def draw_text_block(
    draw: ImageDraw.ImageDraw,
    step: int,
    title: str,
    body: str,
    x: int,
    y: int,
    max_width: int,
) -> None:
    badge_font = load_font(14, bold=True)
    title_font = load_font(56, heavy=True)
    body_font = load_font(22)

    badge = f"STEP {step:02d}"
    draw.rounded_rectangle(
        [x, y, x + draw.textlength(badge, font=badge_font) + 28, y + 34],
        radius=8,
        fill=(219, 234, 254),
    )
    draw.text((x + 14, y + 8), badge, fill=(29, 78, 216), font=badge_font)
    y += 52

    for line in wrap_text(title, title_font, max_width, draw):
        draw.text((x, y), line, fill=(17, 24, 39), font=title_font)
        y += 62

    y += 12
    for line in wrap_text(body, body_font, max_width, draw):
        draw.text((x, y), line, fill=(55, 65, 81), font=body_font)
        y += 32


def compose_step(
    screenshot_path: Path,
    output_path: Path,
    step: int,
    title: str,
    body: str,
    frame: Image.Image,
) -> None:
    canvas = gradient_background(CANVAS_W, CANVAS_H)
    draw = ImageDraw.Draw(canvas)

    blob = Image.new("RGBA", (320, 320), (0, 0, 0, 0))
    blob_draw = ImageDraw.Draw(blob)
    blob_draw.ellipse([0, 0, 320, 320], fill=(191, 219, 254, 90))
    canvas.paste(blob, (40, CANVAS_H - 200), blob)

    phone = build_phone(Image.open(screenshot_path), frame)

    shadow = Image.new("RGBA", (phone.width + 40, phone.height + 40), (0, 0, 0, 0))
    shadow_draw = ImageDraw.Draw(shadow)
    shadow_draw.rounded_rectangle(
        [20, 20, phone.width + 20, phone.height + 20],
        radius=36,
        fill=(15, 23, 42, 55),
    )
    shadow = shadow.filter(ImageFilter.GaussianBlur(12))

    phone_x = CANVAS_W - phone.width - 56
    phone_y = (CANVAS_H - phone.height) // 2 + 8
    canvas.paste(shadow, (phone_x - 20, phone_y - 10), shadow)
    canvas.paste(phone, (phone_x, phone_y), phone)

    text_max_w = phone_x - 80
    draw_text_block(draw, step, title, body, 64, 96, text_max_w)

    brand_font = load_font(18, bold=True)
    draw.text((64, CANVAS_H - 48), "WebInto.app", fill=(37, 99, 235), font=brand_font)

    canvas.save(output_path, "WEBP", quality=90, method=6)
    print(f"  saved {output_path.name} (step {step:02d}, {CANVAS_W}x{CANVAS_H})")


def main() -> None:
    frame = Image.open(FRAME_PATH).convert("RGBA")
    raw_dir = IMAGES / "_raw-screenshots"
    raw_dir.mkdir(exist_ok=True)

    for step_num, output_name, raw_name, title, body in STEPS:
        raw_path = raw_dir / raw_name
        if not raw_path.exists():
            raise FileNotFoundError(f"Missing raw screenshot: {raw_path}")
        compose_step(raw_path, IMAGES / output_name, step_num, title, body, frame)

    # Remove deprecated composites
    for stale in (
        "webinto-app-icon-theme-loader.webp",
        "webinto-app-remote-url-update.webp",
    ):
        stale_path = IMAGES / stale
        if stale_path.exists():
            stale_path.unlink()
            print(f"  removed stale {stale}")

    print("Done.")


if __name__ == "__main__":
    main()
