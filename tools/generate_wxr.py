#!/usr/bin/env python3
"""
Genera un archivo WXR (WordPress eXtended RSS) para importar los artículos
de articulos_publicacion/ a WordPress.

Uso:
    python tools/generate_wxr.py

Salida:
    data/nihilnovi_articles_import.xml

Notas:
- Los artículos se importan como posts publicados.
- Las categorías se infieren del prefijo del nombre de archivo (FIL, ECO, MAT, HIS, CIE).
- Los artículos del Módulo I se marcan en la categoría "lecciones".
- El contenido se inserta como HTML; WordPress lo convertirá a bloques HTML si contiene markup.
- Revisa y ajusta títulos, extractos e imágenes destacadas antes de importar.
"""

import os
import re
import html
from pathlib import Path
from datetime import datetime, timedelta

BASE_DIR = Path(__file__).resolve().parent.parent
ARTICLES_DIR = BASE_DIR / "articulos_publicacion"
OUTPUT_FILE = BASE_DIR / "data" / "nihilnovi_articles_import.xml"

DISCIPLINE_MAP = {
    "FIL": ("filosofia", "Filosofía"),
    "ECO": ("economia", "Economía"),
    "MAT": ("matematicas", "Matemáticas"),
    "HIS": ("historia", "Historia"),
    "CIE": ("ciencia", "Ciencia"),
}


def slugify(text: str) -> str:
    text = text.lower()
    text = re.sub(r"[^\w\s-]", "", text)
    text = re.sub(r"[\s-]+", "-", text).strip("-")
    return text


def md_to_html(text: str) -> str:
    """Conversión muy básica de Markdown a HTML."""
    # HTML blocks are preserved.
    # Headings
    text = re.sub(r"^#### (.*?)$", r"<h4>\1</h4>", text, flags=re.MULTILINE)
    text = re.sub(r"^### (.*?)$", r"<h3>\1</h3>", text, flags=re.MULTILINE)
    text = re.sub(r"^## (.*?)$", r"<h2>\1</h2>", text, flags=re.MULTILINE)
    text = re.sub(r"^# (.*?)$", r"<h1>\1</h1>", text, flags=re.MULTILINE)
    # Bold / italic
    text = re.sub(r"\*\*\*(.*?)\*\*\*", r"<strong><em>\1</em></strong>", text)
    text = re.sub(r"\*\*(.*?)\*\*", r"<strong>\1</strong>", text)
    text = re.sub(r"\*(.*?)\*", r"<em>\1</em>", text)
    # Blockquote
    lines = text.split("\n")
    in_quote = False
    out_lines = []
    for line in lines:
        if line.strip().startswith("> "):
            content = line.strip()[2:]
            if not in_quote:
                out_lines.append("<blockquote>")
                in_quote = True
            out_lines.append(content + "<br/>")
        else:
            if in_quote:
                out_lines.append("</blockquote>")
                in_quote = False
            out_lines.append(line)
    if in_quote:
        out_lines.append("</blockquote>")
    text = "\n".join(out_lines)
    # Lists
    text = re.sub(r"^\*   (.*?)$", r"<li>\1</li>", text, flags=re.MULTILINE)
    text = re.sub(r"(<li>.*?</li>\n?)+", lambda m: f"<ul>{m.group(0)}</ul>", text, flags=re.DOTALL)
    # Paragraphs
    paragraphs = text.split("\n\n")
    wrapped = []
    for p in paragraphs:
        p = p.strip()
        if not p:
            continue
        if p.startswith("<") and not p.startswith("<blockquote>"):
            wrapped.append(p)
        else:
            wrapped.append(f"<p>{p}</p>")
    return "\n\n".join(wrapped)


def parse_article(path: Path) -> dict:
    content = path.read_text(encoding="utf-8")
    parts = path.stem.split("_")
    prefix = parts[0].upper() if parts else "FIL"
    discipline_slug, discipline_name = DISCIPLINE_MAP.get(prefix, ("filosofia", "Filosofía"))

    # Title guess: first H1/H2 or filename-based fallback.
    title_match = re.search(r"^#+ (.+)$", content, re.MULTILINE)
    if title_match:
        title = title_match.group(1).strip()
    else:
        title = path.stem.replace("_", " ")

    is_lesson = "Modulo_I_Mito_al_Logos" in str(path)
    categories = [(discipline_slug, discipline_name)]
    if is_lesson:
        categories.append(("lecciones", "Lecciones"))

    # Clean WP HTML comments for widgets remain untouched.
    html_content = md_to_html(content)

    return {
        "title": title,
        "slug": slugify(title) or slugify(path.stem),
        "content": html_content,
        "categories": categories,
        "is_lesson": is_lesson,
        "filename": path.name,
    }


