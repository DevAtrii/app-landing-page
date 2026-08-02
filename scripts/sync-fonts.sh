#!/usr/bin/env bash
# Regenerate assets/vendor/fonts from @fontsource packages + Material Icons (run after npm install).
set -euo pipefail
ROOT="$(cd "$(dirname "$0")/.." && pwd)"
cd "$ROOT"

FONTS_DIR="assets/vendor/fonts"
GEIST_WEIGHTS=(100 200 300 400 500 600 700 800 900)
CRITICAL_WEIGHTS=(500 700 800 900)
DEFERRED_WEIGHTS=(100 200 300 400 600)

mkdir -p "$FONTS_DIR"

# Remove legacy Nunito / Quicksand files
rm -f "$FONTS_DIR"/nunito-*.woff2 "$FONTS_DIR"/quicksand-*.woff2

for w in "${GEIST_WEIGHTS[@]}"; do
  cp "node_modules/@fontsource/geist-sans/files/geist-sans-latin-${w}-normal.woff2" "$FONTS_DIR/"
done

rewrite_font_urls() {
  sed 's|url(./files/|url(/assets/vendor/fonts/|g' \
    | sed "s|, url([^)]*\\.woff)||g" \
    | sed "s|) format('woff2') format('woff');|) format('woff2');|g"
}

emit_geist_faces() {
  local -a weights=("$@")
  for w in "${weights[@]}"; do
    awk "/geist-sans-latin-${w}-normal/,/^}/" "node_modules/@fontsource/geist-sans/${w}.css"
  done | rewrite_font_urls
}

{
  echo "/* Self-hosted: Geist Sans (latin), Material Icons — served first-party for privacy */"
  emit_geist_faces "${GEIST_WEIGHTS[@]}"
} > "$FONTS_DIR/app-fonts.css"

{
  echo "/* Above-the-fold text only — critical Geist Sans weights (load before deferred faces) */"
  emit_geist_faces "${CRITICAL_WEIGHTS[@]}"
} > "$FONTS_DIR/app-fonts-critical.css"

{
  echo "/* Remaining Geist Sans weights — load async after first paint */"
  emit_geist_faces "${DEFERRED_WEIGHTS[@]}"
} > "$FONTS_DIR/app-fonts-deferred.css"

cat > "$FONTS_DIR/material-icons.css" << 'EOF'
@font-face {
  font-family: 'Material Icons';
  font-style: normal;
  font-weight: 400;
  font-display: swap;
  src: url(/assets/vendor/fonts/material-icons.ttf) format('truetype');
}
.material-icons {
  font-family: 'Material Icons';
  font-weight: normal;
  font-style: normal;
  font-size: 24px;
  line-height: 1;
  letter-spacing: normal;
  text-transform: none;
  display: inline-block;
  white-space: nowrap;
  word-wrap: normal;
  direction: ltr;
  -webkit-font-smoothing: antialiased;
  text-rendering: optimizeLegibility;
  -moz-osx-font-smoothing: grayscale;
  font-feature-settings: 'liga';
}
EOF

curl -sLf -o "$FONTS_DIR/material-icons.ttf" "https://fonts.gstatic.com/s/materialicons/v145/flUhRq6tzZclQEJ-Vdg-IuiaDsNZ.ttf"
echo "assets/vendor/fonts updated."