def build_wxr(articles: list[dict]) -> str:
    pub_date = datetime.utcnow().strftime("%a, %d %b %Y %H:%M:%S +0000")
    site_url = "https://nihilnovi.xyz"

    categories_xml = []
    seen_cats = set()
    for art in articles:
        for slug, name in art["categories"]:
            if slug not in seen_cats:
                seen_cats.add(slug)
                categories_xml.append(
                    f'  <wp:category><wp:category_nicename>{slug}</wp:category_nicename>'
                    f'<wp:cat_name><![CDATA[{name}]]></wp:cat_name></wp:category>'
                )

    items_xml = []
    base_date = datetime(2026, 7, 1, 12, 0, 0)
    for idx, art in enumerate(articles):
        post_date = (base_date + timedelta(days=idx)).strftime("%Y-%m-%d %H:%M:%S")
        post_date_gmt = post_date
        cats = "\n".join(
            f'      <category domain="category" nicename="{slug}"><![CDATA[{name}]]></category>'
            for slug, name in art["categories"]
        )
        items_xml.append(f"""    <item>
      <title>{html.escape(art['title'])}</title>
      <link>{site_url}/{art['slug']}/</link>
      <pubDate>{pub_date}</pubDate>
      <dc:creator><![CDATA[davidlegorreta]]></dc:creator>
      <guid isPermaLink="false">{site_url}/?p={1000 + idx}</guid>
      <description></description>
      <content:encoded><![CDATA[{art['content']}]]></content:encoded>
      <excerpt:encoded><![CDATA[]]></excerpt:encoded>
      <wp:post_id>{1000 + idx}</wp:post_id>
      <wp:post_date>{post_date}</wp:post_date>
      <wp:post_date_gmt>{post_date_gmt}</wp:post_date_gmt>
      <wp:post_name>{art['slug']}</wp:post_name>
      <wp:status>publish</wp:status>
      <wp:post_type>post</wp:post_type>
{cats}
      <wp:postmeta>
        <wp:meta_key>_nihilnovi_import_source</wp:meta_key>
        <wp:meta_value><![CDATA[{art['filename']}]]></wp:meta_value>
      </wp:postmeta>
    </item>""")

    wxr = f"""<?xml version="1.0" encoding="UTF-8" ?>
<rss version="2.0"
    xmlns:excerpt="http://wordpress.org/export/1.2/excerpt/"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
    xmlns:wfw="http://wellformedweb.org/CommentAPI/"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:wp="http://wordpress.org/export/1.2/"
>
  <channel>
    <title>Nihil Novi</title>
    <link>{site_url}</link>
    <description>Hub editorial y académico</description>
    <pubDate>{pub_date}</pubDate>
    <language>es</language>
    <wp:wxr_version>1.2</wp:wxr_version>
    <wp:base_site_url>{site_url}</wp:base_site_url>
    <wp:base_blog_url>{site_url}</wp:base_blog_url>
{chr(10).join(categories_xml)}
{chr(10).join(items_xml)}
  </channel>
</rss>
"""
    return wxr


def main():
    articles = []
    for root, _, files in os.walk(ARTICLES_DIR):
        for fname in sorted(files):
            if not fname.endswith(".md"):
                continue
            path = Path(root) / fname
            try:
                articles.append(parse_article(path))
            except Exception as e:
                print(f"Error procesando {path}: {e}")

    if not articles:
        print("No se encontraron artículos.")
        return

    OUTPUT_FILE.parent.mkdir(parents=True, exist_ok=True)
    OUTPUT_FILE.write_text(build_wxr(articles), encoding="utf-8")
    print(f"Generado {OUTPUT_FILE} con {len(articles)} artículos.")


if __name__ == "__main__":
    main()
